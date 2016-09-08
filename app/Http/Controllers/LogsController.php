<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Log;

use Symfony\Component\Console\Output\ConsoleOutput;

class LogsController extends Controller
{
    // for app
    public function index() {
    	return;
    }

    public function viewPersonal(Request $request) {
    	$logs = Log::where(['user_id'=> $request->user_id])->where('work_date', $request->date)->get();
        return $logs;
    }

    public function newlog(Request $request) { // gets today's Logs
    	$log = new Log;

    	$log->work_date = date("Y-m-d");
    	$log->cos = $request->cos;
    	$log->user_id = $request->user_id;
    	$log->from_state = $request->from_state;
    	$log->to_state = $request->to_state;
    	$log->ip_addr = $request->ip_addr;
    	$log->save();

    	return $log;
    }

    public function viewAbc() {
        $logs = Log::where(['user_id'=> 1])->where('work_date', "2016-08-17")->get();
        return $logs;
    }

    public function trial() {//Request $request) {
        $output = new ConsoleOutput();
        //$output->writeln($request->socket);
        $output->writeln(str_random(60));
        //return $request->socket;
    }
}
