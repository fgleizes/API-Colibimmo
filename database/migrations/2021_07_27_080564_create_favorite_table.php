<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_Person');
            $table->integer('id_Project');
            $table->foreign('id_Person', 'Favorite_Person_FK')->references('id')->on('person');
            $table->foreign('id_Project', 'Favorite_Project0_FK')->references('id')->on('project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorite');
    }
}
