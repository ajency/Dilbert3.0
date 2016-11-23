<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole//Model
{
    //

    public function user() {
    	return $this->hasMany(User::class);
    }

    public function permission() {
    	return $this->hasMany(Permission::class);
    }
}
