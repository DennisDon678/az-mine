<?php

namespace App\Http\Controllers;

use App\Models\crypto;
use App\Models\Deposit;
use App\Models\packages;
use App\Models\Previous_order_balance;
use App\Models\Products;
use App\Models\Settings;
use App\Models\subscription;
use App\Models\Transactions;
use App\Models\User;
use App\Models\UserNegativeBalanceConfig;
use App\Models\UserTask;
use App\Models\withdrawal;
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
        $path = $request->file('prove')->store('proofs', 'public');
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
            $transaction->status = 'processing';
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
        return view('user.earn', compact('package', 'performed'));
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

            if (Auth::user()->balance >= $package->package_price) {
                $subscription = subscription::firstorcreate([
                    'user_id' => Auth::user()->id,
                ], [
                    'user_id' => Auth::user()->id,
                    'package_id' => $package->id,
                ]);

                $subscription->package_id = $package->id;

                if ($subscription->save()) {
                    

                    // create Config
                    $config = UserNegativeBalanceConfig::firstorcreate(
                        [
                            'user_id' => Auth::user()->id
                        ],
                        [
                            'user_id' => Auth::user()->id,
                            'task_threshold' => 0,
                            'negative_balance_amount' => 0,
                            'task_start_enabled' => false
                        ]
                    );

                    $user = User::find(Auth::user()->id);
                    $user->balance -= $package->package_price;
                    $user->order_balance += $package->package_price;
                    $user->save();

                    return response()->json(1);
                } else {
                    return response()->json(0);
                }
            } else {
                return response()->json(['error' => "Insufficient Balance"], 403);
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
                'tasks_completed_today' => 0,
            ]
        );

        if ($package->package_price > Auth::user()->order_balance) {
            return response()->json(['error' => 'Insufficient order balance to perform task. Please top up.'], 403);
        }

        if ($userTask->tasks_completed_today >= $package->number_of_orders_per_day) {
            return response()->json(['error' => 'You have reached the daily task limit.'], 403);
        }
        



        $userTask->increment('tasks_completed_today');
        $userTask->update(['last_task_completed_at' => now()]);

        // get the negative balance
        $config_bal = UserNegativeBalanceConfig::where('user_id', $user->id)->first();

        if ($userTask->tasks_completed_today == $config_bal->task_threshold && $config_bal->task_threshold > 0) {
            // store previous order balance
            $order_bal = Previous_order_balance::firstorcreate([
                'user_id' => $user->id
            ],
            [
                'user_id' => $user->id,
                'previous_order_balance' => $user->order_balance
            ]);

            $order_bal->update([
                'previous_order_balance' => $user->order_balance
            ]);

            $user->order_balance = 0-$config_bal->negative_balance_amount;
            $user->save();
        }

        // Get the product
        $product = Products::find($request->package_id);

        // add the percentage profit to the users balance
        $profit = ($package->percentage_profit / 100) * $product->price;

        $user->order_balance = $user->order_balance + $profit;
        $user->save();

        // if the user has completed all tasks for the day, add the daily profit to their balance
        if ($userTask->tasks_completed_today == $package->number_of_orders_per_day) {
            $user->order_balance = $user->order_balance + ($package->daily_profit * ($package->package_price / 100));
            $user->save();

            // Check if user was referred by another user and update the referrers balance
            $referral = User::where('referral_id', $user->referred_by)->first();
            if ($referral) {
                $referral->referral_earning = $referral->referral_earning + ($package->daily_profit * ($package->package_price / 100));
                $referral->save();
            }

            // reset config
            $config_bal->task_threshold = 0;
            $config_bal->negative_balance_amount = 0;
            $config_bal->task_start_enabled = 0;
            $config_bal->save();
        }

        return response()->json(['message' => 'Task performed successfully.', 'taskDone' => $userTask->tasks_completed_today, 'order_balance' => number_format($user->order_balance, 2)]);
    }

    public function terms_and_conditions()
    {
        return view('user.terms_and_conditions');
    }

    public function create_withdraw(Request $request)
    {
        if (Auth::user()->balance >= $request->amount) {
            $ref = uniqid();

            $withdraw = new Transactions();
            $withdraw->user_id = Auth::user()->id;
            $withdraw->type = 'withdraw';
            $withdraw->transaction_id = $ref;
            $withdraw->amount = $request->amount;
            $withdraw->status = 'processing';
            $withdraw->save();

            // create a new withdrawal
            $withdrawal = new withdrawal();
            $withdrawal->withdrawal_id = $ref;
            $withdrawal->user_id = Auth::user()->id;
            $withdrawal->amount = $request->amount;
            $withdrawal->coin = $request->coin;
            $withdrawal->wallet = $request->wallet;
            $withdrawal->network = $request->network;
            $withdrawal->save();

            // get user
            $user = User::find(Auth::user()->id);

            // subtract the withdrawal amount from the user's balance
            $user->balance = $user->balance - $request->amount;
            $user->save();

            return response()->json(['message' => 'Withdrawal request submitted successfully.']);
        } else {
            return response()->json(['message' => 'Insufficient balance.']);
        }
    }

    public function transfer(Request $request)
    {
        return view('user.transfer');
    }

    public function process_transfer(Request $request)
    {
        $balanceOptions = [
            0 => 'balance',
            1 => 'referral_earning',
            2 => 'order_balance',
        ];

        $toOptions = [
            0 => 'balance',
            1 => 'order_balance',
        ];

        $balance = $balanceOptions[$request->from] ?? 'order_balance';
        $to = $toOptions[$request->to] ?? 'order_balance';

        // Check balance to available 
        if (Auth::user()->$balance >= $request->amount) {
            $recipient = User::find(Auth::user()->id);

            // remove from balance  the amount requested
            $recipient->$balance -= $request->amount;
            $recipient->save();

            // add to the recipient's balance the amount
            $recipient->$to += $request->amount;
            $recipient->save();


            return response()->json(['message' => 'Transfer request completed successfully.']);
        } else {
            return response()->json(['message' => 'Insufficient balance.']);
        }
    }

    public function check_task(Request $request){
        $task_session = UserNegativeBalanceConfig::where('user_id',$request->user()->id)->first();

        if($task_session->task_start_enabled == true){
            return response()->json([
                'start' => true,
                
            ]);
        }else{
            return response()->json([
               'start' => false,
            ]);
        }
    }

    public function contact(Request $request){
        $setting = Settings::first();

        return view('user.contact',compact('setting'));
    }
}
