<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $guarded = ['id'];
    
    public function clientId(){
        return $this->belongsTo(Client::class);
    }
}
