@extends('layouts.app')

@section('title','Dashboard Guru BK')
@section('header', 'Dashboard Guru BK')

@section('content')
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
            <h2 class="text-xl font-semibold mb-4">Selamat Datang, {{ Auth::user()->username }}</h2>
            
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

            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                @csrf
                <input type="file" name="file" class="file-input file-input-bordered bg-transparent" required>
                <button class="btn btn-primary" type="submit">Import Excel</button>
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
                    <input type="file" name="file" accept=".xlsx,.xls" required class="file-input file-input-bordered bg-transparent" />
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

        @if ($tab === 'request')
            <x-change-request-table :requests="$requests"></x-change-request-table>
        @endif
</div>
@endsection

@section('side')
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'dashboard' }">
        <a href="{{ route('dashboard.index', ['tab' => 'dashboard']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-home w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'user' }">
        <a href="{{ route('dashboard.user', ['tab' => 'user']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-users w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Kelola Data User</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'siswa' }">
        <a href="{{ route('dashboard.index', ['tab' => 'siswa']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-user-graduate w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Kelola Data Siswa</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'nilai' }">
        <a href="{{ route('dashboard.index', ['tab' => 'nilai']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-chart-bar w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Kelola Nilai</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'peringkat_mipa' }">
        <a href="{{ route('dashboard.index', array_merge(request()->query(), ['tab' => 'peringkat_mipa'])) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-flask w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Pemeringkatan MIPA</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'peringkat_ips' }">
        <a href="{{ route('dashboard.index', array_merge(request()->query(), ['tab' => 'peringkat_ips'])) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-landmark w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Pemeringkatan IPS</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'eligible' }">
        <a href="{{ route('dashboard.index', ['tab' => 'eligible']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-check-circle w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Daftar Eligible</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'request' }">
        <a href="{{ route('dashboard.index', ['tab' => 'request']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-envelope w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Request</span>
        </a>
    </li>
@endsection
