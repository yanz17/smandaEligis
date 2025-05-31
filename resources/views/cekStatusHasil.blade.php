<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Status Eligible</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="hero h-screen" style="background-image: url(/img/hero-bg.JPG)">
    <div class="hero-overlay"></div>
    <div class="hero-content flex flex-col justify-center items-center bg-black/75 md:w-2xl text-white text-center p-0 rounded-4xl">
        @if ($eligible)
            <section class="flex flex-col gap-2 bg-info-content w-full py-5 rounded-t-4xl">
                <h1 class="text-lg font-bold">SELAMAT! ANDA DINYATAKAN SEBAGAI SISWA ELIGIBLE SNBP 2025</h1>
                <h2 class="text-sm font-normal">UNTUK INFORMASI SELANJUTNYA HUBUNGI WALI KELAS MASING-MASING</h2>
            </section>
        @else
            <section class="flex flex-col gap-2 bg-error-content w-full py-5 rounded-t-4xl">
                <h1 class="text-lg font-bold">ANDA DINYATAKAN TIDAK ELIGIBLE UNTUK MENGIKUTI SNBP 2025</h1>
                <h2 class="text-sm font-normal">MASIH ADA KESEMPATAN UNTUK MENGIKUTI SNBT DAN SELEKSI MANDIRI</h2>
            </section>
        @endif

        <section class="p-4">
            <p class="text-lg mb-2">Nama: <strong>{{ $siswa->nama }}</strong></p>
            <p class="text-lg mb-2">NIS: <strong>{{ $siswa->id }}</strong></p>
            <p class="text-lg mb-4">Kelas: <strong>{{ $siswa->kelas->nama_kelas }}</strong></p>
    
            <a href="{{ route('cekstatus.form') }}" class="btn btn-secondary mt-4">Cek Lagi</a>
        </section>
    </div>
</body>
</html>
