<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->timestamps();

            $table->unique(['name', 'company_id']);

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
        Schema::dropIfExists('projects');
    }
}
