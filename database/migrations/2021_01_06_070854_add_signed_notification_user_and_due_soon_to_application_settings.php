<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignedNotificationUserAndDueSoonToApplicationSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('signature_notify_user_id')->nullable();
            $table->unsignedTinyInteger('task_due_soon_days')->default(7);

            $table->foreign('signature_notify_user_id')->references('employee_id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_settings', function (Blueprint $table) {
            $table->dropColumn('signature_notify_user_id');
            $table->dropColumn('task_due_soon_days');
        });
    }
}
