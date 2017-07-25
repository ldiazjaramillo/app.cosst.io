<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Excel;

class RunImports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run imports from imports table';

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
        $files = \App\Import::where('status', 1);
        if(!$files->count()){
            $this->info("No files to import. Exiting program\n");
            return;
        }
        foreach($files->get() as $file){
            $has_header = $file->ignore_first;
            $model_name = $file->model;
            $model = "\App\\$file->model";
            if($has_header && $model_name == "MasterLead") return $this->handleMasterLeads($file);
            $columns = explode(",", $file->columns);
            $columns[] = 'client_id';
            //dd($columns);
            $file_path = storage_path("app/public/$file->file");
            $client_id = $file->client_id;
            
            $this->info("Reading file: $file_path \n");
            \DB::beginTransaction();
            try{
                $excelFile = Excel::load($file_path, function($reader) use ($has_header, $columns, $client_id, $model, $model_name) {
                    if(!$has_header) $reader->noHeading();
                    $csv_rows = $reader->get();
                    $this->info("Number of rows: ". count($csv_rows)."\n");
                    foreach($csv_rows as $row){
                        $items = $row->toArray();
                        $items[] = $client_id;
                        //dd($items);
                        $new_lead = array_combine( $columns, $items );
                        $lead = $model::create($new_lead);
                        $this->info("New $model_name created. Id: $lead->id \n");
                        //dd( $lead );
                    }
                });
                $file->status=2;
                $file->save();
                \DB::commit();
            }catch (\Exception $e) {
                $this->error("Error occured: ".$e->getMessage()."\n");
                \DB::rollback();
                $this->info("Rollback called \n");
            }
        }
    }

    public function handleMasterLeads($file){
        $this->info("Importing into master_leads table \n");
        $file_path = storage_path("app/public/$file->file");
        $this->info("Reading file: $file_path \n");
        $model_name = $file->model;
        $model = "\App\\$file->model";
        \DB::beginTransaction();
        try{
            $excelFile = Excel::load($file_path, function($reader) use ($model, $model_name) {
                $csv_rows = $reader->get(array('record_id','first_name','last_name','job_title','company','in_person_registration','live_stream_registration','match_status','zoom_individual_id','last_name','first_name','middle_name','salutation','suffix','job_title','job_title_hierarchy_level','job_function','management_level','company_division_name','direct_phone_number','direct_phone_change','email_address','email_change','person_street','person_city','person_state','person_zip','country','source_count','last_updated_date','zoom_company_id','company_name','company_domain_name','company_phone_number','company_street_address','company_city','company_state','company_zip_postal_code','company_country','industry_label','industry_hierarchical_category','secondary_industry_label','secondary_industry_hierarchical_category','revenue_in_000s','revenue_range','employees','employees_range','sic1','sic2','naics1','naics2','titlecode','highest_level_job_function','person_pro_url','encrypted_email_address','email_domain'));
                $this->info("Number of rows: ". count($csv_rows)."\n");
                foreach($csv_rows as $row){
                    $items = $row->toArray();
                    //dd($items);
                    $lead = $model::create($items);
                    $this->info("New $model_name created. Id: $lead->id \n");
                    //dd( $lead );
                }
            });
            $file->status=2;
            $file->save();
            \DB::commit();
        }catch (\Exception $e) {
            $this->error("Error occured: ".$e->getMessage()."\n");
            \DB::rollback();
            $this->info("Rollback called \n");
        }
    }
}
