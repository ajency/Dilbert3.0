<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function user() {
    	return $this->belongsTo(User::class);// User will have multiple logs entered
    }
}
