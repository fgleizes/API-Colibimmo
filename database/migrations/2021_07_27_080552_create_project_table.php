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
            $table->id();
            $table->char('reference', 20);
            $table->text('note')->nullable();
            $table->unsignedTinyInteger('commission')->nullable();
            $table->timestamps();
            $table->unsignedFloat('area')->nullable();
            $table->unsignedSmallInteger('min_area')->nullable();
            $table->unsignedSmallInteger('max_area')->nullable();
            $table->unsignedFloat('price')->nullable();
            $table->unsignedFloat('min_price')->nullable();
            $table->unsignedFloat('max_price')->nullable();
            $table->string('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->unsignedTinyInteger('visibility_priority')->nullable();
            $table->foreignId('id_Person')->constrained('person');
            $table->foreignId('id_Type_project')->constrained('type_project');
            $table->foreignId('id_Statut_project')->constrained('status_project');
            $table->foreignId('id_Energy_index')->nullable()->constrained('energy_index');
            $table->foreignId('id_Address')->nullable()->constrained('address');
            $table->foreignId('id_Manage_project')->constrained('manage_project');
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
