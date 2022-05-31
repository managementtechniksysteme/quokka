<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionReportsTable extends Migration
{
    public function up()
    {
        Schema::create('construction_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number');
            $table->enum('status', ['new', 'signed', 'finished']);
            $table->date('services_provided_on');
            $table->text('other_visitors')->nullable();
            $table->text('inspection_comment')->nullable();
            $table->text('missing_documents')->nullable();
            $table->text('special_occurrences')->nullable();
            $table->text('imminent_danger')->nullable();
            $table->text('concerns')->nullable();
            $table->enum('weather', ['sunny', 'cloudy', 'rainy', 'snowy']);
            $table->tinyInteger('minimum_temperature');
            $table->tinyInteger('maximum_temperature');
            $table->text('comment');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();

            $table->unique(['services_provided_on', 'project_id']);
            $table->unique(['number', 'project_id']);

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('person_id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('construction_reports');
    }
}
