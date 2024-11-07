<?php

namespace App\Http\Controllers;

use App\Models\crypto;
use App\Models\Deposit;
use App\Models\packages;
use App\Models\Previous_order_balance;
use App\Models\Products;
use App\Models\ReferralSetting;
use App\Models\Settings;
use App\Models\subscription;
use App\Models\TaskLog;
use App\Models\Transactions;
use App\Models\User;
use App\Models\UserNegativeBalanceConfig;
use App\Models\UserTask;
use App\Models\withdrawal;
use App\Models\withdrawal_info;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            );
        }
        return view('user.earn', compact('package', 'performed'));
    }

    public function withdraw_view()
    {
        $info = withdrawal_info::where('user_id', Auth::user()->id)->first();
        return view('user.withdraw', compact('info'));
    }

    public function get_product($id)
    {
        $user = User::find(auth()->user()->id);
        $subscription = subscription::where('user_id', $user->id)->first();

        // get package
        $package = packages::find($subscription->package_id);

        $userTask = UserTask::firstOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'current_set' => $package->set,
                'last_task_completed_at' => now(),
                'tasks_completed_today' => 0,
            ]
        );
        if ($userTask->current_set == $package->set && $userTask->tasks_completed_today == $package->number_of_orders_per_day) {
            return response()->json([
                'error' => 'You have Completed All Set For Today.'
            ], 403);
        }

        if ($userTask->tasks_completed_today >= $package->number_of_orders_per_day) {
            return response()->json(['error' => 'You have reached the daily task limit.'], 403);
        }

        $config_bal = UserNegativeBalanceConfig::where('user_id', $user->id)->first();

        // Find product
        if ($userTask->tasks_completed_today + 1 == $config_bal->task_threshold && $config_bal->task_threshold > 0) {
            $amount = $config_bal->negative_balance_amount;
            $product = DB::select('SELECT * FROM products WHERE price >= 500 ORDER BY RAND() LIMIT 1');
            $product = (object)$product[0];
            $product = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $amount,
                'image' => $product->image,
            ];
            
        } else {
            $product = DB::select('SELECT * FROM products WHERE price >= 100 ORDER BY RAND() LIMIT 1');
        }
        if($userTask->tasks_completed_today + 1 != $config_bal->task_threshold && $config_bal->task_threshold != 0){
            $product = (object)$product[0];
        }else{
            $product = (object) $product;
        }

        // new Log;
        $log = TaskLog::create([
            'user_id' => $user->id,
            'order_id' => uniqid(),
            'product_id' => $product->id,
            'amount_earned' => ($config_bal->percentage / 100) * ($product->price),
            'product_amount' => $product->price,
            'completed' => false,
        ]);
        // return product as json
        return response()->json([
            'product'=> $product,
            'order_id' => $log->order_id,
        ]);
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
                    // set all to default values
                    $config->task_threshold = 0;
                    $config->negative_balance_amount = 0;
                    $config->task_start_enabled = false;
                    $config->save();

                    $userTask = UserTask::firstOrCreate(
                        ['user_id' => Auth::user()->id],
                        [
                            'user_id' => Auth::user()->id,
                            'current_set' => $package->set,
                            'last_task_completed_at' => now(),
                            'tasks_completed_today' => 0,
                        ]
                    );

                    $userTask->current_set = $package->set;
                    $userTask->tasks_completed_today = 0;
                    $userTask->save();

                    // $user = User::find(Auth::user()->id);
                    // $user->balance -= $package->package_price;
                    // $user->order_balance += $package->package_price;
                    // $user->save();

                    return response()->json(1);
                } else {
                    return response()->json(0);
                }
            } else {
                return response()->json(3);
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
                'current_set' => $package->set,
                'last_task_completed_at' => now(),
                'tasks_completed_today' => 0,
            ]
        );

        if ($userTask->current_set == $package->set && $userTask->tasks_completed_today == $package->number_of_orders_per_day) {
            return response()->json([
                'error' => 'You have Completed All Set For Today.'
            ], 403);
        }

        if ($package->package_price > Auth::user()->balance) {
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
            $order_bal = Previous_order_balance::firstorcreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'user_id' => $user->id,
                    'previous_order_balance' => $user->balance
                ]
            );

            $order_bal->update([
                'previous_order_balance' => $user->balance
            ]);

            $user->balance = 0 - $config_bal->negative_balance_amount;
            $user->save();

            return response()->json([
                'message' => 'OOPS! Balance not enough to process this order.', 
                'taskDone' => $userTask->tasks_completed_today, 
                'order_balance' => number_format($user->balance, 2)
            ]);
        }
        $referral = User::where('referral_id', $user->referred_by)->first();
        // Get the product
        $product = Products::find($request->package_id);

        // add the percentage profit to the users balance
        $profit = ($package->percentage_profit / 100) * $product->price;

        $user->balance = $user->balance + $profit;
        $user->save();
        if($referral){
            $referral_config = ReferralSetting::first();
            $referral->referral_earning = $referral->referral_earning + ((($package->percentage_profit / 100) * $product->price) * $referral_config->percentage / 100);
            $referral->save();
        }

        // if the user has completed all tasks for the day, add the daily profit to their balance
        if ($userTask->current_set <= $package->set) {
            if ($userTask->tasks_completed_today == $package->number_of_orders_per_day) {

                if ($userTask->current_set == $package->set) {
                    $user->order_balance = $user->order_balance + ($package->daily_profit * ($package->package_price / 100));
                    $user->save();

                    // Check if user was referred by another user and update the referrers balance
                    if ($referral) {
                        $referral_config = ReferralSetting::first();
                        $referral->referral_earning = $referral->referral_earning + (($package->daily_profit * ($package->package_price / 100))*$referral_config->percentage/100);
                        $referral->save();
                    }
                }

                // reset config
                $config_bal->task_threshold = 0;
                $config_bal->negative_balance_amount = 0;
                // $config_bal->task_start_enabled = 0;
                $config_bal->save();
            }
        }

        // get order id
        $log = TaskLog::where('order_id',$request->order_id)->first();
        $log->completed = true;
        $log->save();

        return response()->json(['message' => 'Task performed successfully.', 'taskDone' => $userTask->tasks_completed_today, 'order_balance' => number_format($user->balance, 2)]);
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

            $info = withdrawal_info::where('user_id', Auth::user()->id)->first();
            // create a new withdrawal
            $withdrawal = new withdrawal();
            $withdrawal->withdrawal_id = $ref;
            $withdrawal->user_id = Auth::user()->id;
            $withdrawal->amount = $request->amount;
            $withdrawal->coin = "USDT";
            $withdrawal->wallet = $info->wallet;
            $withdrawal->network = "TRC20";
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

    public function check_task(Request $request)
    {
        $task_session = UserNegativeBalanceConfig::where('user_id', $request->user()->id)->first();

        if ($task_session->task_start_enabled == true) {
            return response()->json([
                'start' => true,
            ]);
        } else {
            return response()->json([
                'start' => false,
            ]);
        }
    }

    public function check_pending_task(){
        // check pending task log
        $task = TaskLog::where('user_id', '=', Auth::user()->id)->where('completed',false)->count();

        if($task > 0){
            return response()->json([
                'pending' => true,
            ]);
        }else{
            return response()->json([
                'pending' => false,
            ]);
        }
    }

    public function contact(Request $request)
    {
        $setting = Settings::first();

        return view('user.contact', compact('setting'));
    }

    public function update_transaction(Request $request)
    {
        // get user
        $user = User::find($request->user()->id);
        $user->pin = $request->pin;

        if ($user->save()) {
            return response()->json([
                'message' => 'PIN was successfully updated'
            ]);
        } else {
            return response()->json([
                'message' => 'Something went wrong'
            ], 501);
        }
    }

    public function bind_wallet()
    {
        if (!Auth::user()->pin) {
            return redirect('/user/profile')->with('error', 'You must set your Transaction PIN first.');
        }
        $wallet = withdrawal_info::where('user_id', Auth::user()->id)->first()->wallet;
        if($wallet){
            $wallet = $wallet;
        }else{
            $wallet = "";
        }
        return view('user.bind_wallet',compact('wallet'));
    }

    public function check_withdraw_wallet(Request $request)
    {
        $wallet = withdrawal_info::where('user_id', $request->user()->id)->first();

        if (!$wallet) {
            return response()->json([
                'wallet_exists' => false,
            ]);
        } else {
            return response()->json([
                'wallet_exists' => true,
            ]);
        }
    }

    public function update_wallet(Request $request)
    {
        $wallet = withdrawal_info::firstorcreate([
            'user_id' => Auth::user()->id
        ], [
            'user_id' => Auth::user()->id,
            'wallet' => $request->wallet
        ]);

        $wallet->wallet = $request->wallet;

        if ($wallet->save()) {
            return response()->json([
                'message' => 'Wallet was successfully updated'
            ]);
        } else {
            return response()->json([
                'message' => 'Something went wrong'
            ], 501);
        }
    }

    public function orders()
    {
        return view('user.orders');
    }

    public function reset_user_tasks(){
        $userTasks = UserTask::get();
        
        foreach($userTasks as $task){
            $task_session = UserNegativeBalanceConfig::where('user_id', $task->user_id)->first();
            $task_session->task_start_enabled = false;
            $task_session->save();
            $task->tasks_completed_today = 0;
            $task->current_set = 1;
            $task->save();
        }

        return response()->json([
           'message' => 'Tasks reset successfully'
        ]);
    }

    public function submit_pending_task(Request $request){
        $task = TaskLog::where('order_id', $request->order_id)->where('completed',false)->first();
        $user = User::find($request->user()->id);
        $subscription = subscription::where('user_id', $user->id)->first();
        $package = packages::find($subscription->package_id);
        $userTask = UserTask::where('user_id', $user->id)->first();;
        
        // check balance
        if($user->balance <= $package->package_price){
            return response()->json([
                'error' => "OOPS! Balance not enough to process this order."
            ],403);
        }
        if($task){
            $task->completed = true;
            $task->save();

            $userTask->tasks_completed_today += 1;
            $userTask->last_task_completed_at = now();
            $userTask->save();

            // Add profit t0 User Account
            $user->balance += $task->amount_earned;
            $user->save();
        }

        return response()->json([
           'message' => 'Task Submitted successfully'
        ]);
    }
}
