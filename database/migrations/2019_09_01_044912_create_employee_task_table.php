<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_task', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('task_id');
            $table->timestamps();

            $table->primary(['employee_id', 'task_id']);

            $table->foreign('employee_id')->references('person_id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_task');
    }
}
