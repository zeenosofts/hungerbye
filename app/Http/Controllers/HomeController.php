<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Chat;
use App\Employee;
use App\Leave;
use App\Schedules;
use App\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //return view('auth.choose_role'); die;
        if(Auth::user()->type == "google"){
            if(Auth::user()->roles()->first() == null){
                return redirect("choose_role");
            }
        }
        if(Auth::user()->status == "0" && Auth::user()->roles()->first()->name == "partner")
        {
            if(empty(Auth::user()->business_name) || empty(Auth::user()->business_address)){
                return redirect("complete_profile");
            }else{
                return redirect("pending_approval");
            }
        }
        if(Auth::user()->status == "0" && Auth::user()->roles()->first()->name != ""){
            return redirect("pending_approval");
        }
        return view('home');
    }

}
