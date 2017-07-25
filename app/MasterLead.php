<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterLead extends Model
{
    protected $guarded = ['id'];
    protected $table = "master_leads";
}
