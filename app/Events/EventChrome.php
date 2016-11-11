<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\User;
use App\Organization;
use App\Log;

use App\Http\Controllers\Controller;
use Request;
use Redis;
use Symfony\Component\Console\Output\ConsoleOutput;

class EventChrome extends Event implements ShouldBroadcast {
    use SerializesModels;

    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($redis_list) {
        
        $output = new ConsoleOutput();

        $output->writeln("Socket id - Event Chrome");
        
        if(isset($redis_list->auth) && isset($redis_list->socket_id)){ // unauthorized data entry
            $output->writeln("Invalid Authentication");
            $this->data = array(
                'socket_status' => "invalid_auth", 'socket_id' => $redis_list->socket_id
            );
        }

        if(isset($redis_list->org_id)){ // Broadcast if any organization is deleted
            $output->writeln("Socket + org_id confirmed");
            $this->data = array(
                'socket_status' => "org_id_deleted", 'org_id' => $redis_list->org_id
            );
        }

        if(isset($redis_list->user_id) && $redis_list->user_id > 0) { // if user is not disconnected
            $output->writeln("Socket id - Event Chrome inside if");
            $output->writeln($redis_list->socket_id);

            $output->writeln(gettype($redis_list->user_id));
            $output->writeln($redis_list->user_id);
            if(isset($redis_list->socket_id)) { // checks if user has socket ID
                //
                $output->writeln("Socket + user id confirmed");
                //$output->writeln($redis_list);
                //$org = Organization::where('id',$redis_list->org_id)->get();
                //$output->writeln($redis_list->org_id);
                /*if(count($org) > 0){ // verify if Organizartion exist or is new
                    $org[0]->alt_tz = unserialize($org[0]->alt_tz);
                    $org[0]->ip_lists = unserialize($org[0]->ip_lists);
                    $org[0]->ip_status = unserialize($org[0]->ip_status);
                    //$org[0]->ip = $_SERVER['REMOTE_ADDR'];
                    $output->writeln("Organization confirmed");
                } else {
                    return 0;
                }*/

                $user = User::where('id', $redis_list->user_id)->get();
                $output->writeln("User count");
                $output->writeln(count($user));

                if(count($user) > 0){
                    $user = User::where('id', $redis_list->user_id)->update(['socket_id' => $redis_list->socket_id]);// clear the socket id
                    $output->writeln("Socket id + user id -> update");
                }
                
                $output->writeln("Organization process complete");

                $log = new Log;

                $log->work_date = date("Y-m-d");
                $log->cos = $redis_list->cos;
                $log->user_id = $redis_list->user_id;
                $log->from_state = $redis_list->from_state;
                $log->to_state = $redis_list->to_state;
                $log->ip_addr = $redis_list->ip_addr;
                $log->save();

                if($redis_list->to_state == "offline") { // user goes offline
                    User::where('id', $redis_list->user_id)->update(['socket_id' => ""]);
                    $output->writeln("Socket id + !user id -> update socket clear");
                }

                $output->writeln("New log saved");
                Redis::lpop('test-channels');// remove the current-log element from queue
                $logs = Log::where(['user_id' => $redis_list->user_id])->where('work_date', date("Y-m-d"))->get();
                $output->writeln("Got all log");

                $this->data = array(
                    'socket_status' => "return", 'id' => $redis_list->user_id, 'socket_id' => $redis_list->socket_id, 'content' => $logs
                );
                $output->writeln("sending");
            } else { //no socket ID
                $output->writeln("Socket id not confirmed");
                Redis::lpop('test-channels');// remove the current-log element from queue
                $this->data = array(
                    'socket_status' => "no_socket_id", 'id' => $redis_list->user_id, 'socket_id' => "error"
                );
            }
        } else if(isset($redis_list->socket_id) && isset($redis_list->user_id) && $redis_list->user_id == 0){  // User closed chrome app or app got disconnected
            // user closed chrome app
            $output->writeln("Socket id + !user id");
            
            $user = User::where('socket_id', $redis_list->socket_id)->get();//update(['socket_id' => '']);
            $output->writeln("Socket id + !user id -> get");
            
            //User::where('socket_id', $redis_list->socket_id)->update(['socket_id' => ""]);
            //$user->update(['socket_id' => '']);// clear the socket id
            //$output->writeln("Socket id + !user id -> update");
            //$user_id = $user[0]->id;// get the user ID for Log entry
            //$output->writeln($user);
            $user_id = $user[0]->id;

            $log = new Log;
            $output->writeln("Socket id + !user id -> New Log");
            $log->work_date = date("Y-m-d");
            $log->cos = $redis_list->cos;
            $log->user_id = $user_id;
            $log->from_state = $redis_list->from_state;
            $log->to_state = $redis_list->to_state;
            $log->ip_addr = $redis_list->ip_addr;
            $log->save();

            $output->writeln("Log out log updated");

            Redis::lpop('test-channels');// remove the current-log element from queue
            $this->data = array(
                'socket_status' => "close", 'id' => $redis_list->user_id, 'socket_id' => $redis_list->socket_id
            );
        } else {
            $output->writeln("Executed none");
        }
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn() {
        $output = new ConsoleOutput();
        $output->writeln("Prepare for broadcast");
        
        $redis = Redis::connection();

        $output->writeln("Broadcasting");
        
        return ['test-channel'];
    }
}
