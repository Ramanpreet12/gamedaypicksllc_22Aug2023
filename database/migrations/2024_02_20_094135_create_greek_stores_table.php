<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGreekStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('greek_stores', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_url');
            $table->string('product_type');
            $table->string('product_color');
            $table->integer('price');
            $table->text('description');
            $table->text('product_details');
            $table->enum('status' , ['active', 'inactive']);
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
        Schema::dropIfExists('greek_stores');
    }
}
