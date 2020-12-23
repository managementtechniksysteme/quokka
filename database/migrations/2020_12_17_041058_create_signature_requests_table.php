<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignatureRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signature_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('requestable_id');
            $table->string('requestable_type');
            $table->string('token', 64);
            $table->timestamps();

            $table->primary('token');
            $table->unique(['requestable_id', 'requestable_type'], 'signature_requests_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signature_requests');
    }
}
