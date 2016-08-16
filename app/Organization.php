<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name', 'logo', 'default_tz', 'alt_tz', 'ip_lists', 'ip_status',
    ];

    protected $casts = [
    	'alt_tz' => 'array',
    	'ip_lists' => 'array',
    	'ip_status' => 'array',
    ];

    /*protected $hidden = [
        'domain',
    ];*/
    
    public function user() {
    	return $this->hasMany(User::class);// An organization can have many Members under it
    }
}
