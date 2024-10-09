<?php

namespace App\Http\Controllers;

use App\Models\crypto;
use App\Models\Deposit;
use App\Models\packages;
use App\Models\subscription;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::count();
        $balance = User::sum('balance');
        $deposits = Deposit::paginate('20');
        return view('admin.pages.dashboard', compact('users', 'balance', 'deposits'));
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
        $package = packages::find($package->package_id)->first();

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
        $package->save();

        return redirect()->back()->with('message',"Package update");
    }

    public function setting(){
        return view('admin.pages.setting');
    }
}
