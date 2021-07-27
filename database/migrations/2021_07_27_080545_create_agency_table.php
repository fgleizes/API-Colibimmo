<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 40);
            $table->string('mail', 50)->nullable();
            $table->string('phone', 10)->nullable();
            $table->timestamps();
            $table->integer('id_Address')->unique('Agency_Address_AK');
            $table->foreign('id_Address', 'Agency_Address_FK')->references('id')->on('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agency');
    }
}
