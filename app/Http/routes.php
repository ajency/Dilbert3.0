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

// google authentication
Route::get('/redirect/google', 'SocialAuthController@redirect');
Route::get('/callback/google', 'SocialAuthController@callback');
Route::get('/logout/google', 'SocialAuthController@logout');

// add new Organization details
Route::get('/org', 'OrganizationsController@index');
Route::post('/org/save','OrganizationsController@save');// Creates Organization
Route::get('/orgpresent','OrganizationsController@domainPresent');// Assigns to that Organization

// Complaint Box
Route::post('/query','ComplaintController@issueFaced');

//user info
Route::get('/user','HomeController@profile');
Route::patch('/user/edit','HomeController@newprof');
Route::get('/employees','HomeController@viewEmployees');
Route::post('/employees/update/{user_id}','HomeController@changeRoles');
Route::get('/employees/delete/{user_id}','HomeController@deleteUsers');

// view different organizations
Route::get('/orgs','OrganizationsController@view');// view all the organizations
Route::get('/orgs/delete/{org_id}','OrganizationsController@remove');// delete organization

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

Route::get('/dashboard', function() { /* Angular2 PWA page route */

    if(!auth()->guest()) { /* If user is Authorized, then access Dashboard, else Redirect to Login page */
        $org_id = App\User::where('email',auth()->user()->email)->get();
            
        $logo = App\Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        $logs = App\Log::where([['user_id',auth()->user()->id],['work_date',date('Y-m-d')],])->get();// get data based on today's date
        
        return view('angular.index', compact('logo','logs'))->with('leads',json_encode(array("id" => auth()->user()->id, "user_name" => auth()->user()->name, "api_token" => auth()->user()->api_token, "org_domain" => $logo)));
    } else 
        return redirect('/login');
});

Route::get('/dashboard/{emp_email}', function($emp_email) { /* Angular2 PWA page route */ /* For other members page */

    if(!auth()->guest()) { /* If user is Authorized, then access Dashboard, else Redirect to Login page */
        if(filter_var($emp_email, FILTER_VALIDATE_EMAIL)) { // Check if the URL subsection has email address
            $org_id = App\User::where('email',auth()->user()->email)->get();
            
            #$emp_details = App\User::where('email',$emp_email)->first();
            $emp_details = App\User::where(['email' => $emp_email, 'is_active' => true])->first();

            if(count($emp_details) > 0){
                $logo = App\Organization::find($org_id[0]->org_id)->get();
                $logo = $logo[0]->domain;
                $logs = App\Log::where([['user_id', $emp_details->id],['work_date', date('Y-m-d')],])->get();// get data based on today's date
                
                /*return view('angular.index', compact('logo','logs'))->with('leads',json_encode(array("id" => auth()->user()->id, "user_name" => auth()->user()->name, "api_token" => auth()->user()->api_token, "org_id" => auth()->user()->org_id, "emp_id" => $emp_details->id, "emp_name" => $emp_details->name, "emp_email" => $emp_email, "other_emp" => "true")));*/
                return view('angular.index', compact('logo','logs'))->with('leads',json_encode(array("id" => auth()->user()->id, "user_name" => auth()->user()->name, "api_token" => auth()->user()->api_token, "org_domain" => $logo)));
            } else { // This email address doesn't exist
                abort(412);
                //abort(404);
            }
        } else { // it is not an Email Address
            abort(404);
        }
    } else 
        return redirect('/login');
});

Route::get('/summary', function() {
    if(!auth()->guest()) { /* If user is Authorized, then access Summary page, else Redirect to Login page */
        $org_id = App\User::where('email',auth()->user()->email)->get();
            
        $logo = App\Organization::find($org_id[0]->org_id)->get();
        $logo = $logo[0]->domain;
        $logs = App\Log::where([['user_id',auth()->user()->id],['work_date',date('Y-m-d')],])->get();// get data based on today's date
        return view('angular.summary', compact('logo','logs'));
    } else 
        return redirect('/login');
});

