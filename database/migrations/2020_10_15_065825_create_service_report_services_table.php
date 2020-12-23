<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceReportServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_report_services', function (Blueprint $table) {
            $table->unsignedBigInteger('service_report_id');
            $table->date('provided_on');
            $table->unsignedDouble('hours');
            $table->unsignedDouble('allowances');
            $table->unsignedInteger('kilometres');
            $table->timestamps();

            $table->primary(['service_report_id', 'provided_on']);

            $table->foreign('service_report_id')->references('id')->on('service_reports')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_report_services');
    }
}
