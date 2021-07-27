<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_appointment', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_Project');
            $table->integer('id_Appointment');
            $table->foreign('id_Appointment', 'Person_appointment_Appointment0_FK')->references('id')->on('appointment');
            $table->foreign('id_Project', 'Person_appointment_Project_FK')->references('id')->on('project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_appointment');
    }
}
