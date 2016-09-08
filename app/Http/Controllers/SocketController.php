<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Request;
use LRedis;

class SocketController extends Controller {
	public function newStat() { // get data from view
		event(new App\Events\EventName());
    	return "event fired";
	}

	public function showStat() { // update the view
		$redis = LRedis::connection();
		$data = ['message' => Request::input('message'), 'user' => Request::input('user')];
		$redis->publish('message', json_encode($data));
		return response()->json([]);
	}
}
