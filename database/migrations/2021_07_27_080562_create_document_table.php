<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('pathname', 150);
            $table->integer('id_Project');
            $table->integer('id_Type_document');
            $table->foreign('id_Project', 'Document_Project_FK')->references('id')->on('project');
            $table->foreign('id_Type_document', 'Document_Type_document0_FK')->references('id')->on('type_document');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document');
    }
}
