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
    <title>Home</title>
</head>
<body class="bg-gray-900">
    <header>
        {{-- Navbar --}}
        <x-navbar></x-navbar>

        {{-- Hero Element --}}
        <div
          class="hero min-h-screen bg-fixed"
          style="background-image: url(../img/hero-bg.JPG);">
          <div class="hero-overlay"></div>
          <div class="hero-content text-neutral-content text-center">
            <div class="max-w-md">
              <h1 class="mb-5 text-5xl font-bold">Simpati <span class="text-yellow-300">Prestasi</span></h1>
              <p class="mb-5">
                Selamat datang di portal informasi siswa eligible SNBP resmi SMAN 2 Kuningan
              </p>
              <button class="btn btn-primary"><a href="/cekStatus">Cek Status</a></button>
            </div>
          </div>
        </div>
    </header>

    <main class="pb-8 flex flex-col justify-center items-center">
      {{-- Tulisan perkenalan --}}
      <div class="lg:px-25">
        <section class="container mt-10 p-8 text-white">
          <h1 class="text-3xl md:text-4xl font-bold text-center">Apa itu Smanda <span class="text-yellow-300">Eligis</span>?</h1>
          <p class="text-lg/8 text-justify md:text-left md:text-xl/8.5 font-semibold pt-4 md:indent-8">Diambil dari kata Eligible, Smanda Eligis adalah portal resmi SMA Negeri 2 Kuningan yang memuat informasi mengenai siswa yang eligible untuk mengikuti Seleksi Nasional Berbasis Prestasi (SNBP). Siswa dapat dengan mudah mengecek status eligible-nya secara individu. Selain itu, Smanda Eligis dibuat built-in dengan pengelolaan data siswa, sehingga proses pengiriman informasi dapat dilakukan dengan cepat.</p>
        </section>
      </div>

      {{-- Cards --}}
      <section class="grid grid-flow-row grid-cols-1 lg:grid-cols-3 gap-2 items-center justify-center">
        <x-profile-card title="Cek Status">
          Cek status eligible secara individu dengan cepat dan mudah.
        </x-profile-card>
        <x-profile-card title="Integrasi Data">
          Dapat mengintegrasikan data siswa dengan nilai rapor dan prestasi dari semester awal hingga akhir. 
        </x-profile-card>
        <x-profile-card title="Metode Perhitungan ARAS">
          ARAS (Additive Ratio Assessment) merupakan metode yang mampu menentukan siswa terbaik dengan kriteria-kriteria yang sudah ditentukan secara optimal. 
        </x-profile-card>
      </section>
    </main>

    <footer class="footer sm:footer-horizontal bg-gray-800 text-white p-10">
      <aside>
        <div class="avatar">
          <div class="w-20 rounded">
            <img src="../img/logo-smanda.png" />
          </div>
        </div>
        <p class="font-bold">Smanda <span class="text-yellow-300">Eligis</span></p>
        <p>Copyright &copy; 2025 SMAN 2 Kuningan</p>
      </aside>
      <nav>
        <h6 class="footer-title">Follow Us On</h6>
        <a class="link link-hover" href="https://www.instagram.com/sman2kuningan.official/">Instagram</a>
        <a class="link link-hover" href="https://www.facebook.com/smandakng?locale=id_ID">Facebook</a>
        <a class="link link-hover" href="https://www.youtube.com/@sman2kuningan671">Youtube</a>
        <a class="link link-hover" href="https://www.tiktok.com/@sman2kng">Tiktok</a>
      </nav>
      <nav>
        <h6 class="footer-title">Contact</h6>
        <p>(0232) 871063</p>
        <p>sman2_kuningan@yahoo.com</p>
      </nav>
      <nav class="md:w-sm">
        <h6 class="footer-title">Address</h6>
        <p>Jl. Aruji Kartawinata No.16, Kuningan, Kec. Kuningan, Kabupaten Kuningan, Jawa Barat 45511</p>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.2548631397535!2d108.4859238!3d-6.979225199999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f14408f066a07%3A0x686f7e7775c9f69f!2sSMAN%202%20Kuningan!5e0!3m2!1sid!2sid!4v1745071029589!5m2!1sid!2sid" width="350" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </nav>
    </footer>
</body>
</html>