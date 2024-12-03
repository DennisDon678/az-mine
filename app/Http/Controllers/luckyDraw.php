<?php

namespace App\Http\Controllers;

use App\Models\DailyDraw;
use App\Models\luckyItem;
use App\Models\subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class luckyDraw extends Controller
{
    public function performDraw(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        // check subscription
        $sub = subscription::where('user_id', $user->id)->first();

        if(!$sub){
            return response()->json([
                'message' => 'Subscribe to one of our packages to participate in lucky draws.'
            ]);
        }

        // check if pending
        $pending = DailyDraw::where('user_id', $user->id)->where('claimed',false)->first();
        if($pending){
            return response()->json([
                'message' => 'Claim all your winning draws to participate again.'
            ]); 
        }

        if($user->balance < 0){
            return response()->json(['message' => 'Clear All negative balance to qualify for a draw.'], 403);
        }

        $alreadyDrawn = DailyDraw::where('user_id', $user->id)
            ->whereDate('draw_date', $today)
            ->exists();

        if ($alreadyDrawn) {
            return response()->json(['message' => 'You have already performed today’s draw.'], 403);
        }

        // Perform the draw logic
        $reward = $this->getReward(); // Function to determine the prize
        DailyDraw::create([
            'user_id' => $user->id,
            'reward' => $reward->amount,
            'type' => $reward->type,
            'draw_date' => $today,
        ]);

        return response()->json(['message' => 'Draw successful!', 'reward' => $reward]);
    }

    private function getReward()
    {
        $rewards = luckyItem::inRandomOrder()->first(); // Example prizes
        return ($rewards);
    }

    public function claim_draw()
    {
        $user = User::find(auth()->user()->id);
        $today = Carbon::today();

        $draw = DailyDraw::where('user_id', $user->id)
            ->whereDate('draw_date', $today)
            ->first();

        if ($draw) {
            // Perform the claim logic
            $user->balance = $user->balance + $draw->reward;
            $user->save();
            $draw->claimed = true;
            $draw->save();
            return response()->json(['message' => 'Draw claimed successfully.']);
        } else {
            return response()->json(['message' => 'No draw available for claiming.'], 404);
        }
    }

    public function claim_unclaimed(Request $request)
    {
        $draw = DailyDraw::find($request->id);
        $user = User::find($draw->user_id);

        $user->balance = $user->balance + $draw->reward;
        $user->save();
        $draw->claimed = true;
        $draw->save();
        return response()->json(['message' => 'Draw claimed successfully and added to your balance.']);
    }
}
