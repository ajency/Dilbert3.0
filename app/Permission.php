<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission//Model
{
    //
    public function role() {
    	return $this->belongsTo(Role::class);
    }
}
