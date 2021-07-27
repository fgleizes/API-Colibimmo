<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Address', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('number')->nullable();
            $table->string('street', 60);
            $table->string('additionnal_address', 60)->nullable();
            $table->string('building', 20)->nullable();
            $table->tinyInteger('floor')->nullable();
            $table->string('residence', 20)->nullable();
            $table->string('staircase', 2)->nullable();
            $table->foreignId('id_City')->constrained('City');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
