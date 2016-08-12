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
            //dd($account->user["domain"]);
            if(isset($account->user["domain"])){ // checks the presence of domain attribute/object
                //if($account->domain == "ajency.in")
                //dd($account);
                //return response()->json($account);
                $org = Organization::where('domain',$account->user["domain"])->get();
                if(count($org) > 0) { //domain exist in database
                    $user = $service->createOrGetUser($account);
                    auth()->login($user);
                    
                    //return $user;
                    return redirect()->to('/home');
                } else { // add organization
                    //dd($account->user["domain"]);
                    return view('org.index',compact('account'));       
                }
            } else { // if no domain
                return redirect('/login');
            }   
        } catch (Exception $e) {
            
        }
    }

    public function logout() { // overrided the default login -> for chrome App
        //Auth::logout();
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