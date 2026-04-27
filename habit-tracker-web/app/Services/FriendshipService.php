<?php

namespace App\Services;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class FriendshipService
{
    //statusy relacji
    public const STATUS_PENDING  = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_BLOCKED  = 'blocked';

    
    //wysylanie zaproszenia , zwracamy obiekt relacji
    public function sendFriendRequest(int $fromUserId, int $toUserId): Friendship
    {
        //  Nie można zaprosić samego siebie
        if ($fromUserId === $toUserId) {
            throw ValidationException::withMessages([
                'friend' => 'Nie możesz wysłać zaproszenia do samego siebie.',
            ]);
        }

        //sprawdzamy czy user istnieja 
        User::query()->findOrFail($fromUserId);
        User::query()->findOrFail($toUserId);

        // sprawdzamy czy ktokolwiek zablokowal dryga strone 
        if ($this->isBlockedEitherWay($fromUserId, $toUserId)) {
            throw ValidationException::withMessages([
                'friend' => 'Nie możesz wysłać zaproszenia (użytkownik jest zablokowany lub Ty jesteś zablokowany).',
            ]);
        }

        // sprawdzamy czy jestesmy znajomymi
        if ($this->areFriends($fromUserId, $toUserId)) {
            throw ValidationException::withMessages([
                'friend' => 'Jesteście już znajomymi.',
            ]);
        }

        // jeden pending miedzy userami, sprawdzamy czy ktos juz wyslal
        if ($this->hasPendingRequest($fromUserId, $toUserId)) {
            throw ValidationException::withMessages([
                'friend' => 'Zaproszenie już istnieje i czeka na akceptację.',
            ]);
        }

        // tworzymy zaproszenie 
        return Friendship::query()->create([
            'user_id'    => $fromUserId,
            'friend_id'  => $toUserId,
            'status'     => self::STATUS_PENDING,
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * Akceptuje zaproszenie (pending) przez odbiorcę.
     * - $requestId: id rekordu friendships
     * - $currentUserId: zalogowany user, który akceptuje (musi być friend_id w tym rekordzie)
     */

    //akceptacja zaproszenia , zwracamy rekord z accepted 
    public function acceptFriendRequest(int $requestId, int $currentUserId): Friendship
    {
        // tranakcja start (powiadomienia rozbuduje pozniej )
        return DB::transaction(function () use ($requestId, $currentUserId) {
        //pobieramy zaproszenie i blikujemy rekord
            $request = Friendship::query()->lockForUpdate()->findOrFail($requestId);

            // sprawdzamy czy zaproszenie jest dla zalogowanego usera 
            if ((int)$request->friend_id !== $currentUserId) {
                throw ValidationException::withMessages([
                    'friend' => 'Nie możesz zaakceptować zaproszenia, które nie jest do Ciebie.',
                ]);
            }

            // sprawdzamy czy jest pending
            if ($request->status !== self::STATUS_PENDING) {
                throw ValidationException::withMessages([
                    'friend' => 'To zaproszenie nie jest już w statusie oczekującym.',
                ]);
            }

            // sprawdzamy czy jest blokada w ktoras strone 
            if ($this->isBlockedEitherWay((int)$request->user_id, (int)$request->friend_id)) {
                throw ValidationException::withMessages([
                    'friend' => 'Nie można zaakceptować – relacja jest zablokowana.',
                ]);
            }

            // Zmieniamy status na accepted
            $request->status = self::STATUS_ACCEPTED;
            $request->save();

            return $request;
        });
    }

    //odrzucenie 
    public function declineFriendRequest(int $requestId, int $currentUserId): void
    {
        //tranzakcja bo blokujemy
        DB::transaction(function () use ($requestId, $currentUserId) {
            //pobieramy i blokujemy
            $request = Friendship::query()->lockForUpdate()->findOrFail($requestId);
            //sprawdzamy czy to zaproszenie jest do mnie 
            if ((int)$request->friend_id !== $currentUserId) {
                throw ValidationException::withMessages([
                    'friend' => 'Nie możesz odrzucić zaproszenia, które nie jest do Ciebie.',
                ]);
            }
            //sprawdzamy czy nadal pending
            if ($request->status !== self::STATUS_PENDING) {
                throw ValidationException::withMessages([
                    'friend' => 'Tego zaproszenia nie da się już odrzucić (status nie jest pending).',
                ]);
            }

            $request->delete();
        });
    }

    //usuwanie
    public function removeFriend(int $userId, int $friendId): void
    {
        DB::transaction(function () use ($userId, $friendId) {

            // Szukamy relacji accepted w dowolną stronę
            $friendship = $this->findFriendshipBetweenUsers($userId, $friendId, self::STATUS_ACCEPTED, true);

            if (!$friendship) {
                throw ValidationException::withMessages([
                    'friend' => 'Nie jesteście znajomymi (brak relacji accepted).',
                ]);
            }

            $friendship->delete();
        });
    }

    
    //userId blokuje blockedUserId zwracamy rekord relacji
    public function blockUser(int $userId, int $blockedUserId): Friendship
    {
        //nie mozna samego siebie 
        if ($userId === $blockedUserId) {
            throw ValidationException::withMessages([
                'friend' => 'Nie możesz zablokować samego siebie.',
            ]);
        }
//tranzakcja bo uzywam lockForUpdate
        return DB::transaction(function () use ($userId, $blockedUserId) {

            // Szukamy relacji w odpowiednia strone 
            $existing = Friendship::query()
                ->where('user_id', $userId)
                ->where('friend_id', $blockedUserId)
                ->lockForUpdate()
                ->first();
//jesli jest zmieniam status na blocked 
            if ($existing) {
                $existing->status = self::STATUS_BLOCKED;
                $existing->save();

                return $existing;
            }

            // Jeśli nie było relacji w tym kierunku, tworzymy blokadę
            return Friendship::query()->create([
                'user_id'    => $userId,
                'friend_id'  => $blockedUserId,
                'status'     => self::STATUS_BLOCKED,
                'created_at' => Carbon::now(),
            ]);
        });
    }

    // sprawdzamy czy user a i b sa znajomymi w dowolna strone 
    public function areFriends(int $userIdA, int $userIdB): bool
    {
        return Friendship::query()
            ->where('status', self::STATUS_ACCEPTED)
            ->where(function ($q) use ($userIdA, $userIdB) {
                $q->where(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('user_id', $userIdA)->where('friend_id', $userIdB);
                })->orWhere(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('user_id', $userIdB)->where('friend_id', $userIdA);
                });
            })
            ->exists();
    }

    //sprawdzamy czy itnieje pending w dwie strony , max 1 pending
    public function hasPendingRequest(int $userIdA, int $userIdB): bool
    {
        return Friendship::query()
            ->where('status', self::STATUS_PENDING)
            ->where(function ($q) use ($userIdA, $userIdB) {
                $q->where(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('user_id', $userIdA)->where('friend_id', $userIdB);
                })->orWhere(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('user_id', $userIdB)->where('friend_id', $userIdA);
                });
            })
            ->exists();
    }

    //poieramy znajomych
    public function getFriends(int $userId): Collection
    {
        //pobranie relacjii accepted 
        $rows = Friendship::query()
            ->where('status', self::STATUS_ACCEPTED)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
            })
            ->get();

        // Zbieramy ID znajomych, jesli userId to ja znajomy jest w friend , jsli friend to ja znajomy jest w user 
        $friendIds = $rows->map(function (Friendship $f) use ($userId) {
            return ((int)$f->user_id === $userId) ? (int)$f->friend_id : (int)$f->user_id;
        })->unique()->values();

        return User::query()
            ->whereIn('id', $friendIds)
            ->get();
    }

    //zaproszenia do zalogowanego usera 
    public function getPendingRequests(int $userId): Collection
    {
        return Friendship::query()
            ->where('status', self::STATUS_PENDING)
            ->where('friend_id', $userId)
            ->latest()
            ->get();
    }

    //zaproszenia od zalogowanego usera 
    public function getSentRequests(int $userId): Collection
    {
        return Friendship::query()
            ->where('status', self::STATUS_PENDING)
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

   
     //helper
    //sprawdzamy czy jest blokada pomiedzy userami
    public function isBlockedEitherWay(int $userIdA, int $userIdB): bool
    {
        return Friendship::query()
        //warunek w dwie strony
            ->where('status', self::STATUS_BLOCKED)
            ->where(function ($q) use ($userIdA, $userIdB) {
                $q->where(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('user_id', $userIdA)->where('friend_id', $userIdB);
                })->orWhere(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('user_id', $userIdB)->where('friend_id', $userIdA);
                });
            })
            ->exists();
    }

    //szukamy rekordu relacji pomiedz a i b w obu kierunkach 
    private function findFriendshipBetweenUsers(
        int $userIdA,
        int $userIdB,
        string $status,
        bool $lock = false
    ): ?Friendship {
        $query = Friendship::query()
            ->where('status', $status)
            ->where(function ($q) use ($userIdA, $userIdB) {
                $q->where(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('user_id', $userIdA)->where('friend_id', $userIdB);
                })->orWhere(function ($q2) use ($userIdA, $userIdB) {
                    $q2->where('user_id', $userIdB)->where('friend_id', $userIdA);
                });
            });
//jsli true to blokujemy ten rekord w bazie 
        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first();
    }
}
