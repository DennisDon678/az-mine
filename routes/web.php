<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::prefix('auth')->group(function () {
    Route::get('/login',function(){
        return view('auth.login');
    })->name('login');

    Route::get('/register',function(){
        $code = rand(1000,9999);
        return view('auth.register',compact('code'));
    })->name('register');
});
