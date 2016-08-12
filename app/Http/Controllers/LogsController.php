<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Log;

class LogsController extends Controller
{
    public function index() {
    	return;
    }

    public function viewPersonal(Request $request) {
    	//$log = Log::where('user_id',auth()->user()->id)->get();
    	$logs = Log::where(['user_id'=> $request->user_id])->where('work_date', $request->date)->get();
        return $logs;
    }

    public function newlog(Request $request) {
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
        //$logs = Log::where([['user_id',1],['work_date',"2016-08-11"]])->get();
        // $logs = Log::where(['user_id'=> '1', 'work_date' => "2016-08-11"])->get();
        $logs = Log::where(['user_id'=> 1])->where('work_date', "2016-08-11")->get();
        return $logs;
    }
}
