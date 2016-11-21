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

use Illuminate\Http\Request;
use App\Http\Requests;

//use Redis;
use Symfony\Component\Console\Output\ConsoleOutput;

// for website
//Route::get('localization/{locale}','LocalizationController@index');
Route::get('lang/{lang}', ['as'=>'lang.switch', 'uses'=>'LocalizationController@switchLang']);

/*Route::get('/', function () {
    //$locale = "en";
    //session(['locale' => $locale]); // set the language for the page that user chose
    //echo session('locale');
    //exit();
    //app()->setLocale($locale);
    //Config::set('app.locale', "en");
    if(auth()->guest())
        return view('welcome',compact('locale'));
    else{
        $org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        return view('welcome',compact('logo','locale'));
    }
});

Route::group( ['prefix' => '{locale}'], function() {
    Route::get('/', function ($locale) {
        session(['locale' => $locale]); // set the language for the page that user chose
        app()->setLocale($locale);
        //echo session()->get('locale');
        //exit();
        if(auth()->guest())
            return view('welcome',compact('locale'));
        else{
            $org_id = User::where('email',auth()->user()->email)->get();
            
            $logo = Organization::find($org_id[0]->org_id)->get();
            $logo = $logo[0]->domain;
            return view('welcome',compact('logo','locale'));
        }
    });

    Route::auth();

    Route::get('/home', 'HomeController@index');
});*/

Route::get('/', function () {
    $locale = "en";
    //app()->setLocale($locale);
    if(auth()->guest())
        return view('welcome',compact('locale'));
    else{
        $org_id = User::where('email',auth()->user()->email)->get();
        
        $logo = Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        return view('welcome',compact('logo','locale'));
    }
});

Route::auth();

Route::get('/home', 'HomeController@index');

//Route::get('language/{lang}', 'HomeController@language')->where('lang', '[A-Za-z_-]+');



//Route::auth(); // for '/login' & '/register'
/*Route::get('/login', function () {
    return redirect('/' . App::getLocale(). '/login');
});*/

//$this->get('/{locale}/login', 'Auth\AuthController@showLoginForm');
//$this->post('login', 'Auth\AuthController@login');
//$this->get('logout', 'Auth\AuthController@logout');

// Registration Routes...
//$this->get('{locale}/register', 'Auth\AuthController@showRegistrationForm');
//$this->post('register', 'Auth\AuthController@register');

// google authentication
Route::get('/redirect/google', 'SocialAuthController@redirect');
Route::get('/callback/google', 'SocialAuthController@callback');
Route::get('/logout/google', 'SocialAuthController@logout');

//Route::get('/redirect/gplus', 'GPlusAuthController@redirect');// won't work like that as both GPlus & Social share same 'google' driver
//Route::get('/callback/gplus', 'GPlusAuthController@callback');// won't work like that as both GPlus & Social share same 'google' driver

// add new Organization details
Route::get('/org', 'OrganizationsController@index');
Route::post('/org/save','OrganizationsController@save');
Route::get('/orgpresent','OrganizationsController@domainPresent');

//user info
Route::get('/user','HomeController@profile');
Route::patch('/user/edit','HomeController@newprof');

// view different organizations
Route::get('/orgs','OrganizationsController@view');// view all the organizations
Route::get('/orgs/del/{org_id}','OrganizationsController@remove');// delete organization

Route::get('/roles','RolePermissionController@view');// view all the Roles & their permissions
Route::get('/roles/add','RolePermissionController@add');
Route::get('/roles/edit/{role_id}','RolePermissionController@edit');
Route::post('/roles/create','RolePermissionController@create');
Route::patch('/roles/edits','RolePermissionController@create');

// for app
Route::get('/confirm','SocialAuthController@getConfirm');// verification with db -> then return the API token key

Route::get('/org/info','OrganizationsController@info');//information of the organisation, employee comes under -> Ajax calls

//Route::get('/log/new','LogsController@newlog');// insert new log -> online, active, idle, offline -> Ajax Calls
Route::get('/personal','LogsController@viewPersonal');// get log details for activity log // Ajax calls

