@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather & Flood Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .glass {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .weather-icon {
      filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
    }
    .animate-float {
      animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    .status-safe { @apply bg-green-100 text-green-800 border-green-200; }
    .status-warning { @apply bg-yellow-100 text-yellow-800 border-yellow-200; }
    .status-danger { @apply bg-red-100 text-red-800 border-red-200; }
  </style>
</head>
<body class="gradient-bg min-h-screen p-4 lg:p-6">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header Section -->
    <div class="glass rounded-3xl shadow-xl p-6 lg:p-8 mb-6">
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div class="flex-1">
          <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h1 class="text-3xl lg:text-4xl font-bold text-gray-800" id="current-time">05:20 AM</h1>
              <p class="text-sm text-gray-600" id="current-date">Wednesday, 15 May, 2025</p>
            </div>
          </div>
          <p class="text-xl font-semibold text-gray-700">Good morning, J! ☀️</p>
        </div>
        <div class="w-full lg:w-96">
          <div class="relative">
            <input type="text" placeholder="Search location..." 
                   class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      
      <!-- Weekly Forecast -->
      <div class="lg:col-span-12">
        <div class="glass rounded-3xl shadow-xl p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">7-Day Forecast</h2>
          <div class="flex gap-4 overflow-x-auto pb-2">
            <div class="flex-shrink-0 bg-blue-500 text-white rounded-2xl p-4 text-center min-w-[100px] shadow-lg">
              <div class="weather-icon animate-float mb-2">☀️</div>
              <p class="text-sm font-medium">Today</p>
              <p class="text-xl font-bold">36°</p>
              <p class="text-xs opacity-90">Sunny</p>
            </div>
            <div class="flex-shrink-0 bg-white rounded-2xl p-4 text-center min-w-[100px] shadow-lg hover:shadow-xl transition-all">
              <div class="weather-icon mb-2">🌤️</div>
              <p class="text-sm font-medium text-gray-600">Sun</p>
              <p class="text-xl font-bold text-gray-800">38°</p>
              <p class="text-xs text-gray-500">Cloudy</p>
            </div>
            <div class="flex-shrink-0 bg-white rounded-2xl p-4 text-center min-w-[100px] shadow-lg hover:shadow-xl transition-all">
              <div class="weather-icon mb-2">🌧️</div>
              <p class="text-sm font-medium text-gray-600">Mon</p>
              <p class="text-xl font-bold text-gray-800">35°</p>
              <p class="text-xs text-gray-500">Rainy</p>
            </div>
            <div class="flex-shrink-0 bg-white rounded-2xl p-4 text-center min-w-[100px] shadow-lg hover:shadow-xl transition-all">
              <div class="weather-icon mb-2">⛈️</div>
              <p class="text-sm font-medium text-gray-600">Tue</p>
              <p class="text-xl font-bold text-gray-800">33°</p>
              <p class="text-xs text-gray-500">Storm</p>
            </div>
            <div class="flex-shrink-0 bg-white rounded-2xl p-4 text-center min-w-[100px] shadow-lg hover:shadow-xl transition-all">
              <div class="weather-icon mb-2">🌦️</div>
              <p class="text-sm font-medium text-gray-600">Wed</p>
              <p class="text-xl font-bold text-gray-800">36°</p>
              <p class="text-xs text-gray-500">Light Rain</p>
            </div>
            <div class="flex-shrink-0 bg-white rounded-2xl p-4 text-center min-w-[100px] shadow-lg hover:shadow-xl transition-all">
              <div class="weather-icon mb-2">☀️</div>
              <p class="text-sm font-medium text-gray-600">Thu</p>
              <p class="text-xl font-bold text-gray-800">38°</p>
              <p class="text-xs text-gray-500">Sunny</p>
            </div>
            <div class="flex-shrink-0 bg-white rounded-2xl p-4 text-center min-w-[100px] shadow-lg hover:shadow-xl transition-all">
              <div class="weather-icon mb-2">🌤️</div>
              <p class="text-sm font-medium text-gray-600">Fri</p>
              <p class="text-xl font-bold text-gray-800">37°</p>
              <p class="text-xs text-gray-500">Partly Cloudy</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Today's Weather -->
      <div class="lg:col-span-4">
        <div class="glass rounded-3xl shadow-xl p-6 h-full">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Cuaca Hari Ini</h2>
            <div class="text-4xl animate-float">🌧️</div>
          </div>
          <p class="text-sm text-gray-600 mb-6">Wednesday, 15 May, 2025</p>
          
          <div class="text-center mb-6">
            <h3 class="text-5xl font-bold text-gray-800 mb-2">36°</h3>
            <p class="text-lg text-gray-600 font-medium">Hujan Lebat</p>
          </div>
          
          <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl">
              <div class="flex items-center gap-2">
                <span>💧</span>
                <span class="text-sm font-medium">Kelembapan</span>
              </div>
              <span class="font-semibold text-blue-600">80%</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
              <div class="flex items-center gap-2">
                <span>🌪️</span>
                <span class="text-sm font-medium">Kecepatan Angin</span>
              </div>
              <span class="font-semibold text-green-600">15 km/h</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-purple-50 rounded-xl">
              <div class="flex items-center gap-2">
                <span>👁️</span>
                <span class="text-sm font-medium">Visibilitas</span>
              </div>
              <span class="font-semibold text-purple-600">8 km</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Flood Status -->
      <div class="lg:col-span-4">
        <div class="glass rounded-3xl shadow-xl p-6 text-center h-full">
          <h2 class="text-xl font-semibold text-gray-800 mb-6">Status Banjir</h2>
          
          <div class="relative inline-block mb-6">
            <svg width="120" height="120" class="transform -rotate-90">
              <circle cx="60" cy="60" r="50" stroke="#e5e7eb" stroke-width="8" fill="none" />
              <circle cx="60" cy="60" r="50" stroke="#3b82f6" stroke-width="8" fill="none" 
                      stroke-dasharray="314" stroke-dashoffset="125" stroke-linecap="round" 
                      class="transition-all duration-1000 ease-out"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="text-3xl font-bold text-gray-800">60%</span>
              <span class="text-xs text-gray-500">Capacity</span>
            </div>
          </div>
          
          <div class="space-y-3">
            <div class="bg-blue-50 rounded-xl p-3">
              <p class="text-sm text-gray-600">Ketinggian Air</p>
              <p class="text-2xl font-bold text-blue-600">120 cm</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-3">
              <p class="text-sm text-gray-600">Status</p>
              <p class="text-lg font-semibold text-yellow-600">Waspada</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Location -->
      <div class="lg:col-span-4">
        <div class="glass rounded-3xl shadow-xl p-6 h-full">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Lokasi</h2>
          
          <div class="bg-gray-100 rounded-2xl h-40 mb-4 flex items-center justify-center">
            <div class="text-center text-gray-500">
              <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
              </svg>
              <p class="text-sm">Interactive Map</p>
            </div>
          </div>
          
          <div class="space-y-2">
            <p class="font-semibold text-gray-800">📍 Sekardangan, Sidoarjo</p>
            <p class="text-sm text-gray-600">Gg. I 11–23, Kec. Sidoarjo</p>
            <p class="text-sm text-gray-500">East Java, Indonesia</p>
          </div>
        </div>
      </div>

      <!-- Flood Chart -->
      <div class="lg:col-span-6">
        <div class="glass rounded-3xl shadow-xl p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Grafik Tinggi Air</h2>
            <select class="px-3 py-1 border border-gray-200 rounded-lg text-sm">
              <option>Last 7 days</option>
              <option>Last 30 days</option>
              <option>Last 3 months</option>
            </select>
          </div>
          <div class="h-64">
            <canvas id="floodChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Warning -->
      <div class="lg:col-span-6">
        <div class="bg-gradient-to-br from-red-50 to-orange-50 border border-red-200 rounded-3xl shadow-xl p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center animate-pulse">
              <span class="text-white text-xl">⚠️</span>
            </div>
            <h2 class="text-xl font-semibold text-red-800">Peringatan Dini</h2>
          </div>
          
          <div class="space-y-4">
            <div class="bg-white bg-opacity-70 rounded-xl p-4">
              <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-600">Level Bahaya</span>
                <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold">Siaga 2</span>
              </div>
              <p class="text-sm text-gray-700"><strong>Jenis:</strong> Curah hujan tinggi</p>
              <p class="text-sm text-gray-700"><strong>Dampak:</strong> Potensi banjir dalam 2-4 jam</p>
              <p class="text-sm text-gray-700"><strong>Tindakan:</strong> Siapkan evakuasi mandiri</p>
            </div>
            
            <div class="bg-white bg-opacity-70 rounded-xl p-4">
              <p class="text-sm text-gray-600 mb-2">Rekomendasi:</p>
              <ul class="text-sm text-gray-700 space-y-1">
                <li>• Hindari aktivitas di luar ruangan</li>
                <li>• Pantau update cuaca secara berkala</li>
                <li>• Siapkan tas darurat</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- History Table -->
      <div class="lg:col-span-12">
        <div class="glass rounded-3xl shadow-xl p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Riwayat Data</h2>
            <button class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors text-sm">
              Export Data
            </button>
          </div>
          
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="text-left py-3 px-2 font-semibold text-gray-700">Tanggal</th>
                  <th class="text-left py-3 px-2 font-semibold text-gray-700">Tinggi Air</th>
                  <th class="text-left py-3 px-2 font-semibold text-gray-700">Status</th>
                  <th class="text-left py-3 px-2 font-semibold text-gray-700">Curah Hujan</th>
                  <th class="text-left py-3 px-2 font-semibold text-gray-700">Kelembapan</th>
                  <th class="text-left py-3 px-2 font-semibold text-gray-700">Suhu</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="py-3 px-2 font-medium">15 May 2025</td>
                  <td class="py-3 px-2">120 cm</td>
                  <td class="py-3 px-2">
                    <span class="status-warning px-2 py-1 rounded-full text-xs font-medium border">Waspada</span>
                  </td>
                  <td class="py-3 px-2">65 mm</td>
                  <td class="py-3 px-2">80%</td>
                  <td class="py-3 px-2">36°C</td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="py-3 px-2 font-medium">14 May 2025</td>
                  <td class="py-3 px-2">126 cm</td>
                  <td class="py-3 px-2">
                    <span class="status-warning px-2 py-1 rounded-full text-xs font-medium border">Waspada</span>
                  </td>
                  <td class="py-3 px-2">45 mm</td>
                  <td class="py-3 px-2">78%</td>
                  <td class="py-3 px-2">35°C</td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="py-3 px-2 font-medium">13 May 2025</td>
                  <td class="py-3 px-2">90 cm</td>
                  <td class="py-3 px-2">
                    <span class="status-safe px-2 py-1 rounded-full text-xs font-medium border">Aman</span>
                  </td>
                  <td class="py-3 px-2">35 mm</td>
                  <td class="py-3 px-2">75%</td>
                  <td class="py-3 px-2">37°C</td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="py-3 px-2 font-medium">12 May 2025</td>
                  <td class="py-3 px-2">85 cm</td>
                  <td class="py-3 px-2">
                    <span class="status-safe px-2 py-1 rounded-full text-xs font-medium border">Aman</span>
                  </td>
                  <td class="py-3 px-2">20 mm</td>
                  <td class="py-3 px-2">70%</td>
                  <td class="py-3 px-2">38°C</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
    </div>
  </div>

  <script>
    // Real-time clock
    function updateTime() {
      const now = new Date();
      const timeStr = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
      });
      const dateStr = now.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
      
      document.getElementById('current-time').textContent = timeStr;
      document.getElementById('current-date').textContent = dateStr;
    }
    
    // Update time every second
    setInterval(updateTime, 1000);
    updateTime();

    // Flood Chart
    const ctx = document.getElementById('floodChart');
    if(ctx) {
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [{
            label: 'Water Level (cm)',
            data: [85, 90, 126, 135, 120, 115, 120],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 200,
              grid: {
                color: 'rgba(0,0,0,0.1)'
              },
              ticks: {
                callback: function(value) {
                  return value + ' cm';
                }
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          },
          elements: {
            point: {
              hoverRadius: 8
            }
          }
        }
      });
    }
  </script>
</body>
</html>
@endsection