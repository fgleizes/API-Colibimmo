<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypePropertyProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_property_project', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_Type_property');
            $table->integer('id_Project');
            $table->foreign('id_Project', 'Type_property_project_Project0_FK')->references('id')->on('project');
            $table->foreign('id_Type_property', 'Type_property_project_Type_property_FK')->references('id')->on('type_property');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_property_project');
    }
}
