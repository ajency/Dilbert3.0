<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Socialite;
use App\SocialAccountService;
use App\Organization;
use App\User;

use Config;
use Illuminate\Support\Facades\Session;

use Symfony\Component\Console\Output\ConsoleOutput;

class SocialAuthController extends Controller {
    public function setLang($lang) {
        if (array_key_exists($lang, Config::get('app.locales'))) {
            Session::set('locale', $lang);
        }
    }

    public function redirect() { // for google authentication
        return Socialite::driver('google')->redirect();
    }

    public function callback(SocialAccountService $service) { // after Google authentication & redirection
        try {
            //set’s application’s locale
            //app()->setLocale($locale);
            
            $account = Socialite::driver('google')->stateless()->user(); /* trying to use socialite on a laravel with socialite sessions deactivated */
            if(isset($account->user["domain"])){ // checks the presence of domain attribute/object
                if(Organization::where('domain', '=',$account->user["domain"])->exists()) {
                    $arraySocial = $service->createOrGetUser($account);
                    $org = Organization::where('domain', '=',$account->user["domain"])->get();
                    $user = $arraySocial[0];
                    $status = $arraySocial[1];
                    if($status == "exist") { // UserID & the Org linking to user exist
                        auth()->login($user);
                        $this->setLang(auth()->user()->lang);
                        return redirect()->to('/home')->with('reload', "true");
                    } else if($status == "present") {// join organization
                        $company = $org[0]->name;
                        $domain = $org[0]->domain;
                        $useremail = $user->email;
                        $timeZones = array($org[0]->default_tz);// default time zone
                        $timeZones = array_merge($timeZones, unserialize($org[0]->alt_tz));//merge default & alt
                        return view('org.index',compact('status','company','domain','useremail','timeZones'));// open Join Organization page
                    }
                } else { // add organization
                    $arraySocial = $service->createOrGetUser($account);
                    $user = $arraySocial[0];
                    $useremail = $user->email;
                    $status = "new";
                    $ip = $_SERVER['REMOTE_ADDR'];    
                    return view('org.index',compact('account','status','useremail','ip'));// open new organization page
                }
            } else { // if no domain
                return redirect('/login');
            }   
        } catch (Exception $e) {
            
        }
    }
    
    public function logout() { // overrided the default login -> for chrome App
        $output = new ConsoleOutput();

        try {
            if(auth()->check()) { // User's session is Valid
                if (array_key_exists(auth()->user()->lang, Config::get('app.locales'))) {
                    Session::set('locale', "en");
                }

                auth()->logout();
                //return redirect()->to(session('locale').'/home');
                return redirect('/login');
            } else { // User's Session Timed out
                Session::set('locale', "en");
                auth()->logout();
                return redirect('/login')->with('message', "session_timeout");
            }
        } catch (Exception $e) { // User session validation Exception
            Session::set('locale', "en");
            auth()->logout();
            return redirect('/login')->with('message', "session_timeout");
        }
    }
    
    // for PWA - Progressive Web App
    public function getName(Request $request) {
        if(!empty($request->user_id) && $request->header('X-API-KEY')!= null) { // if api key is present in Header){
            $user_cnt = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->count(); // Check if user with that userID & API_token exist & get the count of those user's
            if($user_cnt > 0) {
                $user = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->first();
                if ($user->can('edit-personal')) {// This permission can access self & other user's data//if ($user->can('edit-users')) {// verifies if user has permission to read other's data
                    
                    $content = []; $json = [];

                    $emp_data = User::where(['email' => $request->emp_email])->first();
                    if($emp_data != null) { // The Email ID exist in the database
                        $content["name"] = $emp_data->name;
                        $content["email"] = $request->emp_email;
                        
                        array_push($json, $content); // push the content to the array
                        return response()->json($json);
                    }  else {
                        return response()->json(['status' => 'Error', 'msg' => 'Invalid ID'], 401);
                    }
                } else {
                    return response()->json(['status' => 'Error', 'msg' => 'Invalid Parameters'], 403);
                }
            } else {
                return response()->json(['status' => 'Error', 'msg' => 'Invalid User ID'], 401);
            }
        } else {
            //$output->writeln("In else");          
            return response()->json(['status' => 'Error', 'msg' => 'Required parameters not satisfied'], 400);
        }
    }

    // for app
    public function getConfirm(Request $request) { // confirms if user & organization exist
        $output = new ConsoleOutput();

        //$output->writeln("Confirmation");
        $user_id = User::where('email',$request->email)->get();
        
        if(count($user_id) > 0) {
            $output->writeln("User exist");
            //$user_id = User::where('email',$request->email)->update(['api_token' => str_random(60)]); // commented as API token is generated when user creates account for the 1st time
            $user_id = User::where('email',$request->email)->get();
            return $user_id;
        }
        else if(isset($request->content["hd"])){ // check if domain exist
            $output->writeln("User not existing");
            /*$org = Organization::where('domain',$request->content["hd"])->get();
            
            if(count($org) > 0) { // if organization exist, then create user, & continue
                $user = new User;
                $user->email = $request->content["email"];
                $user->name = $request->content["name"];
                $user->password = "user";
                $user->avatar = $request->content["picture"];
                $user->acd = date('Y-m-d');
                $user->org_id = $org[0]->id;
                $user->role = "member";
                $user->api_token = str_random(60);
                $user->save();
                
                $user = User::find($user->id);
                return $user;
            }*/
            return 1;
        }

        $output->writeln("No organization");
        return 0;
    }
}