<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->string('registration_identifier');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique('registration_identifier');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
