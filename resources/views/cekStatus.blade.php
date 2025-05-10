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
    <title>Cek Status</title>
</head>
<body class="hero h-screen" style="background-image: url(/img/hero-bg.JPG)">
    <div class="hero-overlay"></div>
    <div class="hero-content flex flex-col justify-center items-center bg-black/75 rounded-4xl md:w-2xl">
        <h1 class="text-white font-bold text-2xl md:text-4xl mt-4 mb-4">Status Eligible SNBP 2025</h1>

        <form action="{{ route('cekstatus.cek') }}" method="post">
            @csrf
            <section class="flex flex-col gap-1">
                <label class="text-sm md:text-lg font-medium text-white">Nomor Induk Siswa</label>
                <input
                    type="text"
                    name="id"
                    class="input input-sm md:input-lg w-3xs md:w-lg"
                    required
                    placeholder="Masukkan NIS (10 digit)"
                    value="{{ old('id') }}"
                />
                @error('id')
                    <p class="text-red-400">{{ $message }}</p>
                @enderror
            </section>
            <section class="flex flex-col gap-1">
                <label class="text-sm md:text-lg font-medium text-white">Tanggal Lahir</label>
                <input
                    type="date"
                    name="tanggal_lahir"
                    class="input input-sm md:input-lg w-3xs md:w-lg"
                    required
                    placeholder="Masukkan Tanggal Lahir"
                    value="{{ old('tanggal_lahir') }}"
                />
                @error('tanggal_lahir')
                    <p class="text-red-400">{{ $message }}</p>
                @enderror
            </section>
            <section class="flex flex-col justify-center items-center gap-5 mb-2">
                <button class="btn btn-primary btn-lg text-sm md:text-lg">Cek Status</button>
                <p><a href="/" class="link link-hover text-yellow-500 text-sm md:text-lg font-semibold">Kembali</a></p>
            </section>
        </form>
    </div>
</body>
</html>