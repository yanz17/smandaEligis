<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Cek Status</title>
</head>
<body class="hero h-screen" style="background-image: url(/img/hero-bg.JPG)">
    <div class="hero-overlay"></div>
    <div class="hero-content flex flex-col justify-center items-center bg-black/75 rounded-3xl"
    style="padding: 1.5rem 5rem 1.5rem 5rem">
        <div class="">
            <h1 class="text-yellow-500 font-bold text-2xl md:text-3xl mt-4 mb-4">Status Eligible SNBP 2025</h1>
    
            <form action="{{ route('cekstatus.cek') }}" method="post">
                @csrf
                <section class="flex flex-col gap-1 mb-4 items-center">
                    <label class="text-lg text-left font-medium text-white w-full">Nomor Induk Siswa</label>
                    <input
                        type="text"
                        name="id"
                        class="input input-lg bg-transparent text-white border-white rounded-lg w-full"
                        required
                        placeholder="Masukkan NIS (10 digit)"
                        value="{{ old('id') }}"
                    />
                    @error('id')
                        <p class="text-red-400">{{ $message }}</p>
                    @enderror
                </section>
                <section class="flex flex-col gap-1 mb-4">
                    <label class="text-lg font-medium text-white">Tanggal Lahir</label>
                    <input
                        type="date"
                        name="tanggal_lahir"
                        class="input input-lg w-3xs md:min-w-xl bg-transparent text-white border-white rounded-lg w-full"
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
    </div>
</body>
</html>