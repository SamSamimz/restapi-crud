<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Post;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test',function() {
    return response()->json(['post' => "title",]);
});

Route::middleware(['guest'])->group(function() {
    Route::post('/login',[AuthController::class,'login'])->name('login.user');
    Route::post('/register',[AuthController::class,'register'])->name('register.user');
});

Route::middleware(['auth:sanctum'])->group(function() {
    Route::apiResource('/categories',CategoryController::class);
    Route::apiResource('/products',ProductController::class);
    Route::apiResource('/users',UserController::class);
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
});