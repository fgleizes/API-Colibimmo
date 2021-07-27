<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('code', 3);
            $table->string('name', 30);
            $table->string('slug', 30);
            $table->string('region_code', 3);
            $table->integer('id_Region');
            $table->foreign('id_Region', 'Department_Region_FK')->references('id')->on('region');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department');
    }
}
