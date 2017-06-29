<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\App\Opportunity;
use App\Lead;

class RestoreClientsID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore:client_ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore empty clients ids';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Getting all opportunities \n");
        $opportunities = \App\Opportunity::where('client_id', '')->orWhere('client_id', null)->get();
        if(!$opportunities->count()){
            $this->info("No opportunities with empty client_id found \n"); return;
        }
        $count = 0;
        foreach($opportunities as $opportunity){
            echo ".";
            $lead = \App\Lead::where('email', $opportunity->contact_email)->first();
            $client_id = null;
            if($lead){
                if($lead->zoom_id) $client_id = $lead->zoom_id;
                else $client_id = $lead->zoom_company_id;
            }
            if($opportunity->client_id != $client_id ){
                $opportunity->client_id = $client_id;
                $opportunity->save();
                $this->info("Opportunity for lead $opportunity->contact_email updated\n");
                $count++;
            }
        }
        echo "\n";
        $this->info("Opportunities found: ".$opportunities->count()." \nOpportunities fixed: $count \n");

    }
}
