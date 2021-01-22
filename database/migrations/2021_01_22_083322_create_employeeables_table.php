<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employeeables', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('employeeable_id');
            $table->string('employeeable_type');
            $table->enum('employee_type', ['involved'])->nullable();
            $table->timestamps();

            $table->primary(['employee_id', 'employeeable_id', 'employeeable_type', 'employee_type'], 'employeeables_primary');

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
        Schema::dropIfExists('employeeables');
    }
}
