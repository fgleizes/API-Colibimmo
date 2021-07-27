<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_project', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_Option');
            $table->integer('id_Project');
            $table->foreign('id_Option', 'Option_project_Option_FK')->references('id')->on('option');
            $table->foreign('id_Project', 'Option_project_Project0_FK')->references('id')->on('project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_project');
    }
}
