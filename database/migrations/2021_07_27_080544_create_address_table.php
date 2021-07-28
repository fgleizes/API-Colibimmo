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
            $table->id();
            $table->unsignedSmallInteger('number')->nullable();
            $table->string('street', 60);
            $table->string('additional_address', 60)->nullable();
            $table->string('building', 20)->nullable();
            $table->tinyInteger('floor')->nullable();
            $table->string('residence', 20)->nullable();
            $table->string('staircase', 2)->nullable();
            $table->foreignId('id_City')->constrained('city');
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
