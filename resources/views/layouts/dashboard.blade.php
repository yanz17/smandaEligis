<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Tambah ini di <head> -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
</head>
<body class="min-h-screen bg-slate-800 flex items-center justify-center">
    <div class="card w-lg mx-auto bg-black text-white">
        <div class="px-8 py-5">
            @yield('content')
        </div>
    </div>
</body>
</html>

