<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('requestable_id');
            $table->string('requestable_type');
            $table->string('token', 64);
            $table->timestamps();

            $table->primary('token');
            $table->unique(['requestable_id', 'requestable_type'], 'download_requests_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('download_requests');
    }
}
