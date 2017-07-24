<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

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

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function getEventTimeAttribute(){
        if($this->date){
            $date = \Carbon\Carbon::parse($this->date, $this->timezone);
            if($date->isToday()) return "Today at ".$date->format("h:i A")." ".$date->tzName;
            else return $date->toDayDateTimeString()." ".$date->tzName;
        }else{
            return "N/A";
        }
    }

    public function getTodayTimeAttribute(){
        if($this->date){
            $date = \Carbon\Carbon::parse($this->date, $this->timezone);
            return $date->format("h:i A")." ".$date->tzName;
        }else{
            return "N/A";
        }
    }

    public function getEventDateAttribute(){
        if($this->date){
            $date = \Carbon\Carbon::parse($this->date, $this->timezone);
            return $date->toDateTimeString()." ".$date->tzName;
        }else{
            return "N/A";
        }
    }

    public function getClientAgentAttribute(){
        if(is_null($this->agent_id)) return "N/A";
        
        $user = \App\User::find($this->agent_id);
        if(is_null($user)) return "N/A";
        else return $user->name; 
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
        return \App\User::where('role_id', 3)->where('client_id', $this->client_id)->orderBy('name')->get()->toArray();
    }

    public function getTzlistOptionsAttribute(){
        return $this->tzlist;
    }

    public function getAgentsByType(){
        $client_id = session()->get('working_client.id');
        return \App\User::where('role_id', 3)->where('client_id', $client_id)->orderBy('name')->get();
    }

    public function getCreationDateAttribute(){
        return Carbon::parse($this->created_at)->tz('America/New_York');
    }
}
