<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('number');
            $table->enum('status', ['new', 'signed', 'finished']);
            $table->text('comment');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();

            $table->unique(['number', 'project_id']);

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('person_id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_reports');
    }
}
