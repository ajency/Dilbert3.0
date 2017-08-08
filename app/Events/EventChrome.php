<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Organization;
use App\Log;
use App\Locked_Data;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LockedDataController;
use Request;
use Redis;
use Symfony\Component\Console\Output\ConsoleOutput;

class EventChrome extends Event implements ShouldBroadcast {
    use SerializesModels;

    //public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($redis_list) {

        // $output = new ConsoleOutput();

        // $output->writeln("Socket id - Event Chrome");

        //File::append($_SERVER['OUTPUT_FILE_PATH'], json_encode($redis_list)."\n");
        if(isset($redis_list->auth) && isset($redis_list->socket_id)){ // unauthorized data entry
            //$output->writeln("Invalid Authentication");
            $this->data = array(
                'socket_status' => "invalid_auth", 'socket_id' => $redis_list->socket_id
            );
        }

        if(isset($redis_list->org_id)){ // Broadcast if any organization is deleted
            //$output->writeln("Socket + org_id confirmed");
            $this->data = array(
                'socket_status' => "org_id_deleted", 'org_id' => $redis_list->org_id
            );
        }

        if(isset($redis_list->user_id) && $redis_list->user_id > 0) { // if user is not disconnected i.e. connected to socket
            //$output->writeln("Socket id - Event Chrome inside if");
            //$output->writeln($redis_list->socket_id);

            //$output->writeln(gettype($redis_list->user_id));
            //$output->writeln($redis_list->user_id);
            if(isset($redis_list->socket_id)) { // checks if user has socket ID
                //
                //$output->writeln("Socket + user id confirmed");

                $user = User::where('id', $redis_list->user_id)->get();

                // Get User's TimeZone
                $userTimeZone = $user[0]->timeZone;

                // Get the TimeZone value from the whole content
                $tempTimeZone = explode(':',explode(')',explode('GMT', $userTimeZone)[1])[0]); // Split using GMT, ) & : from [<Country> ({+/-}hr:min)]

                // Check the time slot & get Hours, Minutes & +/- sign
                if(strpos($tempTimeZone[0], "+") !== False) { /* It does have '+' timezone*/
                    $zoneValues = explode("+", $tempTimeZone[0]);
                    $hr = $zoneValues[1];
                    $min = $tempTimeZone[1];
                    $sign = '+';
                } elseif(strpos($tempTimeZone[0], "-") !== False) { /* It does have '-' timezone*/
                    $zoneValues = explode("-", $tempTimeZone[0]);
                    $hr = $zoneValues[1];
                    $min = $tempTimeZone[1];
                    $sign = '-';
                } else { /* No TimeZone assigned */
                    $hr = '00';
                    $min = '00';
                    $sign = '+';
                }

                /* Get current System UTC+0:0 time & increment w.r.t that employee's Timezone */
                $x = strtotime($sign . $hr . " hour " . $sign . $min . " min", strtotime(date('Y-m-d H:i:s')));
                $timeZone = date("H:i", $x); // Get the new Time in Hr:Min format

                // $startTimeRestriction = strtotime($sign.$hr." hour ".$sign.$min." min", strtotime("04:00:00")) // Restrict the Start Time from 09:30 AM IST

                if (strtotime($timeZone) < strtotime("09:30:00")) { // If the time is less than 09:30 AM, then set the time to 09:30 AM
                    $timeZone = date("H:i", strtotime("09:30:00"));
                }

                //$output->writeln("User count");
                //$output->writeln(count($user));

                if(count($user) > 0){ /* Update the new Socket ID in User's table */
                    if(isset($redis_list->to_state) && $redis_list->to_state == "offline") /*  If User is offline, then delete the SocketID */
                        User::where('id', $redis_list->user_id)->update(['socket_id' => ""]);
                    else { // User is either Idle, active or created a New Session
                        if(isset($redis_list->chrome_app_version) && isset($redis_list->chrome_browser_version))
                            User::where('id', $redis_list->user_id)->update(['socket_id' => $redis_list->socket_id, 'app_version' => $redis_list->chrome_app_version, 'browser_version' => $redis_list->chrome_browser_version]);// Update with new socket id, chrome app version & chrome browser version
                        else
                            User::where('id', $redis_list->user_id)->update(['socket_id' => $redis_list->socket_id]);// Update with new socket id
                    }
                    //$output->writeln("Socket id + user id -> update");
                }

                //$output->writeln("Organization process complete");
                $org_ipList = Organization::where('id',$user[0]->org_id)->first(); // Get the Details of that Organization

                if(count($org_ipList)) { /* Check if that organization exist */
                    if(Log::where(['user_id' => $redis_list->user_id, 'work_date' => date("Y-m-d"), 'cos' => $timeZone, 'from_state' => $redis_list->from_state, 'to_state' => $redis_list->to_state])->count() <= 0) { /* If the new log doesn't exist in the table, then enter the data */ // -> To avoid recursive data

                        //$output->writeln(count($org_ipList));
                        $org_ipList = unserialize($org_ipList->ip_lists);/* Unserialize from JSON to array */ /* Get all the IP List assigned by that Organization */

                        if(count($org_ipList) > 0 && in_array($redis_list->ip_addr, $org_ipList)) { /* If ip addresses > 0 & user's ip exists in the list, then save the log */
                            $log = new Log;

                            $log->work_date = date("Y-m-d");
                            $log->cos = $timeZone;
                            $log->user_id = $redis_list->user_id;
                            $log->from_state = $redis_list->from_state;
                            $log->to_state = $redis_list->to_state;
                            $log->ip_addr = $redis_list->ip_addr;
                            $log->save();

                            /* If the user logged in for the 1st time, then add that log to Locked_Data table, just to track @ what time u logged in */
                            if(Locked_Data::where(['user_id' => $log->user_id, 'work_date' => $log->work_date])->count() <= 0) { // If count is 0, then it's today's 1st entry

                                //check if the time is past 11am
                                if(date("H") >= 11 & date("i") > 0) {
                                  //if past 11am send a mail
                                  $data = array(date("H:i:s"));
                                	Mail::send('lateMail', $data, function($message)
                                	{
                                    $message->from('dilbert@ajency.in');
                                    $message->to('sharang@ajency.in');
                                    $message->cc('sujit@ajency.in');
                                    $message->subject('Late Login');
                                	});
                                }

                                $locking_today_data = new Locked_Data;
                                $locking_today_data->user_id = $log->user_id;
                                $locking_today_data->work_date = $log->work_date;
                                $locking_today_data->start_time = date("Y-m-d H:i:s",strtotime($log->work_date.' '.$log->cos));
                                $locking_today_data->status = "Present"; // mark the status as Present
                                $locking_today_data->save();
                            }

                            if($redis_list->to_state == "New Session" || $redis_list->to_state == "active") { // If it is a New Session or Active Session
                                if(Locked_Data::where(['user_id' => $redis_list->user_id, 'work_date' => $log->work_date])->count() > 0) { // If count > 0, then it exist
                                    Locked_Data::where(['user_id' => $redis_list->user_id, 'work_date' => $log->work_date])->update(["end_time" => null, "total_time" => null]);
                                }
                            }

                            if($redis_list->to_state == "offline" || $redis_list->to_state == "idle") { // user goes offline or idle
                                if(Locked_Data::where(['user_id' => $redis_list->user_id, 'work_date' => $log->work_date])->count() > 0) { // If count > 0, then today's is not 1st entry && User has gone Offline or idle
                                    /* Update Summary/ Locked_Data Table with new Offline state */
                                    $userLocked_data = Locked_Data::where(['user_id' => $redis_list->user_id, 'work_date' => $log->work_date])->get();
                                    Locked_Data::where(['user_id' => $redis_list->user_id, 'work_date' => $log->work_date])->update(["end_time" => date("Y-m-d H:i:s",strtotime($log->work_date.' '.$timeZone)), "total_time" => (new LockedDataController)->getTimeDifference($userLocked_data[0]->start_time, strftime(date("Y-m-d H:i:s",strtotime($log->work_date.' '.$timeZone))))]);
                                }
                            }

                            Redis::lpop('test-channels');// remove the current-log element from queue
                            $logs = Log::where(['user_id' => $redis_list->user_id])->where('work_date', date("Y-m-d"))->get();

                            $this->data = array(
                                'socket_status' => "return", 'id' => $redis_list->user_id, 'socket_id' => $redis_list->socket_id, 'content' => $logs
                            );
                        } else { /* User is not working @ office */
                            Redis::lpop('test-channels');// remove the current-log element from queue
                            if($redis_list->to_state == "New Session") { /* Display this message only if it's a New Session*/
                                $this->data = array(
                                    'socket_status' => "no_socket_id", 'id' => $redis_list->user_id, 'socket_id' => "error", 'error_msg' => "External IP address", 'error_display' => "Yes"
                                );
                            } else { /* Don't display message to client */
                                $this->data = array(
                                    'socket_status' => "no_socket_id", 'id' => $redis_list->user_id, 'socket_id' => "error", 'error_msg' => "External IP address", 'error_display' => "No"
                                );
                            }
                        }
                    } else { /* Data/Log exist in the table */
                        Redis::lpop('test-channels');
                        $this->data = array(
                            'socket_status' => "data/log exist", 'id' => $redis_list->user_id, 'socket_id' => $redis_list->socket_id
                        );
                    }
                } else {
                    $output->writeln("Organization doesn't exist");
                    Redis::lpop('test-channels');// remove the current-log element from queue
                    $this->data = array(
                        'socket_status' => "no_socket_id", 'id' => $redis_list->user_id, 'socket_id' => "error", 'error_msg' => "Sorry!! IP list not found for verification", 'error_display' => "No"
                    );
                }

            } else { //no socket ID
                $output->writeln("Socket id not confirmed");
                Redis::lpop('test-channels');// remove the current-log element from queue
                $this->data = array(
                    'socket_status' => "no_socket_id", 'id' => $redis_list->user_id, 'socket_id' => "error", 'error_msg' => "No socket ID registered", 'error_display' => "No"
                );
            }
        } else if(isset($redis_list->socket_id) && isset($redis_list->user_id) && $redis_list->user_id == 0){  // User closed chrome app or app got disconnected
            // user closed chrome app
            //$output->writeln("Socket id + !user id");

            $user = User::where('socket_id', $redis_list->socket_id)->get();//update(['socket_id' => '']);
            //$output->writeln("Socket id + !user id -> get");

            if(count($user)){ // User exist
                $user_id = $user[0]->id;

                // Get User's TimeZone
                $userTimeZone = $user[0]->timeZone;

                // Get the TimeZone value from the whole content
                $tempTimeZone = explode(':',explode(')',explode('GMT', $userTimeZone)[1])[0]); // Split using GMT, ) & : from [<Country> ({+/-}hr:min)]

                // Check the time slot & get Hours, Minutes & +/- sign
                if(strpos($tempTimeZone[0], "+") !== False) { /* It does have '+' timezone*/
                    $zoneValues = explode("+", $tempTimeZone[0]);
                    $hr = $zoneValues[1];
                    $min = $tempTimeZone[1];
                    $sign = '+';
                } elseif(strpos($tempTimeZone[0], "-") !== False) { /* It does have '-' timezone*/
                    $zoneValues = explode("-", $tempTimeZone[0]);
                    $hr = $zoneValues[1];
                    $min = $tempTimeZone[1];
                    $sign = '-';
                } else { /* No TimeZone assinged */
                    $hr = '00';
                    $min = '00';
                    $sign = '+';
                }

                /* Get current System UTC+0:0 time & increment w.r.t that employee's Timezone */
                $x = strtotime($sign.$hr." hour ".$sign.$min." min", strtotime(date('Y-m-d H:i:s')));
                $timeZone = date("H:i", $x); // Get the new Time in Hr:Min format

                if (strtotime($timeZone) < strtotime("09:30:00")) { // If the time is less than 09:30 AM, then set the time to 09:30 AM
                    $timeZone = date("H:i", strtotime("09:30:00"));
                }

                User::where('id', $user_id)->update(['socket_id' => '']); /* Clear Socket ID */

                //$output->writeln("Socket id + !user id -> New Log");
                $log = new Log;
                $log->user_id = $user_id;
                $log->work_date = date("Y-m-d");
                $log->cos = $timeZone;
                $log->from_state = $redis_list->from_state;
                $log->to_state = $redis_list->to_state;
                $log->ip_addr = $redis_list->ip_addr;
                $log->save();

                if(Locked_Data::where(['user_id' => $user_id, 'work_date' => $log->work_date])->count() > 0) { // If count > 0, then it's today's is not 1st entry
                    /* Update Summary/ Locaked_Data Table with new Offline state */
                    $userLocked_data = Locked_Data::where(['user_id' => $user_id, 'work_date' => $log->work_date])->get();
                    Locked_Data::where(['user_id' => $user_id, 'work_date' => $log->work_date])->update(["end_time" => date("Y-m-d H:i:s",strtotime($log->work_date.' '.$timeZone)), "total_time" => (new LockedDataController)->getTimeDifference($userLocked_data[0]->start_time, strftime(date("Y-m-d H:i:s",strtotime($log->work_date.' '.$timeZone))))]);
                }
                //$output->writeln("Log out log updated");

                Redis::lpop('test-channels');// remove the current-log element from queue
                $this->data = array(
                    'socket_status' => "close", 'id' => $redis_list->user_id, 'socket_id' => $redis_list->socket_id
                );
            } else {
                Redis::lpop('test-channels');// remove the current-log element from queue
                /*$this->data = array(
                    'socket_status' => "close", 'id' => $redis_list->user_id, 'socket_id' => $redis_list->socket_id
                );*/
            }
        } else {
            Redis::lpop('test-channels');// remove the current-log element from queue
            //$output->writeln("Executed none");
        }
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn() {
        //$output = new ConsoleOutput();
        //$output->writeln("Prepare for broadcast");

        $redis = Redis::connection();

        //$output->writeln("Broadcasting");

        return ['test-channel'];
    }
}
