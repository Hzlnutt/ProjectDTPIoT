<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SensorData extends Model
{
    use HasFactory;

    protected $table = 'sensor_data';
    
    protected $fillable = [
        'device_id',
        'location',
        'tinggi_air',
        'curah_hujan_30menit',
        'curah_hujan_harian',
        'status',
        'level',
        'total_tips',
        'sensor_timestamp'
    ];

    protected $casts = [
        'sensor_timestamp' => 'datetime',
        'tinggi_air' => 'float',
        'curah_hujan_30menit' => 'float',
        'curah_hujan_harian' => 'float',
        'total_tips' => 'integer'
    ];

    public function getStatusColorAttribute()
    {
        $colors = [
            'Aman' => 'green',
            'Waspada' => 'yellow',
            'Siaga' => 'orange',
            'Bahaya' => 'red'
        ];
        
        return $colors[$this->status] ?? 'gray';
    }

    public function getWaterLevelPercentageAttribute()
    {
        // Assuming max safe level is 200cm
        return min(($this->tinggi_air / 200) * 100, 100);
    }
}