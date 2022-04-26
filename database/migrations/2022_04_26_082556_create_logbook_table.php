<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogbookTable extends Migration
{
    public function up()
    {
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->date('driven_on');
            $table->unsignedInteger('start_kilometres');
            $table->unsignedInteger('end_kilometres');
            $table->unsignedInteger('driven_kilometres');
            $table->unsignedInteger('litres_refuelled')->nullable();
            $table->string('origin');
            $table->string('destination');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('vehicle_id');
            $table->timestamps();

            $table->foreign('employee_id')->references('person_id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logbook');
    }
}
