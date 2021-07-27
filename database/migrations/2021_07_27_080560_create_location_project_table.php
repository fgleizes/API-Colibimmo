<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_project', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('comments')->nullable();
            $table->integer('id_Project');
            $table->integer('id_City')->nullable();
            $table->foreign('id_City', 'location_project_City0_FK')->references('id')->on('city');
            $table->foreign('id_Project', 'location_project_Project_FK')->references('id')->on('project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_project');
    }
}
