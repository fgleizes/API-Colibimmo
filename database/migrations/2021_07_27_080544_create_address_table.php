<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->smallInteger('number')->nullable();
            $table->string('street', 60);
            $table->string('additional_address', 60)->nullable();
            $table->string('building', 20)->nullable();
            $table->tinyInteger('floor')->nullable();
            $table->string('residence', 20)->nullable();
            $table->string('staircase', 2)->nullable();
            $table->integer('id_City');
            $table->foreign('id_City', 'Address_City_FK')->references('id')->on('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
