<?php

namespace App\Events;

use App\Models\SensorData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorDataUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sensorData;

    public function __construct(SensorData $sensorData)
    {
        $this->sensorData = $sensorData;
    }

    public function broadcastOn()
    {
        return new Channel('sensor-updates');
    }

    public function broadcastWith()
    {
        return [
            'tinggi_air' => $this->sensorData->tinggi_air,
            'status' => $this->sensorData->status,
            'curah_hujan' => $this->sensorData->curah_hujan_30menit,
            'last_update' => $this->sensorData->created_at->format('Y-m-d H:i:s')
        ];
    }
}