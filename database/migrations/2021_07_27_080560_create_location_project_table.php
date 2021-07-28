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
            $table->id();
            $table->string('comments')->nullable();
            $table->foreignId('id_Project')->constrained('project');
            $table->foreignId('id_City')->nullable()->constrained('city');
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
