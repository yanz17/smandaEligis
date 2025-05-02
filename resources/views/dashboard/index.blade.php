<!DOCTYPE html>
<html lang="en" x-data="{ tab: (new URLSearchParams(window.location.search)).get('tab') || 'dashboard' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Dashboard Guru BK</title>
</head>
<body>
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col p-4">
            <div class="flex justify-between items-center mb-6">
                <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">Menu</label>
                <h1 class="text-2xl font-bold">Dashboard Guru BK</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline">Logout</button>
                </form>
                <div>
                    <p>Tab aktif: <span x-text="tab"></span></p>
                </div>
            </div>

            <div class="bg-base-200 p-6 rounded-lg">
                {{-- Alert sukses --}}
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                        class="alert alert-success mb-4">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @php $tab = request('tab', 'dashboard'); @endphp
                @if ($tab === 'dashboard')
                <div>
                    <h2 class="text-xl font-semibold mb-4">Selamat Datang, {{ Auth::user()->name }}</h2>
                    <p>Ini adalah dashboard untuk Guru BK.</p>
                </div>
                @endif


                @if ($tab === 'user')
                <div>
                    <h2 class="text-xl font-semibold mb-4">Data User</h2>
                    @isset($users)
                        <x-tabel-user :users="$users" />
                        {{ $users->appends(request()->except('page'))->links() }}
                    @endisset
                </div>
                @endif

                @if ($tab === 'siswa')
                <div>
                    <h2 class="text-xl font-semibold mb-4">Data Siswa</h2>
                    <x-search-filter
                        :kelas="$kelas"
                        tab="siswa"
                        :searchName="request('search_siswa')"
                        :selectedKelas="request('kelas_id')"
                    />
                    @isset($siswas)
                        <x-tabel-siswa :siswas="$siswas" :sort="$sort" />
                        {{ $siswas->appends(request()->except('page'))->links() }}
                    @endisset
                </div>
                @endif
                

                @if ($tab === 'nilai')
                <div>
                    <h2 class="text-xl font-semibold mb-4">Data Nilai</h2>
                    <x-search-filter
                        :kelas="$kelas"
                        tab="nilai"
                        :searchName="request('search_nilai')"
                        :selectedKelas="request('kelas_id')"
                    />

                    @isset($nilais)
                        <x-tabel-nilai :nilais="$nilais" :sort="$sort"/>
                        {{ $nilais->appends(request()->except('page'))->links() }}
                    @endisset
                </div>
                @endif

                @if ($tab === 'peringkat')
                    <h2 class="text-xl font-semibold mb-4">Peringkat</h2>

                    <x-step-filter :search="$searchSiswa" :langkah="request('langkah')" />
                    
                    @php
                        $langkah = request('langkah', 'peringkat');
                    @endphp

                    <x-tabel-aras :hasil-aras="$hasilAras" :langkah="$langkah"/>
                @endif

                @if ($tab === 'eligible')
                    <x-tabel-eligible :eligibles="$eligibles"/>
                @endif
            </div>
        </div> 


        <div class="drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label> 
            <ul class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">
                <li class="mb-2 font-bold text-xl">SMANDA-ELIGIS</li>
                <li><a href="{{ route('dashboard.index', ['tab' => 'dashboard']) }}" 
                    class="{{ request('tab') === 'dashboard' ? 'active bg-base-300 font-bold' : '' }}">
                    Dashboard</a></li>
                <li><a href="{{ route('dashboard.user', ['tab' => 'user']) }}" 
                    class="{{ request('user') === 'user' ? 'active bg-base-300 font-bold' : '' }}"
                    >Kelola Data User</a></li>
                <li><a href="{{ route('dashboard.index', ['tab' => 'siswa']) }}" 
                    class="{{ request('tab') === 'siswa' ? 'active bg-base-300 font-bold' : '' }}"
                    >Kelola Data Siswa</a></li>
                <li><a href="{{ route('dashboard.index', ['tab' => 'nilai']) }}" 
                    class="{{ request('tab') === 'nilai' ? 'active bg-base-300 font-bold' : '' }}"
                    >Kelola Nilai</a></li>
                    <li>
                        <a href="{{ route('dashboard.index', array_merge(request()->query(), ['tab' => 'peringkat'])) }}" 
                            class="{{ request('tab') === 'peringkat' ? 'active bg-base-300 font-bold' : '' }}">
                            Pemeringkatan
                        </a>
                    </li>
                    <li><a href="{{ route('dashboard.index', ['tab' => 'eligible']) }}" 
                        class="{{ request('tab') === 'eligible' ? 'active bg-base-300 font-bold' : '' }}"
                        >Daftar Eligible</a></li>
                </ul>
        </div>
    </div>
</body>
</html>
