<?php

namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;

use App\Organization;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser) {

        $user = User::whereEmail($providerUser->getEmail())->first();

        $org = Organization::where('domain',$providerUser->user["domain"])->get();
        $status = "exist";
        if (!$user) { // if the email & info is not present in the list, then create new
            $user = new User;
            $user->email = $providerUser->email;
            $user->name = $providerUser->name;
            $user->password = "user";
            $user->avatar = $providerUser->avatar;
            $user->acd = date('Y-m-d');
            $user->api_token = str_random(60);
            $user->org_id = 0;
            $user->lang = "en";
            $user->role = "member";
            $user->save();

            $status = "present";
        } else if($user->org_id == 0) { // safe-point, just incase user account is created but not linked with any organization, then redirect him/her to Join Organization page
            $status = "present";
        }

        return array($user, $status);
    }
}