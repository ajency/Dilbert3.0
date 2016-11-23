<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable //\Eloquent {//Authenticatable {
{
    use EntrustUserTrait; // add this trait to your user model
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getId() {
      return $this->id;
    }

    public function role() {
        //return $this->belongsTo('App\Models\Role');
        return $this->belongsTo(Role::class);
    }

    public function organization() {
        //return $this->belongsTo('App\Models\Organization');
        return $this->belongsTo(Organization::class);
    }
}