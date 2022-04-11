<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['material', 'wage']);
            $table->string('name');
            $table->string('description');
            $table->string('unit')->nullable();
            $table->string('costs')->nullable();
            $table->timestamps();

            $table->unique(['type', 'name']);

            $table->index('unit');
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}
