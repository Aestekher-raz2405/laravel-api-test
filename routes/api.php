<?php

use App\Http\Controllers\api\v1\PostController as v1PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\PromptGenarationController;
Route::middleware(['auth:sanctum','throttle:api'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::apiResource('posts', v1PostController::class);
        Route::apiResource('prompt-generations', PromptGenarationController::class)->only(['index','store']);
    });
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});





require __DIR__.'/auth.php';
