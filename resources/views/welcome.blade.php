@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather & Flood Dashboard - Real Time</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .weather-icon { filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1)); }
        .animate-float { animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        .status-safe { @apply bg-green-100 text-green-800 border-green-200; }
        .status-warning { @apply bg-yellow-100 text-yellow-800 border-yellow-200; }
        .status-danger { @apply bg-red-100 text-red-800 border-red-200; }
        .status-critical { @apply bg-red-200 text-red-900 border-red-300; }
        .pulse-ring { animation: pulse-ring 1.25s cubic-bezier(0.215, 0.61, 0.355, 1) infinite; }
        @keyframes pulse-ring { 0% { transform: scale(.33); } 80%, 100% { opacity: 0; } }
        .online-indicator { background-color: #10b981; animation: pulse 2s infinite; }
        .offline-indicator { background-color: #ef4444; }
        .water-level-bar { transition: all 0.5s ease; }
        .rain-drop { animation: rain-drop 2s ease-in-out infinite; }
        @keyframes rain-drop { 0%, 100% { transform: translateY(0px) scale(1); opacity: 0.7; } 50% { transform: translateY(-5px) scale(1.1); opacity: 1; } }
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
                            <h1 class="text-3xl lg:text-4xl font-bold text-gray-800">Flood Monitoring System</h1>
                            <p class="text-gray-600 text-lg">Sekardangan, Sidoarjo - Real-time Environmental Data</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-2 bg-white bg-opacity-50 rounded-full">
                        <div id="connection-status" class="w-3 h-3 rounded-full online-indicator"></div>
                        <span id="connection-text" class="text-sm font-medium text-gray-700">ESP32 Connected</span>
                    </div>
                    <div class="text-right">
                        <div id="current-time" class="text-xl font-bold text-gray-800"></div>
                        <div id="current-date" class="text-sm text-gray-600"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
            <!-- Water Level Card -->
            <div class="glass rounded-2xl shadow-lg p-6 relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6 2a1 1 0 011 1v1h6V3a1 1 0 112 0v1h1a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2h1V3a1 1 0 011-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Water Level</h3>
                            <p class="text-xs text-gray-500">Current Height</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <span id="water-level" class="text-3xl font-bold text-gray-800">0.0</span>
                    <span class="text-sm text-gray-500 mb-1">cm</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                    <div id="water-level-bar" class="water-level-bar bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                </div>
                <p id="water-level-status" class="text-xs text-gray-500">Normal Level</p>
            </div>

            <!-- Flood Status Card -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div id="status-icon" class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Flood Status</h3>
                            <p class="text-xs text-gray-500">Current Alert Level</p>
                        </div>
                    </div>
                </div>
                <div id="flood-status" class="px-3 py-1 rounded-full text-sm font-medium status-safe inline-block mb-2">
                    Aman
                </div>
                <p id="flood-level" class="text-sm text-gray-600">Normal</p>
            </div>

            <!-- 30min Rainfall Card -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white rain-drop" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 3.636L10 .707l4.95 2.929c.953.565 1.55 1.61 1.55 2.722v5.586a5.5 5.5 0 01-11 0V6.358c0-1.112.597-2.157 1.55-2.722z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">30min Rainfall</h3>
                            <p class="text-xs text-gray-500">Last Interval</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <span id="rainfall-30min" class="text-3xl font-bold text-gray-800">0.0</span>
                    <span class="text-sm text-gray-500 mb-1">mm</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                    <p class="text-xs text-gray-500">Updated every 30 minutes</p>
                </div>
            </div>

            <!-- Daily Rainfall Card -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Daily Rainfall</h3>
                            <p class="text-xs text-gray-500">Today's Total</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <span id="rainfall-daily" class="text-3xl font-bold text-gray-800">0.0</span>
                    <span class="text-sm text-gray-500 mb-1">mm</span>
                </div>
                <div class="flex items-center gap-2">
                    <span id="total-tips" class="text-xs text-gray-500">0 tips recorded</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Water Level Chart -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Water Level Trend</h3>
                    <div class="flex gap-2">
                        <button onclick="updateChartPeriod('24h')" class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">24H</button>
                        <button onclick="updateChartPeriod('7d')" class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">7D</button>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="waterLevelChart"></canvas>
                </div>
            </div>

            <!-- Rainfall Chart -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Rainfall Distribution</h3>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">30min intervals</span>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="rainfallChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Device Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Recent Activity</h3>
                <div id="activity-log" class="space-y-3 max-h-64 overflow-y-auto">
                    <!-- Activity items will be populated here -->
                </div>
            </div>

            <!-- Device Information -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Device Information</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Device ID</span>
                        <span id="device-id" class="font-medium text-gray-800">ESP32_FLOOD_SENSOR_01</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Location</span>
                        <span id="device-location" class="font-medium text-gray-800">Sekardangan, Sidoarjo</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">WiFi Signal</span>
                        <span id="wifi-signal" class="font-medium text-gray-800">-45 dBm</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Last Update</span>
                        <span id="last-update" class="font-medium text-gray-800">Just now</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex gap-3">
                            <button onclick="resetSensorData()" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                Reset Data
                            </button>
                            <button onclick="downloadData()" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm">
                                Export Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let waterLevelChart, rainfallChart;
        let sensorData = {
            waterLevel: 0,
            rainfall30min: 0,
            rainfallDaily: 0,
            status: 'Aman',
            level: 'Normal',
            totalTips: 0,
            lastUpdate: new Date()
        };

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            updateDateTime();
            fetchSensorData();
            
            // Update data every 5 seconds
            setInterval(fetchSensorData, 5000);
            setInterval(updateDateTime, 1000);
        });

        // Update current date and time
        function updateDateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Fetch sensor data from API
        async function fetchSensorData() {
            try {
                // Try ESP32 direct API first
                const directResponse = await fetch('/api/current');
                if (directResponse.ok) {
                    const data = await directResponse.json();
                    updateSensorDisplay(data);
                    updateConnectionStatus(true);
                    return;
                }
                
                // Fallback to Laravel API
                const response = await fetch('/api/sensor-data/latest');
                if (response.ok) {
                    const result = await response.json();
                    if (result.status === 'success') {
                        updateSensorDisplay(result.data);
                        updateConnectionStatus(true);
                    }
                } else {
                    updateConnectionStatus(false);
                }
            } catch (error) {
                console.error('Error fetching sensor data:', error);
                updateConnectionStatus(false);
                // Use simulated data for demo
                simulateSensorData();
            }
        }

        // Update sensor display with new data
        function updateSensorDisplay(data) {
            // Update water level
            const waterLevel = data.tinggi_air || data.waterLevel || 0;
            document.getElementById('water-level').textContent = waterLevel.toFixed(1);
            
            // Update water level bar (assuming max safe level is 200cm)
            const percentage = Math.min((waterLevel / 200) * 100, 100);
            document.getElementById('water-level-bar').style.width = percentage + '%';
            
            // Update flood status
            const status = data.status || 'Aman';
            const level = data.level || 'Normal';
            
            updateFloodStatus(status, level);
            
            // Update rainfall data
            const rainfall30min = data.curah_hujan_30menit || data.rainfall30min || 0;
            const rainfallDaily = data.curah_hujan_harian || data.rainfallDaily || 0;
            const totalTips = data.total_tips || data.totalTips || 0;
            
            document.getElementById('rainfall-30min').textContent = rainfall30min.toFixed(1);
            document.getElementById('rainfall-daily').textContent = rainfallDaily.toFixed(1);
            document.getElementById('total-tips').textContent = totalTips + ' tips recorded';
            
            // Update last update time
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString('id-ID');
            
            // Add to activity log
            addActivityLog(`Data updated - Water: ${waterLevel.toFixed(1)}cm, Status: ${status}`);
        }

        // Update flood status with appropriate styling
        function updateFloodStatus(status, level) {
            const statusElement = document.getElementById('flood-status');
            const levelElement = document.getElementById('flood-level');
            const iconElement = document.getElementById('status-icon');
            
            statusElement.textContent = status;
            levelElement.textContent = level;
            
            // Remove all status classes
            statusElement.className = statusElement.className.replace(/(status-\w+)/g, '');
            iconElement.className = iconElement.className.replace(/(bg-\w+-\d+)/g, '');
            
            // Apply appropriate status styling
            switch(status) {
                case 'Aman':
                    statusElement.classList.add('status-safe');
                    iconElement.classList.add('bg-green-500');
                    break;
                case 'Waspada':
                    statusElement.classList.add('status-warning');
                    iconElement.classList.add('bg-yellow-500');
                    break;
                case 'Siaga':
                    statusElement.classList.add('status-danger');
                    iconElement.classList.add('bg-orange-500');
                    break;
                case 'Bahaya':
                    statusElement.classList.add('status-critical');
                    iconElement.classList.add('bg-red-500');
                    break;
            }
        }

        // Update connection status indicator
        function updateConnectionStatus(isConnected) {
            const statusElement = document.getElementById('connection-status');
            const textElement = document.getElementById('connection-text');
            
            if (isConnected) {
                statusElement.className = 'w-3 h-3 rounded-full online-indicator';
                textElement.textContent = 'ESP32 Connected';
            } else {
                statusElement.className = 'w-3 h-3 rounded-full offline-indicator';
                textElement.textContent = 'ESP32 Offline';
            }
        }

        // Add activity to log
        function addActivityLog(message) {
            const activityLog = document.getElementById('activity-log');
            const time = new Date().toLocaleTimeString('id-ID');
            
            const activityItem = document.createElement('div');
            activityItem.className = 'flex items-center gap-3 p-3 bg-white bg-opacity-50 rounded-lg';
            activityItem.innerHTML = `
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-700">${message}</p>
                    <p class="text-xs text-gray-500">${time}</p>
                </div>
            `;
            
            activityLog.insertBefore(activityItem, activityLog.firstChild);
            
            // Keep only last 10 activities
            while (activityLog.children.length > 10) {
                activityLog.removeChild(activityLog.lastChild);
            }
        }

        // Initialize charts
        function initializeCharts() {
            // Water Level Chart
            const waterCtx = document.getElementById('waterLevelChart').getContext('2d');
            waterLevelChart = new Chart(waterCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Water Level (cm)',
                        data: [],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Water Level (cm)' }
                        }
                    }
                }
            });

            // Rainfall Chart
            const rainCtx = document.getElementById('rainfallChart').getContext('2d');
            rainfallChart = new Chart(rainCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Rainfall (mm)',
                        data: [],
                        backgroundColor: '#6366F1',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Rainfall (mm)' }
                        }
                    }
                }
            });

            // Load initial chart data
            loadChartData();
        }

        // Load chart data from API
        async function loadChartData() {
            try {
                const response = await fetch('/api/sensor-data/chart?days=1');
                if (response.ok) {
                    const result = await response.json();
                    if (result.status === 'success') {
                        updateCharts(result.data);
                    }
                }
            } catch (error) {
                console.error('Error loading chart data:', error);
                // Use simulated data for demo
                simulateChartData();
            }
        }

        // Update charts with new data
        function updateCharts(data) {
            // Update water level chart
            waterLevelChart.data.labels = data.labels || [];
            waterLevelChart.data.datasets[0].data = data.water_levels || [];
            waterLevelChart.update();

            // Update rainfall chart
            rainfallChart.data.labels = data.labels || [];
            rainfallChart.data.datasets[0].data = data.rainfall || [];
            rainfallChart.update();
        }

        // Simulate sensor data for demo
        function simulateSensorData() {
            const waterLevel = Math.random() * 100;
            const rainfall30min = Math.random() * 10;
            const rainfallDaily = Math.random() * 50;
            
            let status = 'Aman';
            let level = 'Normal';
            
            if (waterLevel > 120) {
                status = 'Bahaya';
                level = 'Siaga 1';
            } else if (waterLevel > 80) {
                status = 'Siaga';
                level = 'Siaga 2';
            } else if (waterLevel > 50) {
                status = 'Waspada';
                level = 'Siaga 3';
            }
            
            updateSensorDisplay({
                tinggi_air: waterLevel,
                curah_hujan_30menit: rainfall30min,
                curah_hujan_harian: rainfallDaily,
                status: status,
                level: level,
                total_tips: Math.floor(rainfallDaily / 0.4)
            });
        }

        // Simulate chart data for demo
        function simulateChartData() {
            const labels = [];
            const waterLevels = [];
            const rainfall = [];
            
            for (let i = 23; i >= 0; i--) {
                const hour = new Date();
                hour.setHours(hour.getHours() - i);
                labels.push(hour.getHours() + ':00');
                waterLevels.push(Math.random() * 100);
                rainfall.push(Math.random() * 5);
            }
            
            updateCharts({ labels, water_levels: waterLevels, rainfall });
        }

        // Reset sensor data
        async function resetSensorData() {
            if (confirm('Are you sure you want to reset all sensor data?')) {
                try {
                    const response = await fetch('/api/reset', { method: 'POST' });
                    if (response.ok) {
                        alert('Sensor data has been reset successfully');
                        fetchSensorData();
                    }
                } catch (error) {
                    alert('Failed to reset sensor data');
                }
            }
        }

        // Download data as CSV
        function downloadData() {
            // This would typically fetch data from the API and create a CSV file
            alert('Data export feature will download historical sensor data as CSV');
        }

        // Update chart period
        function updateChartPeriod(period) {
            // Update chart based on selected period
            console.log('Updating chart for period:', period);
            loadChartData();
        }

        // Initialize with simulated data for demo
        simulateSensorData();
    </script>
</body>
</html>
@endsection