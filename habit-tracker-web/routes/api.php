<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\GoalCategoryController;

/*
|--------------------------------------------------------------------------
| AUTH (public)
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => 'HabitTracker API'
    ]);
});


Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->name('api.') 
    ->group(function () {

        Route::get('/me',     [AuthController::class, 'me'])->name('me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        /*
        |--------------------------------------------------------------------------
        | GOAL CATEGORIES CRUD
        |--------------------------------------------------------------------------
        */
        Route::apiResource('goal-categories', GoalCategoryController::class);

        /*
        |--------------------------------------------------------------------------
        | GOALS CRUD
        |--------------------------------------------------------------------------
        */
        Route::apiResource('goals', GoalController::class);

        /*
        |--------------------------------------------------------------------------
        | GOAL DAYS (logika dni)
        |--------------------------------------------------------------------------
        */
        Route::get('/goals/{goal}/days', [GoalController::class, 'days'])
            ->name('goals.days');

        Route::patch('/goals/{goal}/days/{date}/done', [GoalController::class, 'markDoneForDate'])
            ->name('goals.days.done');

        Route::patch('/goals/{goal}/days/{date}/status', [GoalController::class, 'setStatusForDate'])
            ->name('goals.days.status');

        Route::patch('/goals/{goal}/days/{date}/note', [GoalController::class, 'setNoteForDate'])
            ->name('goals.days.note');
        Route::delete('/goals/{goal}/days/{date}/note', [GoalController::class, 'clearNoteForDate'])
            ->name('goals.days.note.delete');
        Route::get('/public-goals', [GoalController::class, 'publicIndex'])
            ->name('goals.public');
    });
