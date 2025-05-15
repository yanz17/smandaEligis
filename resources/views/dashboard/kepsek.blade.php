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
    <title>Dashboard Kepala Sekolah</title>
</head>
<body>
        <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col p-4">
            <div class="flex justify-between items-center mb-6">
                <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">Menu</label>
                <h1 class="text-2xl font-bold">Dashboard Wali Kelas</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline">Logout</button>
                </form>
            </div>

            <div class="bg-base-200 p-6 rounded-lg">

                @php $tab = request('tab', 'dashboard'); @endphp
                @if ($tab === 'dashboard')
                <div>
                    <h2 class="text-xl font-semibold mb-4">Selamat Datang, {{ Auth::user()->name }}</h2>
                    <p>Ini adalah dashboard untuk Kepala Sekolah</p>
                    <x-chart-dashboard />
                </div>
                @endif

                @if ($tab === 'eligible')
                    <x-step-filter :search="$search" :tab="'eligible'" :showLangkah="false" />

                    <div class="tabs tabs-boxed mb-4">
                        <a href="?tab=eligible&jurusan=MIPA" class="tab {{ request('jurusan') !== 'MIPA' ? 'tab-active' : '' }}">MIPA</a>
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
                
                    @if(request('jurusan') === 'IPS' && isset($eligiblesIps))
                        <x-tabel-eligible :data="$eligiblesIps" />
                    @elseif(isset($eligiblesMipa))
                        <x-tabel-eligible :data="$eligiblesMipa" />
                    @else
                        <div class="text-center text-gray-500">Data eligible belum tersedia.</div>
                    @endif
                @endif
            </div>
        </div> 


        <div class="drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label> 
            <ul class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">
                <li class="mb-2 font-bold text-xl">SMANDA-ELIGIS</li>
                <li><a href="{{ route('dashboard.kepsek', ['tab' => 'dashboard']) }}" 
                    class="{{ request('tab') === 'dashboard' ? 'active bg-base-300 font-bold' : '' }}">
                    Dashboard</a></li>
                <li><a href="{{ route('dashboard.kepsek', ['tab' => 'eligible']) }}" 
                    class="{{ request('tab') === 'eligible' ? 'active bg-base-300 font-bold' : '' }}"
                    >Daftar Eligible</a></li>
            </ul>
        </div>
    </div>
</body>
</html>