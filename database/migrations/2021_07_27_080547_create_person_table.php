<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->string('lastname', 50);
            $table->string('firstname', 50);
            $table->string('mail', 150)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->foreignId('id_Agency')->nullable()->constrained('agency');
            $table->foreignId('id_Address')->nullable()->constrained('address');
            $table->foreignId('id_Role')->constrained('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person');
    }
}