Route::group(['prefix' => 'api'], function () {

    // for app -> using AJAX call -> API auth in Org Controller
    Route::get('/org/info','OrganizationsController@info');//info of the organization, employee comes under

    // for app + nodeJS server -> using Socket.io (or) for app -> using ajax
    Route::get('fire', function (Request $request) {
        // this fires the event
        $output = new ConsoleOutput();

        $output->writeln("At fire");
        
        if(\Request::exists('data_from') && \Request::only('data_from') !== "") { /* request has come from Chrome App */
            $output->writeln("requested from chrome");

            if($request->header( 'X-API-KEY' ) !== "" && $request->to_state === "New Session") {
                $output->writeln("Login Redis");
                $redis_list = $request;
                
                $redis_list->ip = request()->ip(); /* Get IP address using Request */
                event(new App\Events\EventChrome($redis_list));
            } else if ($request->to_state === "offline") {
                $output->writeln("Logout Redis");

                $redis_list = $request;
                $redis_list->ip = request()->ip(); /* Get IP address using request */
                event(new App\Events\EventChrome($redis_list));
            }

        } else { /* Request has come from nodeJS */
            $output->writeln("requested from nodeJS");
            
            if(\Request::header( 'X-API-KEY' ) !== "") { // if api key is present in Header
                $output->writeln("Header Present");
                $output->writeln(\Request::header( 'X-API-KEY' ));

                $redis_list = json_decode(Redis::lindex('test-channels', 0), false);// take 1st element
                //$output->writeln("REDIS data to JSON:");
                if($redis_list) {
                    //$output->writeln("REDIS data to JSON:" . $redis_list->user_id);
                    $request_user_id = $redis_list->user_id;
                    $user = User::where(['id' => $request_user_id, 'api_token' => \Request::header( 'X-API-KEY' )])->get();
                    //$user = User::where(['gen_id' => $request_user_id, 'api_token' => \Request::header( 'X-API-KEY' )])->get();
                    $output->writeln("User ID:".$request_user_id);
                    
                    if(count($user) > 0) { // if the user exist
                        $output->writeln("APi key Present");
                        
                        $redis_keys = Redis::keys('*');

                        $output->writeln("Length");
                        $queue_list_len = Redis::llen('test-channels');// get length of queue list
                        $output->writeln($queue_list_len);

                        if (isset($redis_list->socket_id)) { // check if the 1st content contains Socket_id
                            $output->writeln("Present");
                        } else {
                            $output->writeln("not Present");
                        }

                        event(new App\Events\EventChrome($redis_list));
                    } else if($request_user_id == 0) { // If user_id = 0, then the user related to that socket_id has gone offline
                        $output->writeln("APi key not present");
                        
                        $request_user_socket = $redis_list->socket_id;
                        
                        $output->writeln("User socket ID:".$request_user_socket);

                        $user = User::where('socket_id', $request_user_socket)->get();
                        //$output->writeln("Getting count");
                        $output->writeln(count($user));                
                        if(count($user)) {
                            $output->writeln("User ID before save:".$user[0]->id);
                            
                            $redis_keys = Redis::keys('*');

                            $output->writeln("Length");
                            $queue_list_len = Redis::llen('test-channels');// get length of queue list
                            $output->writeln($queue_list_len);

                            $output->writeln("Redis List");

                            if (isset($redis_list->socket_id)) { // check if the 1st content contains Socket_id
                                $output->writeln("Present");
                            } else {
                                $output->writeln("not Present");
                            }

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
            } else { // API token authentication is not used for offline function
                $redis_list = json_decode(Redis::lindex('test-channels', 0), false);// take 1st element

                if($redis_list->to_state == "offline") {
                    event(new App\Events\EventChrome($redis_list));
                } else {
                    Redis::lpop('test-channels');// remove the current-log element from queue
                    $output->writeln("No API auth");
                }       
            }
        }
    });
    
    Route::get('/data/save','LockedDataController@save'); // generate summary of logs & save in locked_data
    Route::get('/data/user','LockedDataController@user_log_summary');// get user log summary
    //Route::get('/data/users/{emp_id}','LockedDataController@other_users_log_summary'); // get other employee's log summary
    
    Route::get('/data/username','SocialAuthController@getName'); // Get Name w.r.t that Email ID
    Route::get('/data/users','LockedDataController@other_users_log_summary'); // get other employee's log summary
    Route::get('/data/employees','LockedDataController@employees_log_summary'); // get all the employees log summary
    
    Route::get('/data/role','RolePermissionController@role'); // Create new set of roles for the first time
});

Route::get('/trial','LogsController@trial');

Route::get('/per','LogsController@viewAbc');