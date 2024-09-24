<?php

namespace App\Http\Controllers;

use App\Models\crypto;
use App\Models\Deposit;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserDashboardController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function history()
    {
        return view('user.history');
    }

    public function deposit_view(Request $request)
    {
        $amount = null;
        if (isset($request->amount)) {
            $wallet = crypto::find($request->choice1);
            $amount = $request->amount; 
            return view('user.deposit', compact('wallet', 'amount'));
        } else {
            return view('user.deposit', compact('amount'));
        }
    }

    public function deposit_process(Request $request){
        // process the image save to storage and get the link
        $path = $request->file('prove')->store('proofs');
        // save the deposit details to the database
        $ref = Str::random(12);
        $deposit = new Deposit();
        $deposit->user_id = Auth::user()->id;
        $deposit->coin = $request->choice1;
        $deposit->deposit_id = $ref;
        $deposit->amount = $request->amount;
        $deposit->proof = $path;

        if ($deposit->save()){
            // create a new transaction
            $transaction = new Transactions();
            $transaction->user_id = Auth::user()->id;
            $transaction->type = 'deposit';
            $transaction->transaction_id = $ref;
            $transaction->status = 'pending';
            $transaction->amount = $request->amount;
            $transaction->save();
            // send a notification to admin
        }
        // redirect to the success page
        return redirect()->back()->with('message','Prove submits successfully');
    }

    public function earn_view(){
        return view('user.earn');
    }

    public function withdraw_view(){
        return view('user.withdraw');
    }
}
