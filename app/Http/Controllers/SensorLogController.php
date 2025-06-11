<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorLog;

class SensorLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'location' => 'required|string',
            'rainfall_30min' => 'required|numeric',
            'rainfall_daily' => 'required|numeric',
            'water_level' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $log = new SensorLog($validated);
        $log->save();

        return response()->json(['message' => 'Sensor data stored successfully']);
    }
}
