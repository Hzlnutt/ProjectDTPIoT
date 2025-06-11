<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    public function up()
{
    Schema::create('sensor_logs', function (Blueprint $table) {
        $table->id();
        $table->string('device_id');
        $table->string('location');
        $table->float('rainfall_30min');
        $table->float('rainfall_daily');
        $table->float('water_level');
        $table->string('status');
        $table->timestamps();
    });
}

}
