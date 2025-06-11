<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data sesuai dengan payload dari ESP32
        $validated = $request->validate([
            'device_id' => 'required|string|max:50',
            'location' => 'nullable|string|max:100',
            'tinggi_air' => 'required|numeric',
            'curah_hujan_30menit' => 'required|numeric',
            'curah_hujan_harian' => 'required|numeric',
            'status' => 'required|string|in:Aman,Waspada,Siaga,Bahaya',
            'level' => 'required|string|max:20',
            'total_tips' => 'required|integer',
            'sensor_timestamp' => 'required|date'
        ]);

        // Konversi timestamp ke format yang benar
        $validated['sensor_timestamp'] = Carbon::parse($validated['sensor_timestamp']);

        // Simpan ke database
        $sensorData = SensorData::create([
            'device_id' => $validated['device_id'],
            'location' => $validated['location'],
            'tinggi_air' => $validated['tinggi_air'],
            'curah_hujan_30menit' => $validated['curah_hujan_30menit'],
            'curah_hujan_harian' => $validated['curah_hujan_harian'],
            'status' => $validated['status'],
            'level' => $validated['level'],
            'total_tips' => $validated['total_tips'],
            'sensor_timestamp' => $validated['sensor_timestamp']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data sensor berhasil disimpan',
            'data' => $sensorData,
            'calculated' => [
                'status_color' => $sensorData->status_color,
                'water_level_percentage' => $sensorData->water_level_percentage
            ]
        ], 201);
    }

    public function latest($device_id = null)
    {
        $query = SensorData::query()->latest('sensor_timestamp');
        
        if ($device_id) {
            $query->where('device_id', $device_id);
        }

        $data = $query->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'calculated' => [
                'status_color' => $data->status_color,
                'water_level_percentage' => $data->water_level_percentage
            ]
        ]);
    }
}