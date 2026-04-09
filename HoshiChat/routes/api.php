<?php

use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

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

    // Message routes - nested under conversations
    Route::post('conversations/{conversation}/messages', [MessageController::class, 'store']);
    Route::get('conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::get('conversations/{conversation}/messages/{message}', [MessageController::class, 'show']);
    Route::put('conversations/{conversation}/messages/{message}', [MessageController::class, 'update']);
    Route::delete('conversations/{conversation}/messages/{message}', [MessageController::class, 'destroy']);
});
