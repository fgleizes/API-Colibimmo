<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('lastname', 50);
            $table->string('firstname', 50);
            $table->string('mail', 150)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->integer('id_Agency')->nullable();
            $table->integer('id_Address')->nullable();
            $table->integer('id_Role');
            $table->foreign('id_Address', 'Person_Address0_FK')->references('id')->on('address');
            $table->foreign('id_Agency', 'Person_Agency_FK')->references('id')->on('agency');
            $table->foreign('id_Role', 'Person_Role1_FK')->references('id')->on('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person');
    }
}
