<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.auth.login');
});

//Auth::routes(['verify' => true]);
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home',function(){
        return view('pages.home');
    })->name('home');

    Route::resources([
        'categorys' => CategoryController::class,
        'products'  => ProductController::class,
        'users'     => UserController::class,
    ]);
});
