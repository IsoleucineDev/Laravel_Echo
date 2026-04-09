<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ConversationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Broadcasting\BroadcastManager;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Broadcasting authentication
Route::post('/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Conversation routes
    Route::apiResource('conversations', ConversationController::class);
    Route::post('conversations/{conversation}/users', [ConversationController::class, 'addUser']);
    Route::delete('conversations/{conversation}/users', [ConversationController::class, 'removeUser']);

    // Message routes
    Route::apiResource('messages', MessageController::class);
});
