<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicNotificationTable extends Migration
{
    public function up()
    {
        Schema::create('clinic_notification', function (Blueprint $table) {
            $table->bigIncrements('id'); // auto-incrementing id
            $table->unsignedBigInteger('clinic_id'); // just define it without FK
            $table->unsignedBigInteger('notification_id'); // just define it without FK
            $table->tinyInteger('read_status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clinic_notification');
    }
}
