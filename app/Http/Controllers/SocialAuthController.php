<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Socialite;
use App\SocialAccountService;
use App\Organization;
use App\User;

class SocialAuthController extends Controller {
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback(SocialAccountService $service) {
        try {
            $account = Socialite::driver('google')->user();
            if(isset($account->user["domain"])){ // checks the presence of domain attribute/object
                $org = Organization::where('domain',$account->user["domain"])->get();
                if(count($org) > 0) { //domain exist in database
                    $arraySocial = $service->createOrGetUser($account);
                    $user = $arraySocial[0];
                    $status = $arraySocial[1];
                    if($status == "exist") {
                        auth()->login($user);
                        return redirect()->to('/home');
                    } else if($status == "present") {// join organization
                        $company = $org[0]->name;
                        $domain = $org[0]->domain;
                        $useremail = $user->email;
                        return view('org.index',compact('status','company','domain','useremail'));// open Join Organization page
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
        auth()->logout();
        return redirect()->to('/home');
    }

    public function getConfirm(Request $request) { // confirms if user & organization exist
        $user_id = User::where('email',$request->email)->get();

        if(count($user_id) > 0)
            return $user_id;
        else if(isset($request->content["hd"])){ // check if domain exist
            $org = Organization::where('domain',$request->content["hd"])->get();
            
            if(count($org) > 0) {
                $user = new User;
                $user->email = $request->content["email"];
                $user->name = $request->content["name"];
                $user->password = "user";
                $user->avatar = $request->content["picture"];
                $user->acd = date('Y-m-d');
                $user->org_id = $org[0]->id;
                $user->role = "member";
                $user->save();

                $user = User::find($user->id);
                return $user;
            }
        }
        return 0;
    }
}