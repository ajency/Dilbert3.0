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

use Redis;
use Config;
use Illuminate\Support\Facades\Session;

use Symfony\Component\Console\Output\ConsoleOutput;

class LockedDataController extends Controller
{
	public function save(Request $request) { // Fetches data from logs & enter data in Locked-Table
		$lastDate = Locked_Data::orderBy('work_date', 'desc')->first();// Get the last working date from Locked_data, to check if DB is empty or contains data

		if($lastDate === null) {// Data is entered for the 1st time in Table
			$user_ids = Log::select('user_id', 'work_date')->groupBy('user_id', 'work_date')->get();//Distinct user id's & dates w.r.t to those users
			
			foreach($user_ids as $user) {/* loop through each user's data */
				$log_first = Log::where(['user_id' => $user->user_id, 'work_date' => $user->work_date])->first();// Get 1st timing of that day's log
				$log_last = Log::where(['user_id' => $user->user_id, 'work_date' => $user->work_date])->orderBy('cos', 'desc')->orderBy('id', 'desc')->first();// Get last timing of that day's log

				$summary = new Locked_Data;
				$summary->user_id = $user->user_id;
				$summary->work_date = $user->work_date;
				$summary->start_time = date("Y-m-d H:i:s",strtotime($user->work_date.' '.$log_first->cos));
				$summary->end_time = date("Y-m-d H:i:s",strtotime($user->work_date.' '.$log_last->cos));
				$summary->total_time = $this->getTimeDifference($log_first->cos, $log_last->cos);
				$summary->save();
			}

			Redis::flushall();
			Redis::flushdb();
			return  response()->json(['status' => 'Success']);
		} else if(date('l', strtotime($lastDate->work_date)) != "Saturday" || date_diff(date_create($lastDate->work_date), date_create(date("Y-m-d")))->format("%R%a") != "+1") {
			/*if (previous date is not saturday) or (today's & last summary date difference is not +1)*/
			
			$user_ids = Log::select('user_id')->groupBy('user_id')->get();//Only Distinct user id's

			foreach($user_ids as $user) {// Get each user ID whose logs are entered in Log table
				$lastLogDatesUser = Log::select('user_id','work_date')->where('user_id',$user->user_id)->max('work_date');// Get that users last log date
				$lastLockedDatesUser = Locked_Data::select('user_id','work_date')->where('user_id',$user->user_id)->max('work_date'); // Get that user's last Summary date

				if((int)date_diff(date_create($lastLogDatesUser), date_create($lastLockedDatesUser))->format("%R%a") == 0) {
					/* If  the date is same means the content of that user is up-to-date*/
					$log_first = Log::where(['user_id'=> $user->user_id,'work_date' => $lastLogDatesUser])->first();
					$log_last = Log::where(['user_id'=> $user->user_id,'work_date' => $lastLogDatesUser])->orderBy('cos', 'desc')->orderBy('id', 'desc')->first();
					
					$summary = Locked_Data::where(['user_id' => $user->user_id, 'work_date' => $lastLockedDatesUser])->first();
					$summary->user_id = $user->user_id;
					$summary->work_date = $lastLockedDatesUser;//strftime("%Y-%m-%d", strtotime("$lastDate->work_date +1 day"));
					$summary->start_time = date("Y-m-d H:i:s",strtotime($lastLockedDatesUser.' '.$log_first->cos));//$log_first->cos;
					$summary->end_time = date("Y-m-d H:i:s",strtotime($lastLockedDatesUser.' '.$log_last->cos));//$log_last->cos;
					$summary->total_time = $this->getTimeDifference($log_first->cos, $log_last->cos);
					$summary->update();
				} else if((int)date_diff(date_create($lastLockedDatesUser), date_create($lastLogDatesUser))->format("%R%a") > 0) {
					/* If date in Logs is leading, then update all the logs in Locked_Data table */
					$user_logs = Log::select('user_id', 'work_date')->where(['user_id' => $user->user_id, ['work_date', '>=',$lastLockedDatesUser]])->groupBy('user_id', 'work_date')->get();// Get all the data on & after the Last Date in Locked_Data & group based on user id & work_date

					foreach($user_logs as $user_log) { /* Loop through all the dates */
						if(date('l', strtotime($user_log->work_date)) != "Sunday") {
							$log_first = Log::where(['user_id'=> $user_log->user_id,'work_date' => $user_log->work_date])->first();/* Get the 1st time of that day's log */
							$log_last = Log::where(['user_id'=> $user_log->user_id,'work_date' => $user_log->work_date])->orderBy('cos', 'desc')->orderBy('id', 'desc')->first();/* Get the last time of that day's log*/
							if(Locked_Data::where(['user_id' => $user_log->user_id, 'work_date' => $user_log->work_date])->count() > 0) { /* If that date exist in Locked_data, then update the content */
								$summary = Locked_Data::where(['user_id' => $user_log->user_id, 'work_date' => $user_log->work_date])->first();
								$summary->user_id = $user_log->user_id;
								$summary->work_date = $user_log->work_date;
								$summary->start_time = date("Y-m-d H:i:s",strtotime($user_log->work_date.' '.$log_first->cos));//$log_first->cos;
								$summary->end_time = date("Y-m-d H:i:s",strtotime($lastLockedDatesUser.' '.$log_last->cos));//$log_last->cos;
								$summary->total_time = $this->getTimeDifference($log_first->cos, $log_last->cos);
								$summary->update();
							} else {/* Else the data doesn't exist in Locked_Data, hence Insert the new Logs */
								$summary = new Locked_Data;
								$summary->user_id = $user_log->user_id;
								$summary->work_date = $user_log->work_date;
								$summary->start_time = date("Y-m-d H:i:s",strtotime($user_log->work_date.' '.$log_first->cos));//$log_first->cos;
								$summary->end_time = date("Y-m-d H:i:s",strtotime($user_log->work_date.' '.$log_last->cos));//$log_last->cos;
								$summary->total_time = $this->getTimeDifference($log_first->cos, $log_last->cos);
								$summary->save();
							}// end of if-else
						} // If not Sunday
					}// end of foreach($user_logs as $user_log)
				}// end of if-elseif
			}// End of foreach($user_ids as $user)
			Redis::flushall();// Clear all the buffer
			Redis::flushdb();// Clear all the queue
			return  response()->json(['status' => 'Success']);
		} else { // Last date i.e. Yesterday was Saturday
			Redis::flushall();
			Redis::flushdb();
			return  response()->json(['status' => 'Exist']);
		}
		Redis::flushall();
		Redis::flushdb();
		return  response()->json(['status' => 'Error']);
	}

