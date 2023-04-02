<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceGroupsTable extends Migration
{
    public function up(): void
    {
        Schema::create('finance_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique()->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_groups');
    }
}
