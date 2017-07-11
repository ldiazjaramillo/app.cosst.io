<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogleAuthTokenToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gc_token', 140)->nullable()->after('remember_token');
            $table->integer('gc_expires_in')->nullable()->after('gc_token');
            $table->string('gc_refresh_token', 140)->nullable()->after('gc_expires_in');
            $table->integer('gc_created')->nullable()->after('gc_refresh_token');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gc_token');
            $table->dropColumn('gc_expires_in');
            $table->dropColumn('gc_refresh_token');
            $table->dropColumn('gc_created');
        });
    }
}
