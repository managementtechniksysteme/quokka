<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addressables', function (Blueprint $table) {
            $table->bigInteger('address_id');
            $table->bigInteger('addressable_id');
            $table->string('addressable_type');
            $table->timestamps();

            // The field address_id is not part of the primary key in order
            // to limit addresses to one per linked model (e.g. company).
            $table->primary(['addressable_id', 'addressable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addressable');
    }
}
