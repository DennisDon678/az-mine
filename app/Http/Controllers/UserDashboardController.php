<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(){
        return view('user.dashboard');
    }

    public function profile(){
        return view('user.profile');
    }

    public function history(){
        return view('user.history');
    }
}