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
            $table->string('title')->unique();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_groups');
    }
}
