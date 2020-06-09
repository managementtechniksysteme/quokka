<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('number');
            $table->string('title');
            $table->date('meeting_held_on');
            $table->date('next_meeting_on')->nullable();
            $table->text('comment');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('person_id')->nullable();
            $table->timestamps();

            $table->unique(['title', 'project_id']);
            $table->unique(['number', 'project_id']);

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('person_id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memos');
    }
}
