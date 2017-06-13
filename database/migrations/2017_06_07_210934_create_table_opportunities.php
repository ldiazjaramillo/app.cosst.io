<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOpportunities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name', 140);//Client Business Name
            $table->string('contact_name', 140);//Client Contact Name: First, Last
            $table->boolean('decision_maker');//Is this the decision Maker?
            $table->string('contact_phone', 20);//Client Contact Phone:
            $table->string('contact_email');//Client Contact Email:
            $table->string('client_id');//Client Unique ID:
            $table->string('company_state');//Company State (drop down)
            $table->string('company_states');//States you have employees (multiple)
            $table->string('external_account');//Do you have a an external account/bookeeper
            $table->integer('employees_number');//How many employees does the client have
            
            //FORM2
            $table->boolean('certified_payroll')->nullable();//Do you require Certified payroll
            $table->boolean('job_costing')->nullable();//Do you require Job costing
            $table->boolean('paper_checks')->nullable();//Do you require paper checks?
            $table->boolean('schedule_filing')->nullable();//Do you require Schedule H filing (househould, nannies)?
            $table->boolean('farms_filing')->nullable();//Do you require a 943 filing? (farms)
            $table->boolean('require_international')->nullable();//Do you require international? 
            $table->boolean('require_fica')->nullable();//Do you require FICA tip credit tracking?
            $table->boolean('require_garnishment')->nullable();//Do you require garnishment payment remits?

            //FORM3
            $table->string('payroll_process')->nullable();//How do you currently process payroll (yourself OR enter company name)
                                                          //ADP (Run Product), ADP ( other), Paychex, Intuit Online, Intuit Other, Other
            $table->boolean('health_benefits')->nullable();//Do you offer company-sponsored health benefits?
            $table->string('health_broker')->nullable();//If yes who is your broker?
            $table->boolean('consider')->nullable();//Yes/No: would you like to consider Gusto?
            $table->integer('user_id')->nullable();
            $table->integer('type_id')->nullable();//1 = spa_sbiz, 2 = spb_mmfs, 3 = spb_mmpr
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
}
