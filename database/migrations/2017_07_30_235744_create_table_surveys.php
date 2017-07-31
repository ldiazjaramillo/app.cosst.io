<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSurveys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id')->nullable();
            $table->integer('products_category')->default(0)->nullable();
            $table->integer('future_purchase')->default(0)->nullable();
            $table->integer('future_purchase_type')->default(0)->nullable();
            $table->integer('product_interest')->default(0)->nullable();
            $table->text('client_needs')->nullable();
            $table->text('client_product_introduce')->nullable();
            $table->integer('contact_by')->default(0)->nullable();
            $table->text('season')->nullable();
            $table->text('favorite_vendors')->nullable();
            $table->integer('client_id')->nullable();
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
        Schema::dropIfExists('surveys');
    }
}
