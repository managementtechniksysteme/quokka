<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addressable', function (Blueprint $table) {
            $table->bigInteger("address_id");
            $table->bigInteger("addressable_id");
            $table->string("addressable_type");
            $table->timestamps();

            $table->primary(['address_id', 'addressable_id', 'addressable_type']);
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
