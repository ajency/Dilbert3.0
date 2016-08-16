<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use App\Organization;
use App\Log;

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
     * @return \Illuminate\Http\Response
     */
    public function index() { // 
        $org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        $logs = Log::where([['user_id',auth()->user()->id],['work_date',date('Y-m-d')],])->get();// get data based on today's date

        return view('home',compact('logo','logs'));
    }

    public function profile() {// view profile details
        $org_id = User::where('email',auth()->user()->email)->get();
    
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;

        $log = Log::where('user_id',$org_id[0]->id)->get();
        return view('profile.index',compact('logo','log'));
    }

    public function newprof(Request $request) { // update profile
        $this->validate($request, [
            'empemail' => 'required',
            'empname' => 'required ',// | regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'emprole' => 'required'
        ]);
        $user = User::find($request->empid);
        $user->name = $request->empname;
        $user->email = $request->empemail;
        $user->role = $request->emprole;
        $user->update();
        //User::where('id',$request->empid)->update()
        return back();
    }
}
