<?php

namespace App\Http\Controllers;

use App\Jobs\ChangeLocale;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use App\Organization;
use App\Log;
use App\Role;

use Config;
use Illuminate\Support\Facades\Session;

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

    public function setLang($lang) {
        if (array_key_exists($lang, Config::get('app.locales'))) {
            Session::set('locale', $lang);
        }
    }

    public function index() { // 
        //set’s application’s locale
        // app()->setLocale($locale);

        $org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        $logs = Log::where([['user_id',auth()->user()->id],['work_date',date('Y-m-d')],])->get();// get data based on today's date

        //$this->setLang(auth()->user()->lang);
        return view('home',compact('logo','logs'));
    }

    public function profile() {// view profile details
        //set’s application’s locale
        // app()->setLocale($locale);

        $org_id = User::where('email',auth()->user()->email)->get();
    
        $org = Organization::find($org_id[0]->org_id)->get();
        $logo = $org[0]->domain;

        $log = Log::where('user_id',$org_id[0]->id)->get();
        $timeZones = array($org[0]->default_tz);// default time zone
        $timeZones = array_merge($timeZones, unserialize($org[0]->alt_tz)); // merge default_tz & alt_tz
        //dd($timeZones);
        $status = "nil";
        return view('profile.index',compact('logo','log','status','timeZones'));
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

        if($user->role == "admin" && $request->emprole != "admin") {// if user degrades the role from admin to any other lower role
            $org_role_cnt = User::where('role',"admin")->count();// check the no of admins
            if($org_role_cnt <= 1){ // if there is only 1 admin, then reject the request
                $status = "fail";
            }
        }

        if($status == "success"){
            $user->name = $request->empname;
            $user->role = $request->emprole;
            $user->lang = $request->emplang;
            $user->timeZone = $request->emptz;
            $user->update();

            $this->setLang($request->emplang);
        }
        //User::where('id',$request->empid)->update()
        //return view('profile.index',compact('logo','log','status'));
        return redirect()->back()->with('status',$status);
        //return back();
    }

    public function viewEmployees(Request $request) {

        $users = User::where('org_id', auth()->user()->org_id)->get();
        $roles = Role::all();

        return view('employees.view', compact('users','roles'));
    }

    public function changeRoles(Request $request, $user_id) {
        $user = User::where('id',$user_id)->first();
        
        $old_role = Role::where('name',$user->role)->first();
        $new_role = Role::where('name',$request->role)->first();

        //$user->roles()->sync($new_role->id); // id only
        $user->roles()->detach($old_role->id);
        $user->roles()->attach($new_role->id);

        User::where('id',$user_id)->update(['role' => $request->role]);

        return response()->json(['status' => 'Success']);
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
