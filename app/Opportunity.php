<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $table = "opportunities";

    protected $guarded = ['id'];

    protected $agents = [
            1 => [
                0 =>['name'=>'Joey B', 'email'=>'joey.brown@gusto.com', 'calendar'=>'calendly.com/joey-brown'],
                1 =>['name'=>'Candace S', 'email'=>'candace.sake@gusto.com', 'calendar'=>'calendly.com/candace-sake'],
                2 =>['name'=>'Rene E', 'email'=>'rene.etter-garrette@gusto.com', 'calendar'=>'calendly.com/rene-gusto'],
                3 =>['name'=>'Donny T', 'email'=>'donny.tachis@gusto.com', 'calendar'=>'calendly.com/donny-tachis'],
            ],
            2 => [
                0=>['name'=>'Brandon Boyle', 'email'=>'brandon.boyle@gusto.com', 'calendar'=>'calendly.com/brandon_gusto'],
                1=>['name'=>'Michael Reddish', 'email'=>'michael.reddish@gusto.com', 'calendar'=>'calendly.com/michael-reddish'],
                2=>['name'=>'Chad Benoit', 'email'=>'chad@gusto.com', 'calendar'=>'calendly.com/chad_zp'],
                3=>['name'=>'Johnny Wells', 'email'=>'johnny.wells@gusto.com', 'calendar'=>'calendly.com/johnnywells'],
                4=>['name'=>'Kabir Chopra', 'email'=>'kabir.chopra@gusto.com', 'calendar'=>'calendly.com/kabirchopra'],
            ],
            3 => [
                0=>['name'=>'Yekta Tehrani', 'email'=>'yekta.tehrani@gusto.com', 'calendar'=>'calendly.com/yekta-tehrani'],
                1=>['name'=>'Matt Worden', 'email'=>'matt.worden@gusto.com', 'calendar'=>'calendly.com/matt-worden'],
                2=>['name'=>'Matthew Baker', 'email'=>'matthew.baker@gusto.com', 'calendar'=>'calendly.com/matthewbaker'],
            ]
        ];
    
    protected $status_options = [
        1 => "DB Registered",
        2 => "Event Scheduled"
    ];

    public function getEventTimeAttribute(){
        if($this->date){
            $date = \Carbon\Carbon::parse($this->date, $this->timezone);
            return $date->toTimeString()." ".$date->tzName;
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

    public function getGustoAgentAttribute(){
        if($this->agent_id){
            return $this->agents[$this->type_id][$this->agent_id]['name'];
        }else{
            return "N/A";
        }
    }

    public function getStatusNameAttribute(){
        return $this->status_options[$this->status];
    }
}
