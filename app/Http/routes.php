<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\User;
use App\Organization;

Route::get('/', function () {
	if(auth()->guest())
    	return view('welcome');
    else{
    	$org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        return view('welcome',compact('logo'));
    }
});

Route::auth();
// google authentication
Route::get('/redirect/google', 'SocialAuthController@redirect');
Route::get('/callback/google', 'SocialAuthController@callback');
Route::get('/logout/google', 'SocialAuthController@logout');

Route::get('/redirect/gplus', 'GPlusAuthController@redirect');// won't work like that as both GPlus & Social share same 'google' driver
Route::get('/callback/gplus', 'GPlusAuthController@callback');// won't work like that as both GPlus & Social share same 'google' driver
Route::get('/home', 'HomeController@index');

// add new Organization details
Route::get('/org', 'OrganizationsController@index');
Route::post('/org/save','OrganizationsController@save');

//user info
Route::get('/user','HomeController@profile');
Route::patch('/user/edit','HomeController@newprof');

// view different organizations
Route::get('/orgs','OrganizationsController@view');
Route::get('/orgs/del/{org_id}','OrganizationsController@remove');

// for app
Route::get('/confirm','SocialAuthController@getConfirm');// verification with db

Route::get('/org/info','OrganizationsController@info');//infromation of the organisation, employee comes under

Route::get('/log/new','LogsController@newlog');// insert new log -> online, active, idle, offline
Route::get('/personal','LogsController@viewPersonal');// get log details for activity log



Route::get('/per','LogsController@viewAbc');