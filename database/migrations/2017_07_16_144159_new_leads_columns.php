<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewLeadsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->integer('client_id')->default(0)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('job_function', 140)->nullable();
            $table->string('management_level', 140)->nullable();
            $table->string('zoom_url', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('client_id');
            $table->dropColumn('user_id');
            $table->dropColumn('job_function');
            $table->dropColumn('management_level');
            $table->dropColumn('zoom_url');
        });
    }
}
