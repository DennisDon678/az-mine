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
use App\Models\withdrawal;
use Illuminate\Http\Request;

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

    public function users(){
        $users = User::paginate('20');
        return view('admin.pages.users', compact('users'));
    }

    public function delete_user(Request $request){
        $user = User::find($request->id);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function view_user($id){
        $user = User::find($id);
        $package = subscription::where('user_id', $user->id)->first();

        if($package){
            $package = packages::find($package->package_id)->first();
        }
        else{
            $package = (object) [
                'package_name' => 'No active package',
            ];
        }

        return view('admin.pages.user',compact('user','package'));
    }

    public function edit_user(Request $request){
        $user = User::find($request->id);
        if($request->choice == 'credit'){
            $user->balance += $request->amount;
        }else if($request->choice == 'debit'){
            $user->balance -= $request->amount;
        }
        $user->save();

        return response()->json([
            'message' => 'User Updated successfully',
        ]);
    }

    public function wallets(){
        $wallets = crypto::all();

        return view('admin.pages.wallets', compact('wallets'));
    }

    public function create_wallet(Request $request){
        crypto::create($request->except('_token'));

        return response()->json([
            'message' => 'Wallet created successfully',
        ]);
    }

    public function delete_wallet(Request $request){
        $wallet = crypto::find($request->id);
        $wallet->delete();

        return redirect()->back();
    }

    public function packages(Request $request){
        $packages = packages::all();
        return view('admin.pages.packages', compact('packages'));
    }

    public function update_packages(Request $request){
        $package = packages::find($request->id);
        $package->package_price = $request->package_price;
        $package->percentage_profit = $request->percentage_profit;
        $package->number_of_orders_per_day = $request->number_of_orders_per_day;
        $package->daily_profit = $request->daily_profit;
        $package->save();

        return redirect()->back()->with('message',"Package update");
    }

    public function setting(){
        $settings = Settings::first();
        return view('admin.pages.setting',compact('settings'));
    }

    // update settings
    public function process_setting(Request $request){
        $settings = Settings::first();

        $settings->update($request->except('_token'));

        return response()->json([
            'message' => 'Settings updated successfully',
        ]);
    }

    public function profile(){
        return view('admin.pages.profile');
    }

    public function update_profile(Request $request){
        $admin = Admin::first();
        $admin->update($request->except('_token'));

        return response()->json([
           'message' => 'Email updated successfully',
        ]);
    }

    public function reset_password(Request $request){
        $admin = Admin::first();
        $admin->password = password_hash($request->password,PASSWORD_DEFAULT);
        $admin->save();

        return response()->json([
           'message' => 'Password updated successfully',
        ]);
    }

    public function change_password(Request $request){
        $admin = Admin::first();
        if(password_verify($request->old_password, $admin->password)){
            $admin->password = password_hash($request->password, PASSWORD_DEFAULT);
            $admin->save();

            return response()->json([
               'message' => 'Password updated successfully',
            ]);
        }else{
            return response()->json([
               'message' => 'Old password does not match',
            ], 401);
        }
    }

    public function approve_withdrawal(Request $request){
        $withdrawal = withdrawal::find($request->id);
        $transaction = Transactions::where('transaction_id',$withdrawal->withdrawal_id)->first();
        $transaction->status = 'success';
        $transaction->save();

        $withdrawal->delete();

        return redirect()->back()->with('success', 'Withdrawal approved successfully');
    }

    public function reject_withdrawal(Request $request){
        $withdrawal = withdrawal::find($request->id);
        $transaction = Transactions::where('transaction_id',$withdrawal->withdrawal_id)->first();
        $transaction->status ='failed';
        $transaction->save();

        // refund user balance
        $user = User::find($withdrawal->user_id);
        $user->balance += $withdrawal->amount;
        $user->save();

        $withdrawal->delete();

        return redirect()->back()->with('success', 'Withdrawal rejected successfully');
    }

    public function task_config(){
        $users =  UserNegativeBalanceConfig::with('user')->paginate(20);
        return view('admin.pages.task_config',compact('users'));
    }


    public function update_task_config(Request $request){
        $config = UserNegativeBalanceConfig::find($request->id);

        if($config->update($request->except('_token'))){
            return response()->json([
                'message' => 'Task configuration updated successfully',
            ]);
        }else{
            return response()->json([
                'error' => 'Failed to update task configuration',
            ], 400);
        }
    }

    public function rest_user_balance(Request $request){
        $prev = Previous_order_balance::where('user_id','=', $request->id)->first();
        // return response()->json($request->id);

        $user = User::find($request->id);

        $order = 0-(float)$user->order_balance;

        $new_bal = $order + $request->commission + $prev->previous_order_balance;

        $user->order_balance = $new_bal;
        if($user->save()){
            $neg = UserNegativeBalanceConfig::where('user_id', $request->id)->first();
            $neg->negative_balance_amount = 0;
            $neg->task_threshold = 0;
            $neg->save();
            return response()->json([
                'balance' => $new_bal,
               'message' => 'User balance restored successfully',
            ]);
        }else{
            return response()->json([
                'error' => 'Failed to restore user balance',
            ], 400);
        }
    }

}
