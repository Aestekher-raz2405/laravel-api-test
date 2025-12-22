<?php

use App\Http\Controllers\api\v1\PostController as v1PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/hello', function () {
    return ['message' => 'Hello, Api!'];
});

Route::prefix('v1')->group(function () {
    Route::apiResource('posts', v1PostController::class);

});



