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
            $user->gen_id = (int)strtotime(date('Y-m-d H:i:s.u')) * 1000; // New Generated_userID a.k.a EmployeeID -> for API Request & maybe for the URL
            $user->name = $providerUser->name;
            $user->password = "user";
            $user->avatar = $providerUser->avatar;
            $user->acd = date('Y-m-d');
            $user->api_token = str_random(60); // Generate 1 time API token for the User
            $user->org_id = 0;
            $user->lang = (isset($providerUser->language))? explode("_",$providerUser->language)[0] : "en"; // If page language is defined by the user, the get that language
            $user->role = "member";
            $user->gender = (isset($providerUser->gender)) ? $providerUser->gender: "-";
            $user->dob = (isset($providerUser->birthday)) ? $providerUser->birthday : "-";
            $user->save();

            $status = "present";
        } else if($user->org_id == 0) { // safe-point, just incase user account is created but not linked with any organization, then redirect him/her to Join Organization page
            $status = "present";
        }

        return array($user, $status);
    }
}