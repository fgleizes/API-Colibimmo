<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('City', function (Blueprint $table) {
            $table->id();
            $table->string('zip_code', 5)->nullable();
            $table->string('name', 40);
            $table->string('insee_code', 5)->nullable();
            $table->string('slug', 40);
            $table->double('gps_lng', 17, 14);
            $table->double('gps_lat', 16, 14);
            $table->string('department_code', 3);
            $table->foreignId('id_Department')->constrained('Department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
