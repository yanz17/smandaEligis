<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Dashboard</title>
</head>
<body class="grid grid-flow-col grid-cols-5 bg-gray-200 h-screen">
    <section class="col-span-1 h-screen">
        <x-sidebar>
            <li class="py-4 hover:bg-gray-900"><a href="/admin">Pemeringkatan</a></li>
            <li class="py-4"><a href="/admin">Kelola User</a></li>
            <li class="py-4"><a href="/admin">Kelola Data Siswa</a></li>
            <li class="py-4"><a href="/admin">Kelola Nilai</a></li>
            <li class="py-4"><a href="/admin">Siswa Eligible</a></li>
        </x-sidebar>
    </section>
    <section class="col-span-4  flex flex-col">
        <x-navbar-dash></x-navbar-dash>
        <div class="container grow flex flex-col p-4">
            <div class="card w-full grow bg-base-content"></div>
        </div>
    </section>
</body>
</html>