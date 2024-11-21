<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;
use App\Models\Admin;
use App\Models\Products;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/admin', function () {
        return view('admin.auth.login');
    })->name('admin.login');

    Route::post('/admin', [AuthController::class, 'admin_login']);

    Route::get('/register', function () {
        $code = rand(1000, 9999);
        return view('auth.register', compact('code'));
    })->name('register');

    Route::get('/forgot_password', function () {
        return view('auth.forgot_password');
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// User management routes prefix user and middleware auth
Route::middleware(['auth'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.index');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
        Route::get('/history', [UserDashboardController::class, 'history'])->name('user.history');
        Route::get('/deposit', [UserDashboardController::class, 'deposit_view'])->name('user.deposit');
        Route::post('/deposit', [UserDashboardController::class, 'deposit_process'])->name('user.deposit_process');
        Route::get('/earn', [UserDashboardController::class, 'earn_view'])->name('user.earn');
        Route::get('/withdraw', [UserDashboardController::class, 'withdraw_view'])->name('user.withdraw');

        Route::get('/get_product/{id}', [UserDashboardController::class, 'get_product']);
        Route::get('/subscribe', [UserDashboardController::class, 'subscribe']);
        Route::get('/claim-order', [UserDashboardController::class, 'performTask']);

        Route::post('/withdrawal/submit', [UserDashboardController::class, 'create_withdraw']);

        Route::get('/terms-and-conditions', [UserDashboardController::class, 'terms_and_conditions']);

        Route::get('/transfer', [UserDashboardController::class, 'transfer']);
        Route::post('/transfer', [UserDashboardController::class, 'process_transfer']);
        Route::get('task-check', [UserDashboardController::class, 'check_task']);
        Route::get('contact', [UserDashboardController::class, 'contact']);
        Route::post('/update-transaction-pin', [UserDashboardController::class, 'update_transaction']);
        Route::get('/bind-wallet', [UserDashboardController::class, 'bind_wallet']);
        Route::get('/check-withdraw-wallet', [UserDashboardController::class, 'check_withdraw_wallet']);
        Route::post('/bind-wallet', [UserDashboardController::class, 'update_wallet']);
        Route::get('/orders', [UserDashboardController::class, 'orders']);
        Route::get('/check-pending-task', [UserDashboardController::class, 'check_pending_task']);

        Route::get('/submit-pending-task', [UserDashboardController::class, 'submit_pending_task']);
        Route::get('/lucky', [UserDashboardController::class, 'lucky']);
        Route::post('/update-profile-picture', [UserDashboardController::class, 'update_profile_picture']);
    });
});
Route::get('rest-user-tasks', [UserDashboardController::class, 'reset_user_tasks']);



// Admin ROUTES
Route::get('/admin', function () {
    if (Auth::guard('admin')->check()) {
        return redirect(route('admin.dashboard'));
    } else {
        return redirect(route('admin.login'));
    }
});

Route::get('/create-admin', function () {
    Admin::create([
        'email' => 'admin@example.com',
        'password' => password_hash('123456789', PASSWORD_DEFAULT),
    ]);

    return 'done';
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/add_product', [AdminController::class, 'add_product_view'])->name('admin.add_product_view');
    Route::post('/add_product', [AdminController::class, 'add_product_process'])->name('admin.add_product_process');
    Route::get('/edit_product/{id}', [AdminController::class, 'edit_product_view'])->name('admin.edit_product_view');

    Route::get('/approve-deposit', [AdminController::class, 'approve_deposit']);
    Route::get('/reject-deposit', [AdminController::class, 'reject_deposit']);
    Route::get('/delete-user', [AdminController::class, 'delete_user']);
    Route::get('/user/{id}', [AdminController::class, 'view_user']);
    Route::get('/user/{id}/reset-password', [AdminController::class, 'reset_user_password']);
    Route::get('/generate-new-password', [AdminController::class, 'generate_new_password']);
    Route::get('/edit-user', [AdminController::class, 'edit_user']);
    Route::get('/wallets', [AdminController::class, 'wallets']);
    Route::post('/wallet/create', [AdminController::class, 'create_wallet']);
    Route::get('/delete-wallet', [AdminController::class, 'delete_wallet']);
    Route::get('/packages', [AdminController::class, 'packages']);
    Route::post('/packages', [AdminController::class, 'update_packages']);
    Route::get('/setting', [AdminController::class, 'setting']);
    Route::post('/setting', [AdminController::class, 'process_setting']);

    Route::get('/profile', [AdminController::class, 'profile']);
    Route::post('/profile/update', [AdminController::class, 'update_profile']);
    Route::post('/profile/reset', [AdminController::class, 'reset_password']);
    Route::post('/profile/change', [AdminController::class, 'change_password']);

    Route::get('/approve-withdrawal', [AdminController::class, 'approve_withdrawal']);
    Route::get('/reject-withdrawal', [AdminController::class, 'reject_withdrawal']);

    Route::get('/task-config', [AdminController::class, 'task_config']);
    Route::post('/task-config', [AdminController::class, 'update_task_config']);

    Route::post('rest-user-balance', [AdminController::class, 'rest_user_balance']);
    Route::get('/activate-next-set', [AdminController::class, 'activate_next_set']);

    Route::get('task-setting', [AdminController::class, 'task_setting']);
    Route::get('referral-config', [AdminController::class, 'referral_config']);
    Route::post('referral-config', [AdminController::class, 'update_referral_config']);
    Route::get('/reset-user-task',[AdminController::class, 'reset_user_task']);
    Route::get('/logout', [AdminController::class, 'logout']);

    Route::post('system-time', [AdminController::class, 'system_time']);
});


Route::get('products_update/{id}/{page}', function ($id, $page) {

    try {
        $response = Http::withHeaders([
            'x-rapidapi-host' => 'amazon-online-data-api.p.rapidapi.com',
            'x-rapidapi-key' => '13cdb17d7amsh8be3afdeb37f0d8p103f42jsn8d229c3734f6',
        ])
            ->get('https://amazon-online-data-api.p.rapidapi.com/search?geo=US&query=' . $id . 'page=' . $page)
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

Route::get('/timezone',[AdminController::class,'timezone']);

Route::get('/artisan', function () {
    Artisan::call('migrate');
});
