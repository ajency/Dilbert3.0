<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Organization;
use App\Log;
use App\SocialAccountService;

class OrganizationsController extends Controller
{
    public function index() {
    	return view('org.index');
    }

    public function save(Request $request) {

    	$this->validate($request, [
            'orgname' => 'required',
            'orgdomain' => 'required ',// | regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'defaulttz' => 'required',
            'ip' => 'required|min:2',
            'ipstatus' => 'required|min:2'
        ]);

    	//dd($request);
    	$alttime = array();
    	$ip = array();
    	$ipstatus = array();
    	
    	if(count($request->alttime) > 1){ // checks if alternate Time zones are selected
    		for($i = 1;$i < count($request->alttime); $i++){
    			$alttime[$i - 1] = $request->alttime[$i];
    		}
    	}

    	if(count($request->ip) > 1){ // 1st value is null of IP
    		for($i = 1;$i < count($request->ip); $i++ ){
    			$ip[$i - 1] = $request->ip[$i];
    			$ipstatus[$i - 1] = $request->ipstatus[$i];
    		}
    	}

    	$org = new Organization;
    	$org->name = $request->orgname;
    	$org->domain = $request->orgdomain;
    	$org->logo = $request->orglogo;
    	$org->default_tz = $request->defaulttz;
    	$org->alt_tz = $alttime;//serialize($request->alttime);
    	$org->ip_lists = $ip;//serialize($request->ip);
    	$org->ip_status = $ipstatus;
    	$org->save();

    	return redirect('/redirect/google');
    }

    public function view() {
    	$orgs = Organization::all();

    	$org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;

    	return view('org.view',compact('orgs','logo'));
    }

    public function remove($org_id) {
        $org = Organization::where('id',(int)$org_id)->get();

        if(count($org) > 0){
            $users = User::where('org_id',(int)$org_id)->get();
            
            foreach($users as $user){
                $logs = Log::where('user_id',$user->id)->delete();// delete each user log
                $user->delete();// delete each user
            }

            //dd($users);
            //
            $org = Organization::where('id',(int)$org_id)->delete();
            
            auth()->logout();// logout of session
            return redirect('/login');
        }

        //return back();
    }

    public function info(Request $request) {
        $org = Organization::where('id',$request->org_id)->get();

        return $org;
    }
}