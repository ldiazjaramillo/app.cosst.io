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
            $table->string('company_name', 140);//Client Biz Name
            $table->string('contact_name', 140);//Client Contact Name
            $table->string('contact_position', 140);//Client Job Title
            $table->string('contact_phone', 20);//Client phone
            $table->string('contact_email');//Client email
            $table->string('contact_street');//Client Address street
            $table->string('contact_city');//Client address city
            $table->string('contact_state');//Client address state
            $table->integer('employees_number');//How many employees does the client have
            $table->integer('open_positions')->nullable();//how many open positions do you currently have - if less than 5 (doesn't qualify!)
            $table->boolean('decision_maker');//Is this the decision Maker?
            $table->string('client_id');//Client Unique ID:
            $table->string('notes')->nullable();//Notes
            $table->dateTime('date')->nullable();
            $table->string('timezone')->nullable();
            $table->integer('status')->default(1);
            $table->integer('agent_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('type_id')->nullable();//1 = spa_sbiz, 2 = spb_mmfs, 3 = spb_mmpr
            $table->integer('lead_id')->nullable();
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
