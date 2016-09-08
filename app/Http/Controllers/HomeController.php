<?php

namespace App\Http\Controllers;

use App\Jobs\ChangeLocale;

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
        //set’s application’s locale
        // app()->setLocale($locale);

        $org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        $logs = Log::where([['user_id',auth()->user()->id],['work_date',date('Y-m-d')],])->get();// get data based on today's date

        return view('home',compact('logo','logs'));
    }

    public function profile() {// view profile details
        //set’s application’s locale
        // app()->setLocale($locale);

        $org_id = User::where('email',auth()->user()->email)->get();
    
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;

        $log = Log::where('user_id',$org_id[0]->id)->get();
        $status = "nil";
        return view('profile.index',compact('logo','log','status'));
    }

    public function newprof(Request $request) { // update profile
        //dd($request);
        //set’s application’s locale
        // app()->setLocale($locale);

        $this->validate($request, [
            'empname' => 'required ',// | regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'emprole' => 'required'
        ]);

        $status = "success";
        $user = User::find($request->empid);

        if($user->role == $request->emprole) {
            $status = "nil";
        } else if($user->role == "admin" && $request->emprole != "admin") {
            $org_role_cnt = User::where('role',"admin")->count();
            if($org_role_cnt <= 1){
                $status = "fail";
            }
        }

        if($status == "success"){
            $user->name = $request->empname;
            $user->role = $request->emprole;
            $user->update();
        }
        //User::where('id',$request->empid)->update()
        //return view('profile.index',compact('logo','log','status'));
        return redirect()->back()->with('status',$status);
        //return back();
    }

    /**
     * Change language.
     *
     * @param  App\Jobs\ChangeLocaleCommand $changeLocale
     * @param  String $lang
     * @return Response
     */
    public function language( $lang, ChangeLocale $changeLocale) {       
        $lang = in_array($lang, config('app.locales')) ? $lang : config('app.fallback_locale');
        $changeLocale->lang = $lang;
        $this->dispatch($changeLocale);
        return redirect()->back();
    }

}
