<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Organization;
use App\Log;
use App\Locked_Data;
use App\LogHistory;
use App\SocialAccountService;

use App\Role;
use App\Permission;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LockedDataController;

use Config;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Output\ConsoleOutput;

class LogHistoryController extends Controller
{
    public function save_log_history($details) { // Update all the changes on LogHistory table
    	$newLogHistory = new LogHistory;
		$newLogHistory->user_id = $details['user_id']; // User's Id whose data is modified
		$newLogHistory->modified_by = $details['modified_by']; // User's Id who is modifying the data
		$newLogHistory->modified_on = date('Y-m-d H:i:s'); // Add Current Date and Time
		$newLogHistory->table_modified = $details['table_modified'];
		$newLogHistory->column_modified = $details['column_modified'];
		$newLogHistory->old_value = $details['old_value'];
		$newLogHistory->new_value = $details['new_value'];
		$newLogHistory->save();
    }
    public function update_user_log_summary(Request $request) { // Update the Modifications on User logs/Locked_data
    	$output = new ConsoleOutput();

    	if($request->exists('user_email') && !empty($request->user_email) && $request->header('X-API-KEY')!= null && $request->exists('emp_email')  && $request->exists('work_date')  && $request->exists('start_time')  && $request->exists('end_time') ) { // if api key is present in Header & all the params are filled
        	$user_cnt = User::where(['email' => $request->user_email, 'api_token' => $request->header('X-API-KEY')])->count();
        	//$user_cnt = User::where('id', $request->user_id)->count();
        	if($user_cnt > 0) {
	        	$user = User::where(['email' => $request->user_email, 'api_token' => $request->header('X-API-KEY')])->first();
	        	if ($user->can('edit-users')) {// verifies if user has permission
	        		$emp_details = User::where(['email' => $request->emp_email])->first();

	        		$details = array('user_id' => $emp_details->id, 'modified_by' => $user->id, 'table_modified' => 'Locked_Data');

	        		$user_update = Locked_Data::where(['user_id' => $emp_details->id, 'work_date' => $request->work_date])->first();
	        		
	        		if($user_update->start_time != date("Y-m-d H:i:s",strtotime($request->work_date.' '.$request->start_time))){ // If the Start_time in DB is different from that from the $request, then update the start_time
	        			$details['column_modified'] = 'start_time';
	        			$details['old_value'] = $user_update->start_time;
	        			$user_update->start_time = date("Y-m-d H:i:s",strtotime($request->work_date.' '.$request->start_time)); // New Start time
	        			$details['new_value'] = $user_update->start_time;
	        			$this->save_log_history($details);
	        		}
	        		
	        		if($user_update->end_time != null && $user_update->end_time != date("Y-m-d H:i:s",strtotime($request->work_date.' '.$request->end_time))){ // If the end_time is not NULL && in DB is different from that from the $request, then update the end_time
	        			$details['column_modified'] = 'end_time';
	        			$details['old_value'] = $user_update->end_time;
	        			$user_update->end_time = date("Y-m-d H:i:s",strtotime($request->work_date.' '.$request->end_time)); // New End time
	        			$details['new_value'] = $user_update->end_time;
	        			$this->save_log_history($details);
	        		}

	        		if($user_update->end_time != null) // If end_time is not NULL, the User is offline
	        			$user_update->total_time = (new LockedDataController)->getTimeDifference($user_update->start_time, $user_update->end_time);
					else // else end_time is NULL, the User is online
						$user_update->total_time = null;
	        		$user_update->update();



	        		return response()->json(['status' => 'Success', 'msg' => 'Successfully updated'], 200);
	        	} else {
	        		return response()->json(['status' => 'Error', 'msg' => 'Permission Denied'], 403);
	        	}
	        } else {
	        	return response()->json(['status' => 'Error', 'msg' => 'Invalid User ID'], 401);
	        }
	    } else {
	    	return response()->json(['status' => 'Error', 'msg' => 'Required parameters not satisfied'], 400);
	    }
    }
}
