<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingTable extends Migration
{
    public function up()
    {
        Schema::create('accounting', function (Blueprint $table) {
            $table->id();
            $table->date('service_provided_on');
            $table->time('service_provided_started_at')->nullable();
            $table->time('service_provided_ended_at')->nullable();
            $table->unsignedDouble('amount');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('service_id');
            $table->timestamps();

            $table->foreign('employee_id')->references('person_id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounting');
    }
}
