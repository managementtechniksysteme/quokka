<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactPersonToCompanies extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('companies', 'contact_person_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->unsignedBigInteger('contact_person_id')->nullable();
                $table->foreign('contact_person_id')->references('id')->on('people')->onDelete('set null')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('companies', 'contact_person_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('contact_person_id');
            });
        }
    }
}
