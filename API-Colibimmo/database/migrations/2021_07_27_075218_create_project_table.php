<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Project', function (Blueprint $table) {
            $table->id();
            $table->char('reference',20);
            $table->text('note')->nullable();
            $table->tinyInteger('commission')->nullable();
            $table->date('created_at');
            $table->date('updated_at')->nullable();
            $table->smallInteger('min_area')->nullable();
            $table->smallInteger('max_area')->nullable();
            $table->tinyText('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->tinyInteger('visibility_priority')->nullable();
            $table->foreignId('id_Person')->constrained('person');
            $table->foreignId('id_Type_project')->constrained('type_project');
            $table->foreignId('id_Status_project')->constrained('status_project');
            $table->foreignId('id_Energy_index')->constrained('energy_index');
            $table->foreignId('id_Address')->constrained('address');
            $table->foreignId('id_manage_project')->constrained('manage_project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Project');
    }
}