    public function user_log_summary(Request $request) {// display specific user's locked data in JSON
    	$output = new ConsoleOutput();
        $output->writeln("Personal Lock Data info");

        if(!empty($request->user_id) && $request->header('X-API-KEY')!= null) { // if api key is present in Header){        	
        	$user_cnt = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->count();
        	//$output->writeln($user_cnt);
        	if($user_cnt > 0) {
	        	$user = User::where(['id' => $request->user_id, 'api_token' => $request->header('X-API-KEY')])->first();
	        	if ($user->can('edit-personal')) {// verifies if user has permission

	        		$content = []; $json = [];

	        		$output->writeln("Confirmed");
			        
	        		if(!empty($request->start_date) && !empty($request->end_date)) {/* If start & end date is not empty */
	        			if($request->start_date == $request->end_date) {
	        				$startDate = $endDate = $request->start_date;
	        			} else {
		        			if((int)date('w', strtotime($request->start_date) - 1 ) % 7 > 0) { /* Get start of the week*/
		        				$days = "-" . ((int)date('w', strtotime($request->start_date) - 1) % 7) . " day";
		        				$startDate = date('Y-m-d',strtotime($days, strtotime($request->start_date)));
		        			} else {
		        				$startDate = $request->start_date;
		        			}

		        			if((int)date('w', strtotime($request->end_date) - 1) % 7 < 6) {/* Get end date of that respective week i.e. of Saturday */
		        				$days = ((int)date('w', strtotime($request->end_date)) + 5) . " day"; /* Get last date of that week */
		        				$endDate = date('Y-m-d',strtotime($days, strtotime($request->end_date)));
		        			} else {
		        				$endDate = $request->end_date; /* This is the last date of the week */
		        			}
		        		}
	        		}

			        if(empty($request->start_date) && empty($request->end_date)) /* If date range is not specified */
			        	return Locked_Data::where('user_id',$request->user_id)->get();
			        else if(empty($request->start_date))/* Start date is empty */
			        	return Locked_Data::where([['user_id',$request->user_id], ['work_date', '<=', $request->end_date],])->get();
			        else if(empty($request->end_date))/* End date is empty */
			        	return Locked_Data::where([['user_id',$request->user_id], ['work_date', '>=', $request->start_date],])->get();
			        else{
			        	$datas = Locked_Data::where('user_id',$request->user_id)->whereBetween('work_date',[$startDate, $endDate])->orderBy('work_date', 'ASC')->get();
			        	
			        	if(sizeof($datas) > 0) {
			        		//$output->writeln($datas[sizeof($datas) - 1]["work_date"]);

				        	if ((int)date_diff(date_create(),date_create($datas[sizeof($datas) - 1]["work_date"]))->format("%a") == 0 && ($datas[sizeof($datas) - 1]["total_time"] == null || $datas[sizeof($datas) - 1]["total_time"] == "")) { /* Get Current End_Time & Total_time */
				        		$datas[sizeof($datas) - 1]["end_time"] = date('Y-m-d H:i:s',strtotime('+5 hour +30 minute'));
								$datas[sizeof($datas) - 1]["total_time"] = $this->getTimeDifference($datas[sizeof($datas) - 1]["start_time"], date('Y-m-d H:i:s',strtotime('+5 hour +30 minute')));
				        	}

				        	
				        	foreach ($datas as $data) {
				        		if (sizeof($content) == 0) {
				        			$content["week"] = (int)(date_diff(date_create($startDate),date_create($data->work_date))->format("%a") / 7) + 1;
				        			$content["data"] = array($data);
				        		} else if($content["week"] == (int)(date_diff(date_create($startDate),date_create($data->work_date))->format("%a") / 7) + 1) {
				        			array_push($content["data"], $data);
				        		} else {
				        			array_push($json, $content);
				        			$content["week"] = (int)(date_diff(date_create($startDate),date_create($data->work_date))->format("%a") / 7) + 1;
				        			$content["data"] = array($data);
				        		}
				        		//$data->week = (int)(date_diff(date_create($startDate),date_create($data->work_date))->format("%a") / 7) + 1; /* Get the week of that date */
				        	}
				        	array_push($json, $content);
			        	}
			        	return response()->json($json);
			        }

			    } else {
			    	return response()->json(['status' => 'Error', 'msg' => 'Permission Denied'], 403);
			    }
			} else {
				return response()->json(['status' => 'Error', 'msg' => 'Invalid User ID'], 401);
			}
	    } else {
			//$output->writeln("In else");
	    	return response()->json(['status' => 'Error', 'msg' => 'Parameters not fullfilled'], 400);
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
			    	return response()->json(['status' => 'Error', 'msg' => 'Permission Denied'], 403);
			    }
			} else {
				return response()->json(['status' => 'Error', 'msg' => 'Invalid User ID'], 401);
			}
	    } else {
			//$output->writeln("In else");	    	
	    	return response()->json(['status' => 'Error', 'msg' => 'Required parameters not satisfied'], 400);
	    }
    }

    public function getTimeDifference($time1, $time2) {
    	$output = new ConsoleOutput();
    	if(substr_count($time1, ' ') > 0) { /* If $time1 is in DateTime format */
    		$t1 = explode(' ', $time1);
    		$t1 = explode(':', $t1[1]);
    	} else /* If $time1 is in Time Format */
    		$t1 = explode(':', $time1);
    	
    	if(substr_count($time2, ' ') > 0) { /* If $time2 is in DateTime Format */
	    	$t2 = explode(' ', $time2);
	    	$t2 = explode(':', $t2[1]);
	    } else /* If $time1 is in Time Format */
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