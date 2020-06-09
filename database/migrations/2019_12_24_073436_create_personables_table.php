<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personables', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('personable_id');
            $table->string('personable_type');
            $table->enum('person_type', ['involved', 'present', 'notified'])->nullable();
            $table->timestamps();

            $table->primary(['person_id', 'personable_id', 'personable_type', 'person_type'], 'personables_primary');

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
        Schema::dropIfExists('personable');
    }
}
