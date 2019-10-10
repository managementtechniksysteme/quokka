<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('title_prefix')->nullable();
            $table->string('title_suffix')->nullable();
            $table->enum('gender', ['male', 'female', 'neutral']);
            $table->string('department')->nullable();
            $table->string('role')->nullable();
            $table->string('phone_company')->nullable();
            $table->string('phone_mobile')->nullable();
            $table->string('phone_private')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();

            $table->unique('email');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
}
