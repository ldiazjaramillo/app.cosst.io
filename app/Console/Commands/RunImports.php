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
            $columns = explode(",", $file->columns);
            $columns[] = 'client_id';
            //dd($columns);
            $file_path = storage_path("app/public/$file->file");
            $client_id = $file->client_id;
            $model_name = $file->model;
            $model = "\App\\$file->model";
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
                \DB::rollback();
            }
        }
    }
}
