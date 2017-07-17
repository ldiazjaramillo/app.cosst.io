<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getUsernameAttribute(){
        return explode('@', $this->email)[0];
    }

    public function agentTypeId(){
        return $this->belongsTo(AgentType::class);
    }

    public function clientId(){
        return $this->belongsTo(Client::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function role(){
        return $this->belongsTo(\TCG\Voyager\Models\Role::class);
    }
}
