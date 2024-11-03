<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\crypto;
use App\Models\Deposit;
use App\Models\packages;
use App\Models\Previous_order_balance;
use App\Models\Settings;
use App\Models\subscription;
use App\Models\Transactions;
use App\Models\User;
use App\Models\UserNegativeBalanceConfig;
use App\Models\UserTask;
use App\Models\withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::count();
        $balance = User::sum('balance');
        $deposits = Deposit::paginate('20');
        $withdrawals = withdrawal::paginate('20');
        return view('admin.pages.dashboard', compact('users', 'balance', 'deposits', 'withdrawals'));
    }

    public function approve_deposit(Request $request)
    {
        $deposit = Deposit::find($request->id);
        $user = User::find($deposit->user_id);
        $user->balance += $deposit->amount;
        $user->save();

        // Get transaction
        $transaction = Transactions::where('transaction_id', $deposit->deposit_id)->first();
        $transaction->status = 'success';
        $transaction->save();

        // Delete deposit image
        $path = storage_path('app/public/' . $deposit->proof);
        unlink($path);
        // Delete deposit record
        $deposit->delete();

        return redirect()->back()->with('success', 'Deposit approved successfully');
    }

    public function reject_deposit(Request $request)
    {
        $deposit = Deposit::find($request->id);
        $transaction = Transactions::where('transaction_id', $deposit->deposit_id)->first();
        $transaction->status = 'failed';
        $transaction->save();
        // Delete deposit image
        $path = storage_path('app/public/' . $deposit->proof);
        unlink($path);
        // Delete deposit record
        $deposit->delete();
        return redirect()->back()->with('success', 'Deposit rejected successfully');
    }

    public function users()
    {
        $users = User::paginate('20');
        return view('admin.pages.users', compact('users'));
    }

    public function delete_user(Request $request)
    {
        $user = User::find($request->id);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function view_user($id)
    {
        $user = User::find($id);
        $package = subscription::where('user_id', $user->id)->first();

        if ($package) {
            $package = packages::find($package->package_id)->first();
        } else {
            $package = (object) [
                'package_name' => 'No active package',
            ];
        }

        return view('admin.pages.user', compact('user', 'package'));
    }

    public function edit_user(Request $request)
    {
        $user = User::find($request->id);
        if ($request->choice == 'credit') {
            $user->balance += $request->amount;
        } else if ($request->choice == 'debit') {
            $user->balance -= $request->amount;
        }
        $user->save();

        return response()->json([
            'message' => 'User Updated successfully',
        ]);
    }

    public function wallets()
    {
        $wallets = crypto::all();

        return view('admin.pages.wallets', compact('wallets'));
    }

    public function create_wallet(Request $request)
    {
        crypto::create($request->except('_token'));

        return response()->json([
            'message' => 'Wallet created successfully',
        ]);
    }

    public function delete_wallet(Request $request)
    {
        $wallet = crypto::find($request->id);
        $wallet->delete();

        return redirect()->back();
    }

    public function packages(Request $request)
    {
        $packages = packages::all();
        return view('admin.pages.packages', compact('packages'));
    }

    public function update_packages(Request $request)
    {
        $package = packages::find($request->id);
        $package->package_price = $request->package_price;
        $package->percentage_profit = $request->percentage_profit;
        $package->number_of_orders_per_day = $request->number_of_orders_per_day;
        $package->daily_profit = $request->daily_profit;
        $package->set = $request->set;
        $package->save();

        return redirect()->back()->with('message', "Package update");
    }

    public function setting()
    {
        $settings = Settings::first();
        return view('admin.pages.setting', compact('settings'));
    }

    // update settings
    public function process_setting(Request $request)
    {
        $settings = Settings::first();

        $settings->update($request->except('_token'));

        return response()->json([
            'message' => 'Settings updated successfully',
        ]);
    }

    public function profile()
    {
        return view('admin.pages.profile');
    }

    public function update_profile(Request $request)
    {
        $admin = Admin::first();
        $admin->update($request->except('_token'));

        return response()->json([
            'message' => 'Email updated successfully',
        ]);
    }

    public function reset_password(Request $request)
    {
        $admin = Admin::first();
        $admin->password = password_hash($request->password, PASSWORD_DEFAULT);
        $admin->save();

        return response()->json([
            'message' => 'Password updated successfully',
        ]);
    }

    public function change_password(Request $request)
    {
        $admin = Admin::first();
        if (password_verify($request->old_password, $admin->password)) {
            $admin->password = password_hash($request->password, PASSWORD_DEFAULT);
            $admin->save();

            return response()->json([
                'message' => 'Password updated successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Old password does not match',
            ], 401);
        }
    }

    public function approve_withdrawal(Request $request)
    {
        $withdrawal = withdrawal::find($request->id);
        $transaction = Transactions::where('transaction_id', $withdrawal->withdrawal_id)->first();
        $transaction->status = 'success';
        $transaction->save();

        $withdrawal->delete();

        return redirect()->back()->with('success', 'Withdrawal approved successfully');
    }

    public function reject_withdrawal(Request $request)
    {
        $withdrawal = withdrawal::find($request->id);
        $transaction = Transactions::where('transaction_id', $withdrawal->withdrawal_id)->first();
        $transaction->status = 'failed';
        $transaction->save();

        // refund user balance
        $user = User::find($withdrawal->user_id);
        $user->balance += $withdrawal->amount;
        $user->save();

        $withdrawal->delete();

        return redirect()->back()->with('success', 'Withdrawal rejected successfully');
    }

    public function task_config()
    {
        $users =  subscription::paginate(20);
        return view('admin.pages.conf', compact('users'));
    }

    public function task_setting(Request $request)
    {
        $negative = UserNegativeBalanceConfig::where('user_id', $request->user)->first();
        $subscription = subscription::where('user_id', $request->user)->first();
        $package = packages::where('id', $subscription->package_id)->first();
        $userTask = UserTask::where('user_id', $request->user)->first();

        return view('admin.pages.task_config', compact('negative', 'package', 'userTask'));
    }


    public function update_task_config(Request $request)
    {
        $config = UserNegativeBalanceConfig::find($request->id); 

        if ($config->update($request->except('_token'))) {
            $userTask = UserTask::where('user_id', $config->user_id)->first();
            $userTask->save();
            return redirect()->back()->with(
                'message',
                'Task configuration updated successfully'
            );
        } else {
            return redirect()->back()->with(
                'message',
                'Something went wrong'
            );
        }
    }

    public function rest_user_balance(Request $request)
    {
        $prev = Previous_order_balance::where('user_id', '=', $request->user)->first();
        // return response()->json($request->id);

        $user = User::find($request->user);

        $order = 0 - (float)$user->balance;

        $new_bal = $order + $request->commission + $prev->previous_order_balance;

        $user->balance = $new_bal;
        if ($user->save()) {
            $neg = UserNegativeBalanceConfig::where('user_id', $request->user)->first();
            $neg->negative_balance_amount = 0;
            $neg->task_threshold = 0;
            $neg->save();
            return redirect()->back()->with(
                'message', 'User balance restored successfully'
            );
        } else {
            return redirect()->back()->with(
                'error','Failed to restore user balance');
        }
    }

    public function activate_next_set(Request $request)
    {
        $config = UserNegativeBalanceConfig::where('user_id',$request->id)->first();
        $userTask = UserTask::where('user_id', $config->user_id)->first();

        // find package
        $subscription = subscription::where('user_id', $config->user_id)->first();
        $package = packages::find($subscription->package_id);

        if ($userTask->current_set < $package->set) {
            $userTask->current_set += 1;
            $userTask->tasks_completed_today = 0;
            $userTask->save();
        }

        return response()->json([
            'current_set' => $userTask->current_set,
            'message' => 'Next set activated successfully',
        ]);
    }

    public function reset_user_password($user){
        $user = User::find($user);

        return view('admin.pages.reset_user_password',compact('user'));
    }

    public function generate_new_password(Request $request){
        $user = User::find($request->user);

        $new_password = Str::random(10);
        $user->password = password_hash($new_password, PASSWORD_DEFAULT);
        if($user->save()){
            return response()->json([
                'new_password' =>$new_password,
                'message' => 'Password changed successfully'
            ]);
        }else{
            return response()->json([
                'message' => 'Something went wrong'
            ],503);
        }
    }

    public function logout(){
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        }else{
            return redirect()->route('admin.login');
        }
    }
}
