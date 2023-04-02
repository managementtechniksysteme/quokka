<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceRecordsTable extends Migration
{
    public function up(): void
    {
        Schema::create('finance_records', function (Blueprint $table) {
            $table->id();
            $table->date('billed_on');
            $table->string('title');
            $table->text('comment')->nullable();
            $table->double('amount');
            $table->unsignedBigInteger('finance_group_id');
            $table->timestamps();

            $table->unique(['title', 'finance_group_id']);

            $table->foreign('finance_group_id')->references('id')->on('finance_groups')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_records');
    }
}
