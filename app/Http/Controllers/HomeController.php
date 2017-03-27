<?php

namespace App\Http\Controllers;

use App\Jobs\ChangeLocale;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use App\Organization;
use App\Log;
use App\Role;
use App\Permission;

use Config;
use Illuminate\Support\Facades\Session;

use Symfony\Component\Console\Output\ConsoleOutput;

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

    public function index() { // load default home/dashboard page
        //set’s application’s locale
        // app()->setLocale($locale);

        $org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        $logs = Log::where([['user_id',auth()->user()->id],['work_date',date('Y-m-d')],])->get();// get data based on today's date

        //$this->setLang(auth()->user()->lang);
        //return view('home',compact('logo','logs'));
        if(session()->has("reload")) {
            return redirect('/dashboard')->with('reload', "true");
        } else {
            return redirect('/dashboard');
        }
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
            $user->dob = $request->empdob;
            $user->update();

            $this->setLang($request->emplang);
        }
        //User::where('id',$request->empid)->update()
        //return view('profile.index',compact('logo','log','status'));
        return redirect()->back()->with('status',$status);
        //return back();
    }

    public function viewEmployees(Request $request) { /* Display all the employees under that organization */

        $users = User::where('org_id', auth()->user()->org_id)->orderBy('name', 'asc')->get();
        $logo = Organization::find(auth()->user()->org_id)->get();
        $logo = $logo[0]->domain;
        $orgLogo = Organization::where('id', auth()->user()->org_id)->first();
        $roles = Role::all();

        return view('employees.view', compact('users', 'roles', 'logo', 'orgLogo'));
    }

    public function changeRoles(Request $request, $user_email) { /* Admin/Owner can change his/her(if multiple admins exist) or others roles that was assigned before*/
        $count = 0;
        
        $user = User::where('email',$user_email)->first();
        
        $permissions = Permission::with('roles')->where('name',"edit-users")->get(); // Get role-names who have Admin or Super Admin access using Permissions

        foreach ($permissions[0]->roles as $perRole) {
            $count += User::where('role',$perRole->name)->count();
        }

        if(auth()->user()->can('edit-users') && ((auth()->user()->email != $user_email) || ($count > 1 && auth()->user()->email == $user_email))) { 
            /* If user is authorized to edit & ((if he is not editing his permission) or(if he is editing his permissions but no of admin/super-admin/owner is > 1)) then*/
            $old_role = Role::where('name',$user->role)->first();//delete the old role
            $new_role = Role::where('name',$request->role)->first();// create new role

            //$user->roles()->sync($new_role->id); // id only
            $user->roles()->detach($old_role->id); /* Delete that user's old role */
            $user->roles()->attach($new_role->id); /* Assign new role to that user */

            User::where('email',$user_email)->update(['role' => $request->role]);

            return response()->json(['status' => 'Success']);
        } else if(auth()->user()->can('edit-users') && auth()->user()->email === $user_email) { /* Only one admin */
            return response()->json(['status' => 'Invalid', 'msg' => 'Only 1 Admin']);
        } else { /* User doesn't have permission to edit anyone's roles & permissions */
            return response()->json(['status' => 'Error', 'msg' => 'Permission Denied']);
        }
    }

    public function deleteUsers(Request $request, $user_id) { /* Delete his/her(if multiple user's exist) or other user's account if he/she has permissions */
        $count = 0;
        $permissions = Permission::with('roles')->where('name',"edit-users")->get(); // Get role-names who have Admin or Super Admin access using Permissions

        foreach ($permissions[0]->roles as $perRole) {
            $count += User::where('role',$perRole->name)->count();
        }

        if(auth()->user()->can('edit-users') && auth()->user()->id != $user_id) {
            /* If user is authorized to delete & if he is not deleting his account*/
            $userName = User::where('id',$user_id)->first()->name;
            //User::where('id',$user_id)->delete();
            User::where('id',$user_id)->update(['is_active' => false]);
            //return response()->json(['status' => 'Success']);
            return back()->with(['status' => 'success', 'user' => $userName]);
        } else if(auth()->user()->can('edit-users') && ($count > 1 && auth()->user()->id == $user_id)) {
            /* If user is authorized to delete & if he is deleting his account but no of admin/super-admin/owner is > 1 then*/
            //User::where('id',$user_id)->delete();
            User::where('id',$user_id)->update(['is_active' => false]);
            auth()->logout();// logout of session
            return redirect('/login');//return response()->json(['status' => 'Success']);
        } else if(auth()->user()->can('edit-users') && auth()->user()->id == $user_id) { /* Only one admin */
            return back()->with('status','invalid');//return response()->json(['status' => 'Invalid', 'msg' => 'Only 1 Admin']);
        } else { /* User doesn't have permission to delete anyone's account */
            return back()->with('status','error');//return response()->json(['status' => 'Error', 'msg' => 'Permission Denied']);   
        }
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
