<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_project', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_Person')->unique('manage_project_Person_AK');
            $table->foreign('id_Person', 'manage_project_Person_FK')->references('id')->on('person');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manage_project');
    }
}
