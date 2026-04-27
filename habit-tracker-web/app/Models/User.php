<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Friendship;


class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =========================
    // ZNAJOMOŚCI (friendships)
    // =========================

    /** Zaproszenia wysłane przeze mnie */
    public function friendshipsSent(): HasMany
    {
        return $this->hasMany(Friendship::class, 'user_id', 'id');
    }

    /** Zaproszenia otrzymane przeze mnie */
    public function friendshipsReceived(): HasMany
    {
        return $this->hasMany(Friendship::class, 'friend_id', 'id');
    }

    /**
     * Lista znajomych (zaakceptowanych) jako relacja M:N do users
     * przez tabelę friendships, gdzie ja jestem user_id.
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted');
    }

    /**
     * Czy jestem znajomym z użytkownikiem $otherUserId?
     * Obsługuje oba kierunki relacji (ja->on OR on->ja).
     */
    public function isFriendsWith(int $otherUserId): bool
    {
        return Friendship::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($otherUserId) {
                $q->where(function ($q2) use ($otherUserId) {
                    $q2->where('user_id', $this->id)
                       ->where('friend_id', $otherUserId);
                })->orWhere(function ($q2) use ($otherUserId) {
                    $q2->where('user_id', $otherUserId)
                       ->where('friend_id', $this->id);
                });
            })
            ->exists();
    }

    // =========================
    // TWOJE ISTNIEJĄCE RELACJE
    // =========================

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
            ->withPivot('created_at');
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function assignRole(string|int $role): void
    {
        $roleId = is_numeric($role)
            ? (int) $role
            : Role::where('name', $role)->value('id');

        if ($roleId) {
            $this->roles()->syncWithoutDetaching([
                $roleId => ['created_at' => now()],
            ]);
        }
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements', 'user_id', 'achievement_id')
            ->withPivot('earned_at')
            ->withTimestamps(false);
    }

    public function proRequests()
{
    return $this->hasMany(\App\Models\ProRequest::class, 'user_id');
}

public function reviewedProRequests()
{
    return $this->hasMany(\App\Models\ProRequest::class, 'reviewed_by');
}

public function goals(): HasMany
{
    return $this->hasMany(\App\Models\Goal::class, 'user_id', 'id');
}

}
