<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login',  [LoginController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::get('categorys', [CategoryController::class, 'inbox']);
});
