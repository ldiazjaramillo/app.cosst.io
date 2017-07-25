<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {//Record ID,First Name,Last Name,Job Title,Company,In-Person Registration,Live Stream Registration,Match status,Zoom Individual ID,Last name,First name,Middle name,Salutation,Suffix,Job title,Job title hierarchy level,Job Function,Management Level,Company division name,Direct Phone Number,Direct Phone Change,Email address,Email Change,Person Street,Person City,Person State,Person Zip,Country,Source count,Last updated date,Zoom company ID,Company name,Company domain name,Company phone number,Company Street address,Company City,Company State,Company ZIP/Postal code,Company Country,Industry label,Industry hierarchical category,Secondary industry label,Secondary industry hierarchical category,Revenue (in 000s),Revenue Range,Employees,Employees Range,SIC1,SIC2,NAICS1,NAICS2,TitleCode,Highest Level Job Function,Person Pro URL,Encrypted Email Address,Email Domain
        Schema::create('master_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('record_id')->nullable();
            $table->string('first_name', 80)->nullable();
            $table->string('last_name', 80)->nullable();
            $table->string('middle_name', 40)->nullable();
            $table->string('salutation', 20)->nullable();
            $table->string('suffix', 140)->nullable();
            $table->string('job_title', 280)->nullable();
            $table->string('company')->nullable();
            $table->string('in_person_registration')->nullable();
            $table->string('live_stream_registration')->nullable();
            $table->string('match_status')->nullable();
            $table->string('zoom_individual_id')->nullable();
            $table->string('job_title_hierarchy_level')->nullable();
            $table->string('job_function')->nullable();
            $table->string('management_level')->nullable();
            $table->string('company_division_name')->nullable();
            $table->string('direct_phone_number')->nullable();
            $table->string('direct_phone_change')->nullable();
            $table->string('email_address')->nullable();
            $table->string('email_change')->nullable();
            $table->string('person_street')->nullable();
            $table->string('person_city')->nullable();
            $table->string('person_state')->nullable();
            $table->string('person_zip')->nullable();
            $table->string('country')->nullable();
            $table->string('source_count')->nullable();
            $table->string('last_updated_date')->nullable();
            $table->string('zoom_company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_domain_name')->nullable();
            $table->string('company_phone_number')->nullable();
            $table->string('company_street_address')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_state')->nullable();
            $table->string('company_zip_postal_code')->nullable();
            $table->string('company_country')->nullable();
            $table->string('industry_label')->nullable();
            $table->string('industry_hierarchical_category')->nullable();
            $table->string('secondary_industry_label')->nullable();
            $table->string('secondary_industry_hierarchical_category')->nullable();
            $table->string('revenue_in_000s')->nullable();
            $table->string('revenue_range')->nullable();
            $table->string('employees')->nullable();
            $table->string('employees_range')->nullable();
            $table->integer('sic1')->nullable();
            $table->integer('sic2')->nullable();
            $table->integer('naics1')->nullable();
            $table->integer('naics2')->nullable();
            $table->text('titlecode')->nullable();
            $table->string('highest_level_job_function')->nullable();
            $table->string('person_pro_url')->nullable();
            $table->string('encrypted_email_address')->nullable();
            $table->string('email_domain')->nullable();
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
        Schema::dropIfExists('master_leads');
    }
}
