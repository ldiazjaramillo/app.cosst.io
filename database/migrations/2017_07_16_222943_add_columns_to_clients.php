<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('slack_url')->nullable();
            $table->string('client_domain')->nullable();
            $table->string('form1_url')->nullable();
            $table->string('form2_url')->nullable();
            $table->string('google_drive_folder')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('ob_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('slack_url');
            $table->dropColumn('client_domain');
            $table->dropColumn('form1_url');
            $table->dropColumn('form2_url');
            $table->dropColumn('google_drive_folder');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ob_password');
        });
    }
}
