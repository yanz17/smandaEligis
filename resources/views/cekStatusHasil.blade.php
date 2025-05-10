<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Status Eligible</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
</head>
<body class="hero h-screen" style="background-image: url(/img/hero-bg.JPG)">
    <div class="hero-overlay"></div>
    <div class="hero-content flex flex-col justify-center items-center bg-black/75 rounded-4xl md:w-2xl text-white text-center p-4">
        <h1 class="text-3xl font-bold mb-4">Status Eligible SNBP 2025</h1>

        <p class="text-lg mb-2">Nama: <strong>{{ $siswa->nama }}</strong></p>
        <p class="text-lg mb-4">NIS: <strong>{{ $siswa->id }}</strong></p>

        @if ($eligible)
            <div class="alert alert-success shadow-lg">
                <span>✅ Anda <strong>eligible</strong> untuk mengikuti SNBP.</span>
            </div>
        @else
            <div class="alert alert-error shadow-lg">
                <span>❌ Anda <strong>tidak eligible</strong> untuk mengikuti SNBP.</span>
            </div>
        @endif

        <a href="{{ route('cekstatus.form') }}" class="btn btn-secondary mt-4">Cek Lagi</a>
    </div>
</body>
</html>
