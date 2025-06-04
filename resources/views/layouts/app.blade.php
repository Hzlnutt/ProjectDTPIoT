<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Cuaca & Banjir</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-50 min-h-screen font-sans text-gray-800">

    <!-- Header Navbar -->
    <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-700">Sistem Monitoring Cuaca & Banjir</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="/dashboard" class="text-blue-600 hover:underline">Dashboard</a></li>
                <!-- Tambahkan menu lain di sini -->
            </ul>
        </nav>
    </header>

    <!-- Content -->
    <main class="p-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-10 py-4 px-6 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} Sistem Informasi Cuaca & Banjir. All rights reserved.
    </footer>

</body>
</html>
