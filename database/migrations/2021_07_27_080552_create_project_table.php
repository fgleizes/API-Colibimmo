<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->char('reference', 20);
            $table->text('note')->nullable();
            $table->tinyInteger('commission')->nullable();
            $table->timestamps();
            $table->float('area')->nullable();
            $table->smallInteger('min_area')->nullable();
            $table->smallInteger('max_area')->nullable();
            $table->float('price')->nullable();
            $table->float('min_price')->nullable();
            $table->float('max_price')->nullable();
            $table->string('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->tinyInteger('visibility_priority')->nullable();
            $table->integer('id_Person');
            $table->integer('id_Type_project');
            $table->integer('id_Status_project');
            $table->integer('id_Energy_index')->nullable();
            $table->integer('id_Address')->nullable();
            $table->integer('id_manage_project');
            $table->foreign('id_Address', 'Project_Address3_FK')->references('id')->on('address');
            $table->foreign('id_Energy_index', 'Project_Energy_index2_FK')->references('id')->on('energy_index');
            $table->foreign('id_Person', 'Project_Person_FK')->references('id')->on('person');
            $table->foreign('id_Status_project', 'Project_Status_project1_FK')->references('id')->on('status_project');
            $table->foreign('id_Type_project', 'Project_Type_project0_FK')->references('id')->on('type_project');
            $table->foreign('id_manage_project', 'Project_manage_project4_FK')->references('id')->on('manage_project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project');
    }
}
