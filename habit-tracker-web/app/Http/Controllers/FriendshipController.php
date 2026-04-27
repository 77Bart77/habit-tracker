<?php

namespace App\Http\Controllers;

use App\Services\FriendshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FriendshipController extends Controller
{
    private FriendshipService $service;

    public function __construct(FriendshipService $service)
    {
        
        $this->service = $service;
    }

    //index
    public function index()
    {
        $userId = Auth::id();

        return view('friends.index', [
            'pendingRequests' => $this->service->getPendingRequests($userId),
            'sentRequests'    => $this->service->getSentRequests($userId),
            'friends'         => $this->service->getFriends($userId),
        ]);
    }
//wyslji
    public function send(int $user)
    {
        $fromUserId = Auth::id();
        $toUserId   = $user;

        try {
            $this->service->sendFriendRequest($fromUserId, $toUserId);

            return back()->with('success', 'Zaproszenie wysłane.');
        } catch (ValidationException $e) {
            // Zwracamy błędy walidacyjne do sesji
            return back()->withErrors($e->errors());
        }
    }

    //akceptacja
    public function accept(int $friendship)
    {
        $currentUserId = Auth::id();

        try {
            $this->service->acceptFriendRequest($friendship, $currentUserId);

            return back()->with('success', 'Zaproszenie zaakceptowane.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    //odrzucenie
    public function decline(int $friendship)
    {
        $currentUserId = Auth::id();

        try {
            $this->service->declineFriendRequest($friendship, $currentUserId);

            return back()->with('success', 'Zaproszenie odrzucone.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    //remove
    public function remove(int $user)
    {
        $currentUserId = Auth::id();
        $friendId      = $user;

        try {
            $this->service->removeFriend($currentUserId, $friendId);

            return back()->with('success', 'Znajomy usunięty.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    //blokowanie
    public function block(int $user)
    {
        $currentUserId = Auth::id();
        $blockedUserId = $user;

        try {
            $this->service->blockUser($currentUserId, $blockedUserId);

            return back()->with('success', 'Użytkownik zablokowany.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}
