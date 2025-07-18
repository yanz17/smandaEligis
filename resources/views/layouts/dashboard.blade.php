<!DOCTYPE html>
<html lang="en" x-data="{ isDarkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="isDarkMode ? 'dark-mode' : 'light-mode'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Tambah ini di <head> -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
    .dark-mode {
        background-color: #111827;
        color: #F9FBFA;
    }

    .light-mode {
        background-color: #f9fafb;
        color: #1f2937;
    }
</style>
</head>
<body class="min-h-screen flex items-center justify-center" x-bind:class="isDarkMode ? 'dark-mode' : 'light-mode'">
    <div class="card w-full mx-auto" 
    x-bind:class="isDarkMode ? 'bg-black text-gray-300' : 'bg-white border-black shadow-xl text-black'"
    style="margin: 0 27.5rem 0 27.5rem">
        <div class="px-8 py-5">
            @yield('content')
        </div>
    </div>
</body>
</html>

