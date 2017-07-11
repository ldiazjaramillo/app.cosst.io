<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 140);
            $table->string('description', 180)->nullable();
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('client_id')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });
    }

}