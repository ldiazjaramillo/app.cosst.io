<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $table = "opportunities";

    protected $guarded = ['id'];

    protected $agents = [
            1 => [
                0 => [ "name" => "Mark Angeles", "calendar" => "https://calendly.com/m-angeles", "email" => "m.angeles@jobtarget.com", "phone" => "1 (860) 288-5439"],
                1 => [ "name" => "Ian Kukulka", "calendar" => "https://calendly.com/i-kukulka", "email" => "i.kukulka@jobtarget.com", "phone" => "1 (860) 288-5444"],
                2 => [ "name" => "Rob Prest", "calendar" => "https://calendly.com/r-prest", "email" => "r.prest@jobtarget.com", "phone" => "1 (860) 288-5433"],
                3 => [ "name" => "Jerry Vissers", "calendar" => "https://calendly.com/j-vissers", "email" => "j.vissers@jobtarget.com", "phone" => "1 (860) 288-5441"]
            ]    
        ];
    
    protected $status_options = [
        1 => "DB registered",
        2 => "Event scheduled",
        3 => "Not interested",
        4 => "Meeting confirmed",
        5 => "Meeting held",
        6 => "Meeting cancelled",
        7 => "Meeting rescheduled",
        8 => "No show",
        9 => "Not qualify"
    ];
    protected $tzlist = [
        'UTC' => 'UTC',
        'America/New_York' => 'EST',
        'America/Chicago' => 'CST',
        'America/Denver' => 'MST',
        'America/Los_Angeles' => 'PST',
        'America/Puerto_Rico' => 'AST',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getEventTimeAttribute(){
        if($this->date){
            $date = \Carbon\Carbon::parse($this->date)->tz($this->timezone);
            if($date->isToday()) return "Today at ".$date->format("h:i A")." ".$this->tzlist[$date->tzName];
            else return $date->toDayDateTimeString()." ".$this->tzlist[$date->tzName];
        }else{
            return "N/A";
        }
    }

    public function getTodayTimeAttribute(){
        if($this->date){
            $date = \Carbon\Carbon::parse($this->date)->tz($this->timezone);
            return $date->format("h:i A")." ".$this->tzlist[$date->tzName];
        }else{
            return "N/A";
        }
    }

    public function getEventDateAttribute(){
        if($this->date){
            $date = \Carbon\Carbon::parse($this->date)->tz($this->timezone);
            return $date->toDateTimeString()." ".$this->tzlist[$date->tzName];
        }else{
            return "N/A";
        }
    }

    public function getGustoAgentAttribute(){
        if($this->agent_id){
            return $this->agents[$this->type_id][$this->agent_id]['name'];
        }else{
            return "N/A";
        }
    }

    public function getVfAgentAttribute(){
        if($this->user_id){
            $user = \App\User::find($this->user_id);
            if($user) return $user->name;
            else return "N/A";
        }else{
            return "N/A";
        }
    }

    public function getStatusNameAttribute(){
        return $this->status_options[$this->status];
    }

    public function getStatusOptionsAttribute(){
        return $this->status_options;
    }

    public function getAgentsOptionsAttribute(){
        if($this->type_id) return $this->agents[$this->type_id];
        else return [];
    }

    public function getTzlistOptionsAttribute(){
        return $this->tzlist;
    }

    public function getAgentsByType(){
        return \App\User::where('agent_type_id', $this->type_id)->where('role_id', 3)->orderBy('name')->get();
    }
}
