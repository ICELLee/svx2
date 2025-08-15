<?php

use App\Http\Controllers\Api\SlotApiController;
use App\Http\Controllers\Tools\SlotTrackerController;
use App\Http\Controllers\User\ExtensionTokenController;
use App\Http\Controllers\User\SlotStatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SlotAdminApiController;


Route::middleware(['auth:sanctum','role:admin'])->prefix('admin')->group(function () {
    Route::get('/slots',               [SlotAdminApiController::class,'index']);
    Route::get('/slots/providers',     [SlotAdminApiController::class,'providers']);
    Route::post('/slots',              [SlotAdminApiController::class,'store']);
    Route::put('/slots/{slot}',        [SlotAdminApiController::class,'update']);
    Route::delete('/slots/{slot}',     [SlotAdminApiController::class,'destroy']);
    Route::patch('/slots/{slot}/toggle',[SlotAdminApiController::class,'toggle']);
    Route::post('/slots/import-url',   [SlotAdminApiController::class,'importFromUrl']);
});
Route::middleware('auth:sanctum')->post('/slot/current', [SlotTrackerController::class, 'store']);
Route::middleware('auth')->prefix('user/api')->group(function () {
    Route::get   ('/extension-tokens',        [SlotTrackerController::class, 'index']);
    Route::post  ('/extension-tokens',        [SlotTrackerController::class, 'store']);
    Route::delete('/extension-tokens/{id}',   [SlotTrackerController::class, 'destroy']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/slot/current', [SlotApiController::class, 'store']); // Extension -> Bearer
});
Route::prefix('user/api')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/slots/stats', [SlotStatController::class, 'upsert'])
        ->name('user.slots.stats.upsert');
    Route::get('/slots/stats/{key}', [SlotStatController::class, 'get'])
        ->name('user.slots.stats.get');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user/api/extension-tokens', [ExtensionTokenController::class, 'index']);
    Route::post('/user/api/extension-tokens', [ExtensionTokenController::class, 'store']);
    Route::delete('/user/api/extension-tokens/{extensionToken}', [ExtensionTokenController::class, 'destroy']);
});
Route::get('/ping', function () {
    return response()->json([
        'ok' => true,
        'env' => app()->environment(),
        'ts' => now()->toIso8601String(),
    ]);
});

// Optional: Ping mit Token-Prüfung (Bearer via Sanctum)
Route::middleware('auth:sanctum')->get('/ping-auth', function (Request $request) {
    return response()->json([
        'ok' => true,
        'user_id' => $request->user()->id,
        'ts' => now()->toIso8601String(),
    ]);
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
