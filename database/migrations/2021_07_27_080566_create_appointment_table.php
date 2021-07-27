<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('subject');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->boolean('is_canceled')->nullable();
            $table->timestamps();
            $table->integer('id_Type_appointment');
            $table->foreign('id_Type_appointment', 'Appointment_Type_appointment_FK')->references('id')->on('type_appointment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment');
    }
}
