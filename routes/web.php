<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;
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

    Route::get('/forgot_password', function(){
        return view('auth.forgot_password');
    });

    Route::post('/register',[AuthController::class, 'register']);
    Route::post('/login',[AuthController::class, 'login']);
    Route::post('logout',[AuthController::class, 'logout']);
});

// User management routes prifx user and middleware auth
Route::middleware(['auth'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class,'index'])->name('user.index');
        Route::get('/profile', [UserDashboardController::class,'profile'])->name('user.profile');
        Route::get('/history', [UserDashboardController::class,'history'])->name('user.history');
    });
});

