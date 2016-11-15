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
                    if($status == "exist") {
                        auth()->login($user);
                        $this->setLang(auth()->user()->lang);
                        return redirect()->to('/home');
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
        if (array_key_exists(auth()->user()->lang, Config::get('app.locales'))) {
            Session::set('locale', "en");
        }

        auth()->logout();
        //return redirect()->to(session('locale').'/home');
        return redirect('/login');
    }
    
    // for app
    public function getConfirm(Request $request) { // confirms if user & organization exist
        $output = new ConsoleOutput();

        $output->writeln("Confirmation");
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