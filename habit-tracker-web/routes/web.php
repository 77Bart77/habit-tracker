<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalCategoryController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendshipController;

use App\Http\Controllers\GoalLikeController;
use App\Http\Controllers\GoalCommentController;
use App\Http\Controllers\ChallengeController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProRequestController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\ProRequestController;

/*
|--------------------------------------------------------------------------
| ROUTY PUBLICZNE (dla każdego)
|--------------------------------------------------------------------------
*/

// Strona powitalna
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Społeczność: publiczne cele (lista + podgląd jednego celu)
Route::get('/public-goals', [GoalController::class, 'publicGoals'])
    ->name('goals.public');

Route::get('/goals/public/{id}', [GoalController::class, 'showPublic'])
    ->whereNumber('id')
    ->name('goals.public.show');

// Publiczny profil użytkownika
Route::get('/users/{user}', [UserController::class, 'show'])
    ->whereNumber('user')
    ->name('users.show');


/*
|--------------------------------------------------------------------------
| ROUTY DLA ZALOGOWANYCH
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profil
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Cele (Twoje cele)
    |--------------------------------------------------------------------------
    */
    Route::get('/goals', [GoalController::class, 'index'])
        ->name('goals.index');

    Route::get('/goals/create', [GoalController::class, 'create'])
        ->name('goals.create');

    Route::post('/goals', [GoalController::class, 'store'])
        ->name('goals.store');

    // ✅ WAŻNE: najpierw konkretne ścieżki (bez {id})
    Route::get('/goals/paused', [GoalController::class, 'paused'])
        ->name('goals.paused');

    Route::get('/goals-history', [GoalController::class, 'history'])
        ->name('goals.history');

    // ✅ Notatki do konkretnego dnia celu (tu masz {goal} i {day} — ograniczamy do liczb)
    Route::get('/goals/{goal}/days/{day}/note', [GoalController::class, 'editNote'])
        ->whereNumber('goal')->whereNumber('day')
        ->name('goals.editNote');

    Route::post('/goals/{goal}/days/{day}/note', [GoalController::class, 'updateNote'])
        ->whereNumber('goal')->whereNumber('day')
        ->name('goals.updateNote');

    Route::delete('/goals/{goal}/days/{day}/note', [GoalController::class, 'deleteNote'])
        ->whereNumber('goal')->whereNumber('day')
        ->name('goals.deleteNote');

    // ✅ Dopiero teraz trasy z {id} (i wszystkie mają whereNumber)
    Route::get('/goals/{id}', [GoalController::class, 'show'])
        ->whereNumber('id')
        ->name('goals.show');

    Route::get('/goals/{id}/edit', [GoalController::class, 'edit'])
        ->whereNumber('id')
        ->name('goals.edit');

    Route::put('/goals/{id}', [GoalController::class, 'update'])
        ->whereNumber('id')
        ->name('goals.update');

    // „Usuń” = wstrzymaj (soft pause)
    Route::post('/goals/{id}/delete', [GoalController::class, 'delete'])
        ->whereNumber('id')
        ->name('goals.delete');

    // Trwałe usunięcie
    Route::delete('/goals/{id}', [GoalController::class, 'destroy'])
        ->whereNumber('id')
        ->name('goals.destroy');

    Route::post('/goals/{id}/resume', [GoalController::class, 'resume'])
        ->whereNumber('id')
        ->name('goals.resume');

    /*
    |--------------------------------------------------------------------------
    | Wykonanie celu + notatki "today"
    |--------------------------------------------------------------------------
    */
    Route::post('/goals/{id}/complete-today', [GoalController::class, 'markCompletedToday'])
        ->whereNumber('id')
        ->name('goals.markCompletedToday');

    Route::get('/goals/{id}/note-today', [GoalController::class, 'noteToday'])
        ->whereNumber('id')
        ->name('goals.noteToday');

    Route::post('/goals/{id}/note-today', [GoalController::class, 'saveNote'])
        ->whereNumber('id')
        ->name('goals.saveNote');

    Route::post('/goals/{id}/note-today/delete', [GoalController::class, 'clearNoteToday'])
        ->whereNumber('id')
        ->name('goals.clearNoteToday');

    /*
    |--------------------------------------------------------------------------
    | Znajomi
    |--------------------------------------------------------------------------
    */
    Route::get('/friends', [FriendshipController::class, 'index'])
        ->name('friends.index');

    Route::post('/friends/request/{user}', [FriendshipController::class, 'send'])
        ->whereNumber('user')
        ->name('friends.request.send');

    Route::post('/friends/accept/{friendship}', [FriendshipController::class, 'accept'])
        ->whereNumber('friendship')
        ->name('friends.request.accept');

    Route::delete('/friends/decline/{friendship}', [FriendshipController::class, 'decline'])
        ->whereNumber('friendship')
        ->name('friends.request.decline');

    Route::delete('/friends/remove/{user}', [FriendshipController::class, 'remove'])
        ->whereNumber('user')
        ->name('friends.remove');

    Route::post('/friends/block/{user}', [FriendshipController::class, 'block'])
        ->whereNumber('user')
        ->name('friends.block');

    /*
    |--------------------------------------------------------------------------
    | Społeczność: Like + komentarze (wymaga konta)
    |--------------------------------------------------------------------------
    */
    Route::post('/goals/{goal}/like', [GoalLikeController::class, 'toggle'])
        ->whereNumber('goal')
        ->name('goals.like.toggle');

    Route::post('/goals/{goalId}/comments', [GoalCommentController::class, 'store'])
        ->whereNumber('goalId')
        ->name('goals.comments.store');

    Route::delete('/comments/{commentId}', [GoalCommentController::class, 'destroy'])
        ->whereNumber('commentId')
        ->name('comments.destroy');

    /*
    |--------------------------------------------------------------------------
    | Challenges (wspólne cele)
    |--------------------------------------------------------------------------
    */
    Route::get('/challenges', [ChallengeController::class, 'index'])
        ->name('challenges.index');

    Route::get('/challenges/create', [ChallengeController::class, 'create'])
        ->name('challenges.create');

    Route::post('/challenges', [ChallengeController::class, 'store'])
        ->name('challenges.store');

    Route::get('/challenges/invites', [ChallengeController::class, 'invites'])
        ->name('challenges.invites');

    Route::post('/challenges/invites/{inviteId}/accept', [ChallengeController::class, 'acceptInvite'])
        ->whereNumber('inviteId')
        ->name('challenges.invites.accept');

    Route::post('/challenges/invites/{inviteId}/decline', [ChallengeController::class, 'declineInvite'])
        ->whereNumber('inviteId')
        ->name('challenges.invites.decline');

    Route::post('/challenges/{id}/toggle-today', [ChallengeController::class, 'toggleToday'])
        ->whereNumber('id')
        ->name('challenges.toggleToday');

    Route::get('/challenges/{id}', [ChallengeController::class, 'show'])
        ->whereNumber('id')
        ->name('challenges.show');

    Route::post('/challenges/{challengeId}/comments', [ChallengeController::class, 'storeComment'])
        ->whereNumber('challengeId')
        ->name('challenges.comments.store');

    Route::delete('/challenge-comments/{commentId}', [ChallengeController::class, 'destroyComment'])
        ->whereNumber('commentId')
        ->name('challengeComments.destroy');

    /*
    |--------------------------------------------------------------------------
    | Kategorie (Twoje + globalne)
    |--------------------------------------------------------------------------
    */
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [GoalCategoryController::class, 'index'])->name('index');
        Route::get('/create', [GoalCategoryController::class, 'create'])->name('create');
        Route::post('/store', [GoalCategoryController::class, 'store'])->name('store');

        Route::get('/{id}/edit', [GoalCategoryController::class, 'edit'])
            ->whereNumber('id')->name('edit');

        Route::post('/{id}/update', [GoalCategoryController::class, 'update'])
            ->whereNumber('id')->name('update');

        Route::delete('/{id}', [GoalCategoryController::class, 'delete'])
            ->whereNumber('id')->name('delete');
    });

    Route::post('/goals/{id}/pro-request', [ProRequestController::class, 'store'])
        ->whereNumber('id')
        ->name('proRequests.store');
});


// Auth (Breeze)
require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}', [AdminUserController::class, 'show'])
            ->name('users.show');

        Route::get('/pro-requests', [AdminProRequestController::class, 'index'])
            ->name('pro_requests.index');

        Route::get('/pro-requests/{proRequest}', [AdminProRequestController::class, 'show'])
            ->name('pro_requests.show');

        Route::patch('/pro-requests/{proRequest}/approve', [AdminProRequestController::class, 'approve'])
            ->name('pro_requests.approve');

        Route::patch('/pro-requests/{proRequest}/reject', [AdminProRequestController::class, 'reject'])
            ->name('pro_requests.reject');
    });
