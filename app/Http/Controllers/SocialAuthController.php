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
                    
                    return view('org.index',compact('account','status','useremail'));// open new organization page
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

    public function getConfirm(Request $request) {
        $user_id = User::where('name',$request->name)->get();

        if(count($user_id) > 0)
            return $user_id;
        else
            return 0;
    }
}