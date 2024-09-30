<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;
use App\Models\Products;
use Illuminate\Support\Facades\Http;
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

// User management routes prefix user and middleware auth
Route::middleware(['auth'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class,'index'])->name('user.index');
        Route::get('/profile', [UserDashboardController::class,'profile'])->name('user.profile');
        Route::get('/history', [UserDashboardController::class,'history'])->name('user.history');
        Route::get('/deposit', [UserDashboardController::class,'deposit_view'])->name('user.deposit');
        Route::post('/deposit', [UserDashboardController::class,'deposit_process'])->name('user.deposit_process');
        Route::get('/earn', [UserDashboardController::class,'earn_view'])->name('user.earn');
        Route::get('/withdraw', [UserDashboardController::class,'withdraw_view'])->name('user.withdraw');

        Route::get('/get_product/{id}',[UserDashboardController::class,'get_product']);
        Route::get('/subscribe', [UserDashboardController::class,'subscribe']);
        Route::get('/claim-order',[UserDashboardController::class,'performTask']);

        Route::get('/terms-and-conditions',[UserDashboardController::class,'terms_and_conditions']);
    });
});

Route::get('products_update/{id}/{page}', function ($id,$page) {

    try {
        $response = Http::withHeaders([
            'x-rapidapi-host' => 'amazon-online-data-api.p.rapidapi.com',
            'x-rapidapi-key' => '13cdb17d7amsh8be3afdeb37f0d8p103f42jsn8d229c3734f6',
        ])
        ->get('https://amazon-online-data-api.p.rapidapi.com/search?geo=US&query=' . $id. 'page='.$page)
        ->json();

        $response = (object) $response;

        foreach ($response->products as $result) {
            $result = (object) $result;

            Products::create([
                'name' => $result->product_title,
                'price' => $result->product_price,
                'image' => $result->product_photo,
                'o_id' => $result->asin,
            ]);
        }

        return response('done');
    } catch (\Throwable $th) {
        return response(['error' => $th->getMessage()]);
    }
});
