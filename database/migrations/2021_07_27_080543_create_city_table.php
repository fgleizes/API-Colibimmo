<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->string('zip_code', 5)->nullable();
            $table->string('name', 40);
            $table->string('insee_code', 5)->nullable();
            $table->string('slug', 40);
            $table->double('gps_lat');
            $table->double('gps_lng');
            $table->string('department_code', 3);
            $table->foreignId('id_Departement')->constrained('departement');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city');
    }
}
