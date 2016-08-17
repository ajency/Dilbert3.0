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
        $status = "present";
        $company = "";
        $domain = "";
        $useremail = "";
    	return view('org.index',compact('status','company','domain','useremail'));
    }

    public function save(Request $request) {

        $this->validate($request, [
            'orgname' => 'required',
            'orgdomain' => 'required ',// | regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'defaulttz' => 'required',
            'idleTime' => 'required',
            'ip' => 'required|min:1',
            'ipstatus' => 'required|min:1'
        ]);

    	$alttime = array();
    	$ip = array();
    	$ipstatus = array();
    	
    	if(count($request->alttime) > 0){ // checks if alternate Time zones are selected
    		for($i = 0;$i < count($request->alttime); $i++){
    			$alttime[$i] = $request->alttime[$i];
    		}
    	}

    	if(count($request->ip) > 0){ // 1st value is null of IP
    		for($i = 0;$i < count($request->ip); $i++ ){
    			$ip[$i] = $request->ip[$i];
    			$ipstatus[$i] = $request->ipstatus[$i];
    		}
    	}

    	$org = new Organization;
    	$org->name = $request->orgname;
    	$org->domain = $request->orgdomain;
    	$org->logo = $request->orglogo;
    	$org->default_tz = $request->defaulttz;
    	$org->alt_tz = $alttime;//serialize($request->alttime);
        $org->idle_time = $request->idleTime;
    	$org->ip_lists = $ip;//serialize($request->ip);
    	$org->ip_status = $ipstatus;
    	$org->save();

        $org_id = User::where('email',$request->userid)->update(['org_id' => $org->id]);
        $user = User::where('email',$request->userid)->get();
        auth()->login($user[0]);
        return redirect()->to('/home');
    	//return redirect('/redirect/google');
    }

    public function domainPresent(Request $request) { // new user but same domain, then asking confirmation
        try {
            $org = Organization::where('domain',$request->orgdomain)->get();
            if(count($org) > 0) { //domain exist in database
                $org_id = User::where('email',$request->userid)->update(['org_id' => $org[0]->id]);
                $user = User::where('email',$request->userid)->get();
                auth()->login($user[0]);
                return redirect()->to('/home');
            }
        } catch (Exception $e) {
            
        }
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

            $org = Organization::where('id',(int)$org_id)->delete();
            
            auth()->logout();// logout of session
            return redirect('/login');
        }
    }

    public function info(Request $request) {
        $org = Organization::where('id',$request->org_id)->get();
        return $org;
    }
}