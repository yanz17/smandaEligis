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

                    <h2 class="text-xl font-semibold mt-8 mb-4">Statistik Sekilas</h2>
                    <x-chart-dashboard />
                </div>
                @endif


                @if ($tab === 'user')
                <div>
                    <h2 class="text-xl font-semibold mb-4">Data User</h2>
                    @isset($users)
                        <x-tabel-user :users="$users" />
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

                    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" required>
                        <button class="btn btn-sm btn-primary" type="submit">Import Excel</button>
                    </form>

                    @isset($siswas)
                        <x-tabel-siswa :siswas="$siswas" :sort="$sort" />
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

                    <form action="{{ route('nilai.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <input type="file" name="file" accept=".xlsx,.xls" required class="file-input file-input-bordered bg-slate-600" />
                            <button type="submit" class="btn btn-primary">Impor Nilai</button>
                        </div>
                        @error('file')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                        @if(session('success'))
                            <div class="text-green-600 mt-2 text-sm">{{ session('success') }}</div>
                        @endif
                    </form>                    

                    @isset($nilais)
                        <x-tabel-nilai :nilais="$nilais" :sort="$sort"/>
                    @endisset
                </div>
                @endif

                @if ($tab === 'peringkat_mipa')
                    <h2 class="text-xl font-semibold mb-4">Peringkat</h2>

                    <x-step-filter :search="$searchSiswa" :langkah="request('langkah')" :tab="'peringkat_mipa'" />

                    @php $langkah = request('langkah', 'peringkat'); @endphp
                    <x-tabel-aras :hasil-aras="$hasilArasMipa" :langkah="$langkah" />
                @endif

                @if ($tab === 'peringkat_ips')
                    <h2 class="text-xl font-semibold mb-4">Peringkat</h2>

                    <x-step-filter :search="$searchSiswa" :langkah="request('langkah')" :tab="'peringkat_ips'" />

                    @php $langkah = request('langkah', 'peringkat'); @endphp
                    <x-tabel-aras :hasil-aras="$hasilArasIps" :langkah="$langkah" />
                @endif

                @if ($tab === 'eligible')
                    <x-step-filter :search="$searchSiswa" :tab="'eligible'" :showLangkah="false" />

                    <div class="tabs tabs-boxed mb-4">
                        <a href="?tab=eligible&jurusan=MIPA" class="tab {{ request('jurusan') !== 'IPS' ? 'tab-active' : '' }}">MIPA</a>
                        <a href="?tab=eligible&jurusan=IPS" class="tab {{ request('jurusan') === 'IPS' ? 'tab-active' : '' }}">IPS</a>
                    </div>

                    <div class="flex justify-between items-center mb-4" x-data="{ open: false }">
                        <h4 class="text-lg font-semibold">Data Siswa Eligible</h4>
                    
                        <div class="relative">
                            <button @click="open = !open" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 focus:outline-none">
                                Export Eligible
                                <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                    
                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow z-10">
                                <div class="px-4 py-2 font-semibold text-sm text-gray-700 border-b">MIPA</div>
                                <a href="{{ route('eligible.mipa.excel') }}" class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">Export Excel MIPA</a>
                                <a href="{{ route('eligible.mipa.pdf') }}" target="_blank" class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">Export PDF MIPA</a>
                    
                                <div class="px-4 py-2 font-semibold text-sm text-gray-700 border-t border-b">IPS</div>
                                <a href="{{ route('eligible.ips.excel') }}" class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">Export Excel IPS</a>
                                <a href="{{ route('eligible.ips.pdf') }}" target="_blank" class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">Export PDF IPS</a>
                            </div>
                        </div>
                    </div>
                
                    @if(request('jurusan') === 'IPS')
                        <x-tabel-eligible :data="$eligiblesIps" />
                    @else
                        <x-tabel-eligible :data="$eligiblesMipa" />
                    @endif
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
                    <a href="{{ route('dashboard.index', array_merge(request()->query(), ['tab' => 'peringkat_mipa'])) }}" 
                        class="{{ request('tab') === 'peringkat_mipa' ? 'active bg-base-300 font-bold' : '' }}">
                        Pemeringkatan MIPA
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.index', array_merge(request()->query(), ['tab' => 'peringkat_ips'])) }}" 
                        class="{{ request('tab') === 'peringkat_ips' ? 'active bg-base-300 font-bold' : '' }}">
                        Pemeringkatan IPS
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