Route::group(['prefix' => 'api'], function () {

    // for app -> using AJAX call -> API auth in Org Controller
    Route::get('/org/info','OrganizationsController@info');//info of the organization, employee comes under

    // for app + node -> using Socket.io
    Route::get('fire', function () {
        // this fires the event
        $output = new ConsoleOutput();

        $output->writeln("At fire");
        
        //Redis::flushall();
        //$output->writeln("Flush Redis buffer");
        if(\Request::header( 'X-API-KEY' ) !== "") { // if api key is present in Header
            $output->writeln("Header Present");
            $output->writeln(\Request::header( 'X-API-KEY' ));

            $redis_list = json_decode(Redis::lindex('test-channels', 0), false);// take 1st element
            //$output->writeln("REDIS data to JSON:");
            if($redis_list) {
                //$output->writeln("REDIS data to JSON:" . $redis_list->user_id);
                $request_user_id = $redis_list->user_id;
                //$output->writeln("User ID:xxxxxx");
                $user = User::where(['id' => $request_user_id, 'api_token' => \Request::header( 'X-API-KEY' )])->get();
                //$user = User::where(['id' => $request_user_id])->get();
                $output->writeln("User ID:".$request_user_id);
                
                //Redis::flushall(); // clear all the data in queue
                if(count($user) > 0) { // if the user exist
                    $output->writeln("APi key Present");
                    
                    $redis_keys = Redis::keys('*');

                    $output->writeln("Length");
                    $queue_list_len = Redis::llen('test-channels');// get length of queue list
                    $output->writeln($queue_list_len);

                    /*$output->writeln("Keys ");
                    $output->writeln($redis_keys);
                    $output->writeln("Key 0");*/                

                    /*if($queue_list_len > 0) {
                        foreach (Redis::LRANGE('test-channels', 0, -1) as $key){ // get all the queue contents
                            $output->writeln($key);
                        }
                    }*/

                    //$output->writeln("Redis List");

                    if (isset($redis_list->socket_id)) { // check if the 1st content contains Socket_id
                        $output->writeln("Present");
                    } else {
                        $output->writeln("not Present");
                    }

                    //$output->writeln($redis_list);

                    event(new App\Events\EventChrome($redis_list));
                } else if($request_user_id == 0) { // If user_id = 0, then the user related to that socket_id has gone offline
                    $output->writeln("APi key not present");
                    
                    $request_user_socket = $redis_list->socket_id;
                    
                    $output->writeln("User socket ID:".$request_user_socket);

                    $user = User::where('socket_id', $request_user_socket)->get();
                    //$output->writeln("Getting count");
                    //$output->writeln(count($user));                
                    if(count($user)) {
                        $output->writeln("User ID before save:".$user[0]->id);
                        $redis_list->user_id = $user[0]->id;

                        $output->writeln("User ID:".$redis_list->user_id);
                        
                        $redis_keys = Redis::keys('*');

                        $output->writeln("Length");
                        $queue_list_len = Redis::llen('test-channels');// get length of queue list
                        $output->writeln($queue_list_len);

                        /*$output->writeln("Keys ");
                        $output->writeln($redis_keys);
                        $output->writeln("Key 0");*/

                        $output->writeln("Redis List");

                        if (isset($redis_list->socket_id)) { // check if the 1st content contains Socket_id
                            $output->writeln("Present");
                        } else {
                            $output->writeln("not Present");
                        }

                        //$output->writeln($redis_list);

                        event(new App\Events\EventChrome($redis_list));
                    } else { /* No such socket-ID exist */
                        Redis::lpop('test-channels');// remove the current-log element from queue    
                    }
                } else { // Invalid authentication
                    $redis_list = array("auth" => 0, "socket_id" => $redis_list->socket_id); // auth is set to 0 to define that user + APi key combination doesn't exist
                    event(new App\Events\EventChrome(json_decode(json_encode($redis_list), false)));
                }
            } else {
                $output->writeln("no content");
            }
        } else { // API token auth is not used for offline function
            $redis_list = json_decode(Redis::lindex('test-channels', 0), false);// take 1st element

            if($redis_list->to_state == "offline") {
                event(new App\Events\EventChrome($redis_list));
            } else {
                Redis::lpop('test-channels');// remove the current-log element from queue
                $output->writeln("No API auth");
            }
            
            
        }
    });

    Route::get('/data/save','LockedDataController@save'); // generate summary of logs & save in locked_data
    Route::get('/data/user','LockedDataController@user_log_summary');// get user log summary
    Route::get('/data/employees','LockedDataController@employees_log_summary'); // get all the employee's log summary
    
    Route::get('/data/role','RolePermissionController@role'); // Create new set of roles for the first time
});

Route::get('/trial','LogsController@trial');

Route::get('/per','LogsController@viewAbc');