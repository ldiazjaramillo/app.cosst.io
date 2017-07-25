<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Excel;

class MasterLeadImports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:master';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run imports for master_leads table';

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
        $folder_path = storage_path("app/zoominfo/");
        $this->info("Reading directory $folder_path \n");
        if ($gestor = opendir($folder_path)) {
            while (false !== ($entry = readdir($gestor))) {
                if ($entry != "." && $entry != "..") {
                    \DB::beginTransaction();
                    try{
                        $excelFile = Excel::load($folder_path.$entry, function($reader) use ($entry){
                            $csv_rows = $reader->get(array('record_id','first_name','last_name','job_title','company','in_person_registration','live_stream_registration','match_status','zoom_individual_id','last_name','first_name','middle_name','salutation','suffix','job_title','job_title_hierarchy_level','job_function','management_level','company_division_name','direct_phone_number','direct_phone_change','email_address','email_change','person_street','person_city','person_state','person_zip','country','source_count','last_updated_date','zoom_company_id','company_name','company_domain_name','company_phone_number','company_street_address','company_city','company_state','company_zip_postal_code','company_country','industry_label','industry_hierarchical_category','secondary_industry_label','secondary_industry_hierarchical_category','revenue_in_000s','revenue_range','employees','employees_range','sic1','sic2','naics1','naics2','titlecode','highest_level_job_function','person_pro_url','encrypted_email_address','email_domain'));
                            $this->info("Number of rows: ". count($csv_rows)." on file $entry \n");
                            $count = 0;
                            foreach($csv_rows as $row){
                                $items = $row->toArray();
                                //dd($items);
                                $lead = \App\MasterLead::create($items);
                                $count++;
                                //dd( $lead );
                            }
                        });
                        \DB::commit();
                        $this->info("New MasterLead created for $entry\n");
                    }catch (\Exception $e) {
                        $message = $e;
                        $this->error("Error occured: $message\n");
                        \DB::rollback();
                        $this->error("Rollback called on file: $entry \n");
                        $fp = fopen(storage_path("app/zoominfo/error.log"), 'w');
                        fwrite($fp, $message);
                        fwrite($fp, " On file: $entry \n");
                        fwrite($fp, "\n");
                        fclose($fp);
                    }
                }
            }
            closedir($gestor);
        }
        return;
    }
}
