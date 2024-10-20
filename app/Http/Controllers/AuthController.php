<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'unique:users'],
        ]);

        // Generate referral ID
        $referral_id = Str::random(6);

        // Create a new user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' =>  Hash::make($request->password),
            'referral_id' => $referral_id,
            'referred_by' => $request->referred_by,
        ]);


        if ($user) {
            Auth::attempt([
                'email' => $user->email,
                'password' => $request->password,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Registration Completed',
            ], 201);
        } else {
            return response()->json(['message' => 'Error creating user'], 500);
        }
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        // find user by username
        $user =  User::where('username', $username)->first();

        if ($user) {
            if (Auth::attempt([
                'email' => $user->email,
                'password' => $password,
            ])) {
                return response()->json([
                    'message' => 'Successfully logged in',
                ]);
            } else {
                return response()->json(['message' => 'Check Your Password'], 401);
            }
        } else {
            return response()->json(['message' => 'User not found'], 401);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            return response()->json(['message' => 'Logged out successfully']);
        }else{
            return response()->json(['message' => 'User not logged in'], 401);
        }
    }

    public function admin_login(Request $request){
       $auth = Auth::guard('admin')->attempt([
            'email' => $request->username,
            'password' => $request->password
        ]);

        if($auth){
            return response()->json([
               'message' => 'Logged in successfully as admin'
            ],200);
        }else{
            return response()->json([
               'message' => 'Invalid credentials'
            ],401);
        }
    }

    public function admin_logout(Request $request){
        Auth::guard('admin')->logout();
        return response()->json([
           'message' => 'Logged out successfully as admin'
        ],200);
    }
}
