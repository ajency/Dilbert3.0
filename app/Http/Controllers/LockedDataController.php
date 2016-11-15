<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Organization;
use App\Log;
use App\Locked_Data;
use App\SocialAccountService;

use App\Role;
use App\Permission;

use App\Events\Event;
use App\Events\EventChrome;

use Config;
use Illuminate\Support\Facades\Session;

use Symfony\Component\Console\Output\ConsoleOutput;

class LockedDataController extends Controller
{
	public function save(Request $request) { // Fetches data from logs & enter data in Locked-Table
		$lastDate = Locked_Data::orderBy('work_date', 'desc')->first();

		// Data is entered for the 1st time in Table
		if($lastDate === null) {
			$user_ids = Log::select('user_id', 'work_date')->groupBy('user_id', 'work_date')->get();//Distinct user id's & dates w.r.t to those users
			
			foreach($user_ids as $user) {
				$log_first = Log::where(['user_id' => $user->user_id, 'work_date' => $user->work_date])->first();
				$log_last = Log::where(['user_id' => $user->user_id, 'work_date' => $user->work_date])->orderBy('cos', 'desc')->orderBy('id', 'desc')->first();

				$summary = new Locked_Data;
				$summary->user_id = $user->user_id;
				$summary->work_date = $user->work_date;
				$summary->start_time = $log_first->cos;
				$summary->end_time = $log_last->cos;
				$summary->total_time = $this->getTimeDifference($log_first->cos, $log_last->cos);
				$summary->save();
			}

			return  response()->json(['status' => 'Success']);
		} else if(date('l', strtotime($lastDate->work_date)) != "Saturday" || date_diff(date_create($lastDate->work_date), date_create(date("Y-m-d")))->format("%R%a") != "+1") {
			/*if (previous date is not saturday) or (today's & last summary date difference is not +1)*/
			
			$user_ids = Log::select('user_id')->groupBy('user_id')->get();//Only Distinct user id's

			foreach($user_ids as $user) {
				
				/* Check if last date in the Locked_datas table is not same as last date in logs table */
				if (date_diff(date_create($lastDate->work_date), date_create(date("Y-m-d")))->format("%R%a") == "+0") { /* if same then take current date */
					$log_first = Log::where(['user_id'=> $user->user_id,'work_date' => strftime("%Y-%m-%d", strtotime($lastDate->work_date))])->first();
					$log_last = Log::where(['user_id'=> $user->user_id,'work_date' => strftime("%Y-%m-%d", strtotime($lastDate->work_date))])->orderBy('cos', 'desc')->orderBy('id', 'desc')->first();
					$work_date = strftime("%Y-%m-%d", strtotime($lastDate->work_date));
				} else { /* If not same the take the next date*/
					$log_first = Log::where(['user_id'=> $user->user_id,'work_date' => strftime("%Y-%m-%d", strtotime("$lastDate->work_date +1 day"))])->first();
					$log_last = Log::where(['user_id'=> $user->user_id,'work_date' => strftime("%Y-%m-%d", strtotime("$lastDate->work_date +1 day"))])->orderBy('cos', 'desc')->orderBy('id', 'desc')->first();
					$work_date = strftime("%Y-%m-%d", strtotime("$lastDate->work_date +1 day"));
				}

				$cross_check = Locked_Data::where(['user_id' => $user->user_id, 'work_date' => $work_date])->count();
				
				if($cross_check <= 0) { /* Check if data exist of the user w.r.t that/current date, if no, then add the new data */
					$summary = new Locked_Data;
					$summary->user_id = $user->user_id;
					$summary->work_date = $work_date;//strftime("%Y-%m-%d", strtotime("$lastDate->work_date +1 day"));
					$summary->start_time = $log_first->cos;
					$summary->end_time = $log_last->cos;
					$summary->total_time = $this->getTimeDifference($log_first->cos, $log_last->cos);
					$summary->save();
				} else {/* If data exist then update the data */
					if (date_diff(date_create($lastDate->work_date), date_create(date("Y-m-d")))->format("%R%a") == "+0") { /* If difference between dates is 0, then update*/
						$summary = Locked_Data::where(['user_id' => $user->user_id, 'work_date' => $work_date])->first();
						$summary->user_id = $user->user_id;
						$summary->work_date = $work_date;//strftime("%Y-%m-%d", strtotime("$lastDate->work_date +1 day"));
						$summary->start_time = $log_first->cos;
						$summary->end_time = $log_last->cos;
						$summary->total_time = $this->getTimeDifference($log_first->cos, $log_last->cos);
						$summary->update();
					}
				}
			}

			return  response()->json(['status' => 'Success']);
		} else { // Last date i.e. Yesterday was Saturday
			return  response()->json(['status' => 'Exist']);
		}
		return  response()->json(['status' => 'Error']);
	}

