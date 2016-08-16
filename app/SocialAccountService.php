<?php

namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;

use App\Organization;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser) {

        $user = User::whereEmail($providerUser->getEmail())->first();

        $org = Organization::where('domain',$providerUser->user["domain"])->get();
        
        if (!$user) { // if the email & info is not present in the list, then create new
            $user = new User;
            $user->email = $providerUser->email;
            $user->name = $providerUser->name;
            $user->password = "user";
            $user->avatar = $providerUser->avatar;
            $user->acd = date('Y-m-d');
            $user->org_id = $org[0]->id;
            $user->role = "admin";
            $user->save();
        }
        return $user;
    }
}