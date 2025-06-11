<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorDataTable extends Migration
{
    public function up()
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->string('location');
            $table->float('tinggi_air', 8, 2);
            $table->float('curah_hujan_30menit', 8, 2);
            $table->float('curah_hujan_harian', 8, 2);
            $table->string('status');
            $table->string('level');
            $table->bigInteger('total_tips');
            $table->timestamp('sensor_timestamp');
            $table->timestamps();
            
            $table->index(['device_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensor_data');
    }
}