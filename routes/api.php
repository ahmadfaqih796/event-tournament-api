<?php

use App\Http\Controllers\api\AccountGameController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\EventController;
use App\Http\Controllers\api\GameController;
use App\Http\Controllers\api\NewsController;
use App\Http\Controllers\api\TeamController;
use App\Http\Controllers\api\TeamMemberController;
use App\Http\Controllers\api\TournamentController;
use App\Http\Controllers\api\TournamentRegistrationController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});

Route::controller(AuthController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', 'logout');
    Route::get('/me', 'me');
});

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::controller(UserController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/users', 'store');
    Route::put('/users/{id}', 'update');
    Route::delete('/users/{id}', 'destroy');
});

Route::get('/tournaments', [TournamentController::class, 'index']);
Route::get('/open-tournaments', [TournamentController::class, 'openRegistration']);
Route::get('/tournaments/{id}', [TournamentController::class, 'show']);
Route::controller(TournamentController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/tournaments', 'store');
    Route::put('/tournaments/{id}', 'update');
    Route::delete('/tournaments/{id}', 'destroy');
});

Route::get('/tournament-registration', [TournamentRegistrationController::class, 'index']);
Route::get('/tournament-registration/{id}', [TournamentRegistrationController::class, 'show']);
Route::controller(TournamentRegistrationController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/tournament-registration', 'store');
    Route::put('/tournament-registration/{id}', 'update');
    Route::delete('/tournament-registration/{id}', 'destroy');
});

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
});

Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{id}', [GameController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);
});

Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/teams', [TeamController::class, 'store']);
    Route::put('/teams/{id}', [TeamController::class, 'update']);
    Route::delete('/teams/{id}', [TeamController::class, 'destroy']);
});

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{id}', [NewsController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/news', [NewsController::class, 'store']);
    Route::post('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
