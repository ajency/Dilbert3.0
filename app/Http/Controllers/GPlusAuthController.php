<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Socialite;
use App\SocialAccountService;

class GPlusAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback(SocialAccountService $service) {
    	
        $user = $service->createOrGetUser(Socialite::driver('google')->user());

        dd($user);
    }
}
