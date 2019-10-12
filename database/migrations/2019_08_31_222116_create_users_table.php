<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->string('username');
            $table->string('password');
            $table->rememberToken();
            $table->string('otp_secret')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('employee_id');
            $table->unique('username');

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
        Schema::dropIfExists('users');
    }
}
