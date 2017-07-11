<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentType extends Model
{
    
    public function clientId(){
        return $this->belongsTo(Client::class);
    }
}
