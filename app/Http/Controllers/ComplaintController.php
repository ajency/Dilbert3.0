<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Complaint;

class ComplaintController extends Controller {
    public function issueFaced(Request $request) { // new user but same domain, then asking confirmation
	    try {
	    	$this->validate($request, [
	    		'queryName' => 'required',
	    		'queryEmail' => 'required',
	    		'issueOption' => 'required'
	    	]);
	        
	        $complaint = new Complaint;
	        $complaint->name = $request->queryName;
	        $complaint->email = $request->queryEmail;
	        $complaint->issue = $request->issueOption;
	        $complaint->issue_content = $request->issueTextArea;
	        $complaint->save();
	        
	        return redirect()->to('/login');
	    } catch (Exception $e) {
	        return redirect()->back();
	    }
	}
}
