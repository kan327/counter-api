<?php

use App\Http\Controllers\Api\CounterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/counter/{token}', [CounterController::class, 'index']);
Route::post('/counter/{token}', [CounterController::class, 'store']);
Route::get('/counter/{token}/{id}', [CounterController::class, 'show']);
Route::put('/counter/{token}/{id}', [CounterController::class, 'update']);
Route::delete('/counter/{token}/{id}', [CounterController::class, 'destroy']);