    public function user_log_summary(Request $request) {// display specific user's locked data in JSON
    	$output = new ConsoleOutput();
        $output->writeln("Personal Lock Data info");

        if(!empty($request->user_id) && $request->header('X-API-KEY')!= null) { // if api key is present in Header){
        	$user_cnt = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->count();
        	$output->writeln($user_cnt);
        	if($user_cnt > 0) {
	        	$user = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->first();
	        	if ($user->can('edit-personal')) {// verifies if user has permission
	        		$output->writeln("Confirmed");
			        if(empty($request->start_date) && empty($request->end_date))
			        	return Locked_Data::where('user_id',$request->user_id)->get();
			        else if(empty($request->start_date))
			        	return Locked_Data::where([['user_id',$request->user_id], ['work_date', '<=', $request->end_date],])->get();
			        else if(empty($request->end_date))
			        	return Locked_Data::where([['user_id',$request->user_id], ['work_date', '>=', $request->start_date],])->get();
			        else
			        	return Locked_Data::where('user_id',$request->user_id)->whereBetween('work_date',[$request->start_date, $request->end_date])->get();
			    } else {
			    	return response()->json(['status' => 'Error', 'msg' => 'Permission Denied']);
			    }
			} else {
				return response()->json(['status' => 'Error', 'msg' => 'Invalid User ID']);
			}
	    } else {
			$output->writeln("In else");	    	
	    	return response()->json(['status' => 'Error', 'msg' => 'User ID not defined']);
	    }
	    /*if($request->header('X-API-KEY') !== null) { // if api key is present in Header
            $output->writeln($request->header('X-API-KEY'));
            $user = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->get();

            $output->writeln(count($user));

            if(count($user) > 0) { // if the user exist

            }
        }*/
    }

    public function employees_log_summary(Request $request) {
    	$output = new ConsoleOutput();
        $output->writeln("Employees Lock Data info");

        if(!empty($request->user_id) && $request->header('X-API-KEY')!= null) { // if api key is present in Header){
        	$user_cnt = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->count();
        	//$user_cnt = User::where('id', $request->user_id)->count();
        	if($user_cnt > 0) {
	        	$user = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->first();
	        	if ($user->can('edit-users')) {// verifies if user has permission
	        		$output->writeln("Confirmed");
			        if(empty($request->start_date) && empty($request->end_date))
			        	return Locked_Data::orderBy('user_id')->get();
			        else if(empty($request->start_date))
			        	return Locked_Data::where('work_date', '<=', $request->end_date)->orderBy('user_id')->get();
			        else if(empty($request->end_date))
			        	return Locked_Data::where('work_date', '>=', $request->start_date)->orderBy('user_id')->get();
			        else
			        	return Locked_Data::whereBetween('work_date',[$request->start_date, $request->end_date])->orderBy('user_id')->get();
	        	} else {
			    	return response()->json(['status' => 'Error', 'msg' => 'Permission Denied']);
			    }
			} else {
				return response()->json(['status' => 'Error', 'msg' => 'Invalid User ID']);
			}
	    } else {
			//$output->writeln("In else");	    	
	    	return response()->json(['status' => 'Error', 'msg' => 'Required parameters not satisfied']);
	    }
    }

    public function getTimeDifference($time1, $time2) {
    	$output = new ConsoleOutput();
    	$t1 = explode(':', $time1);
    	$t2 = explode(':', $time2);

    	if($t2[0] > $t1[0] || ($t2[0] == $t1[0] && $t2[1] > $t1[1])) { #compare if Time2 > Time1
	    	$hr = (int)$t2[0] - (int)$t1[0];
	    	$min = (int)$t2[1] - (int)$t1[1];
	    } else {
	    	$hr = (int)$t1[0] - (int)$t2[0];
	    	$min = (int)$t1[1] - (int)$t2[1];
	    }

	    if($min < 0) { #check if min goes in subtraction value, if so, decrement Hr
    		$hr -= 1;
    		$min = $min + 60; // '+' as the $min value is negative
    	}

    	if($min >= 60) {#check if min exceeds 60 , if so, increment Hr
    		$hr += 1;
    		$min = $min - 60;
    	}

    	if($hr > 9)
    		$total = (string)$hr . ":";
    	else
    		$total = "0" . (string)$hr . ":";

    	if($min > 9)
    		$total = $total . (string)$min;
    	else
    		$total = $total . "0" . (string)$min;

    	return $total;
    }
}