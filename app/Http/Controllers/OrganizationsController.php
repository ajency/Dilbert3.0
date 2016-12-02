<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Organization;
use App\Log;
use App\SocialAccountService;

use App\Role;
use App\Permission;

use App\Events\Event;
use App\Events\EventChrome;

use Config;
use Illuminate\Support\Facades\Session;

use Symfony\Component\Console\Output\ConsoleOutput;

class OrganizationsController extends Controller
{
    public function index(Request $request) {
        //set’s application’s locale
        // app()->setLocale($locale);
        
        $status = "present";
        $company = "";
        $domain = "";
        $useremail = "";

        if(isset($request->content)){
            $account = $request;
            $useremail = $request->content["email"];
            $status = "new";
            $ip = $_SERVER['REMOTE_ADDR'];    
            return view('org.index',compact('account','status','useremail','ip'));     
        }
        return view('org.index',compact('status','company','domain','useremail'));
    }

    public function save(Request $request) {// create organization
        //set’s application’s locale
        // app()->setLocale($locale);
        $this->validate($request, [
            'orgname' => 'required',
            'orgdomain' => 'required ',// | regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'defaulttz' => 'required',
            'idleTime' => 'required',
            'orgdeflang' => 'required',
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
    	$org->alt_tz = serialize($alttime);//$alttime;//serialize($request->alttime);
        $org->idle_time = $request->idleTime;
        $org->default_lang = $request->orgdeflang;
    	$org->ip_lists = serialize($ip);//serialize($request->ip);
    	$org->ip_status = serialize($ipstatus);// unserialize() to read from database
    	$org->save();

        $org_id = User::where('email',$request->userid)->update(['org_id' => $org->id,'role' => 'admin','timeZone' => $org->default_tz,'lang' => $org->default_lang]);
        $user = User::where('email',$request->userid)->get();
        
        if(Role::where('name','admin')->count() <= 0) { /* Checks if roles & permissions are populated, if not add initial roles & permissions */ 
            $create_roles = new RolePermissionController;
            $create_roles->role($request);
        }

        /* Link user to admin's role & respective permissions */
        $admin = Role::where('name','admin')->first();
        // or eloquent's original technique
        $user[0]->roles()->attach($admin->id); // id only
        auth()->login($user[0]);
        if (array_key_exists($user[0]->lang, Config::get('app.locales'))) {
            Session::set('locale', $user[0]->lang);
        }

        return redirect()->to('/home');
    	//return redirect('/redirect/google');
    }

    public function domainPresent(Request $request) { // new user but same domain, then asking confirmation
        //set’s application’s locale
        // app()->setLocale($locale);

        try {
            $org = Organization::where('domain',$request->orgdomain)->get();
            if(count($org) > 0) { //domain exist in database
                $org_id = User::where('email',$request->userid)->update(['org_id' => $org[0]->id, 'timeZone' => $request->jointz]);
                $user = User::where('email',$request->userid)->get();

                /* Link user to members's role & respective permissions */
                $member = Role::where('name','member')->first();

                // or eloquent's original technique
                $user[0]->roles()->attach($member->id); // id only

                auth()->login($user[0]);
                return redirect()->to('/home');
            }
        } catch (Exception $e) {
            
        }
    }

    public function view() {
        //set’s application’s locale
        // app()->setLocale($locale);

    	$orgs = Organization::all();

    	$org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;

    	return view('org.view',compact('orgs','logo'));
    }

    public function remove($org_id) {/* Deleting Organzation */
        //set’s application’s locale
        // app()->setLocale($locale);

        $output = new ConsoleOutput();

        $org = Organization::where('id',(int)$org_id)->get();
        //$flag = false;
        if(count($org) > 0) {
            $users = User::where('org_id',(int)$org_id)->get();
            $check = User::where('org_id',(int)$org_id)->where('id',auth()->user()->getId())->get();
            
            foreach($users as $user){
                $logs = Log::where('user_id',$user->id)->delete();// delete each user log
                $user->delete();// delete each user
            }

            $org = Organization::where('id',(int)$org_id)->delete();
            $redis_list = array("org_id" => (int)$org_id);
            
            //event(new EventChrome(json_decode(json_encode($redis_list), false))); -> for logging out user's from chrome app

            if(count($check) > 0){    
                auth()->logout();// logout of session
                return redirect('/login');
            }

            return back();
        }
    }

    //for app
    public function info(Request $request) {
        $output = new ConsoleOutput();
        
        $output->writeln("Org info");

        if($request->header('X-API-KEY') !== null) { // if api key is present in Header
            $output->writeln($request->header('X-API-KEY'));
            
            $user = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->get();

            $output->writeln(count($user));

            if(count($user) > 0) { // if the user exist
                $org = Organization::where('id',$request->org_id)->get();
                if(count($org) > 0){ // if organization exist
                    $org[0]->alt_tz = unserialize($org[0]->alt_tz);
                    $org[0]->ip_lists = unserialize($org[0]->ip_lists);
                    $org[0]->ip_status = unserialize($org[0]->ip_status);
                    $org[0]->ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    return 0;
                }
                return $org;
            } else {
                return -1; // invalid authentication -> intruder
            }
        } else {
            return -2; // No Api key, seems like user not logged in
        }
        return 0;// create organization
    }
}