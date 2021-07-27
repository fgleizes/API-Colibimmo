<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_project', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_room');
            $table->integer('id_Project');
            $table->foreign('id_Project', 'Room_project_Project0_FK')->references('id')->on('project');
            $table->foreign('id_room', 'Room_project_room_FK')->references('id')->on('room');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_project');
    }
}
