<?php

namespace App\Http\Controllers;

use App\Models\crypto;
use App\Models\Deposit;
use App\Models\packages;
use App\Models\Products;
use App\Models\subscription;
use App\Models\Transactions;
use App\Models\User;
use App\Models\UserTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserDashboardController extends Controller
{
    public function index()
    {
        $active = subscription::where('user_id', Auth::user()->id)->first();
        $package = packages::all();
        return view('user.dashboard', compact('package', 'active'));
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

    public function deposit_process(Request $request)
    {
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

        if ($deposit->save()) {
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
        return redirect()->back()->with('message', 'Prove submits successfully');
    }

    public function earn_view()
    {
        if (!subscription::where('user_id', Auth::user()->id)->first()) {
            return redirect()->route('user.index');
        } else {
            $package = packages::find(subscription::where('user_id', Auth::user()->id)->first()->package_id);
            $performed = UserTask::firstorcreate(
                [
                    'user_id' => Auth::user()->id
                ],
                [
                    'user_id' => Auth::user()->id,
                    'last_task_completed_at' => now(),
                    'tasks_completed_today' => 0
                ]
            )->tasks_completed_today;
        }
        return view('user.earn', compact('package','performed'));
    }

    public function withdraw_view()
    {
        return view('user.withdraw');
    }

    public function get_product($id)
    {
        // Find product
        $product = Products::find($id);

        // return product as json
        return response()->json($product);
    }

    public function subscribe(Request $request)
    {

        if (isset($request->activate)) {
            $package = packages::find($request->activate);

           if(Auth::user()->balance >= $package->package_price){
                $subscription = subscription::firstorcreate([
                    'user_id' => Auth::user()->id,
                    'package_id' => $package->id,
                ], [
                    'user_id' => Auth::user()->id,
                    'package_id' => $package->id,
                ]);

                $subscription->package_id = $package->id;

                if ($subscription->save()) {
                    return response()->json(1);
                } else {
                    return response()->json(0);
                }
           }else{
            return response()->json(['error'=>"Insufficient Balance"],403);
           }
        }
        $package = packages::find($request->package);

        return view('user.subscribe', compact('package'));
    }

    public function performTask(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $subscription = subscription::where('user_id', $user->id)->first();

        // get package
        $package = packages::find($subscription->package_id);

        $userTask = UserTask::firstOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'last_task_completed_at' => now(),
                'tasks_completed_today' => 0
            ]
        );

        if ($userTask->tasks_completed_today >= $package->number_of_orders_per_day) {
            return response()->json(['error' => 'You have reached the daily task limit.'], 403);
        }
        // Perform the actual task logic here...
        // Get the product
        $product = Products::find($request->package_id);

        // add the percentage profit to the users balance
        $profit = ($package->percentage_profit / 100) * $product->price;

        $user->balance = $user->balance + $profit;
        $user->save();

        $userTask->increment('tasks_completed_today');
        $userTask->update(['last_task_completed_at' => now()]);

        return response()->json(['message' => 'Task performed successfully.', 'taskDone' => $userTask->tasks_completed_today]);
    }

    public function terms_and_conditions()
    {
        return view('user.terms_and_conditions');
    }
}
