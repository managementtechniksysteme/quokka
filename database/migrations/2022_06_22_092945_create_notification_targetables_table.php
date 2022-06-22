<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTargetablesTable extends Migration
{
    public function up()
    {
        Schema::create('notifiables', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_type_id');
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notifiable_type');
            $table->enum('notification_target_type', ['email', 'webpush']);
            $table->timestamps();

            $table->primary(['notification_type_id', 'notifiable_id', 'notifiable_type', 'notification_target_type'], 'notifiables_primary');

            $table->foreign('notification_type_id')->references('id')->on('notification_types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifiables');
    }
}
