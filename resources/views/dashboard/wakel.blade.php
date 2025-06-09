@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas')

@section('header')
    Dashboard Wali Kelas
        @if(Auth::user()->kelas)
        - {{ Auth::user()->kelas->nama_kelas }}
    @endif
@endsection

@section('content')
    {{-- Alert sukses --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="alert alert-success mb-4">
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-error mb-4" 
        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('error') }}
        </div>
    @endif
    @if (session('import_errors'))
        <div class="alert alert-error mb-4"
        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <ul>
                @foreach (session('import_errors') as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php $tab = request('tab', 'dashboard'); @endphp
    @if ($tab === 'dashboard')
    <div>
        <h2 class="text-xl font-semibold mb-4">Selamat Datang, {{ Auth::user()->username }}</h2>
        <p>Ini adalah dashboard untuk Wali Kelas.</p>
    </div>
    @endif

    @if ($tab === 'siswa')
    <div>
        <h2 class="text-xl font-semibold mb-4">Data Siswa</h2>
        <x-search-filter
            :kelas="$kelas"
            tab="siswa"
            :searchName="request('search_siswa')"
        />

        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center space-x-4">
                <input type="file" name="file" class="file-input file-input-bordered bg-transparent mb-3" required>
                <button class="btn btn-primary" type="submit">Import Excel</button>
            </div>
        </form>

        @isset($siswas)
            @php
                $sort = request('sort', 'asc');
            @endphp

            <x-tabel-siswa :siswas="$siswas" :sort="$sort"/>
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
        />

        <form action="{{ route('nilai.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="flex items-center space-x-4">
                <input type="file" name="file" accept=".xlsx,.xls" required class="file-input file-input-bordered bg-transparent" />
                <button type="submit" class="btn btn-primary">Impor Nilai</button>
            </div>
        </form>                    

        @isset($nilais)
                @php
                $sort = request('sort', 'asc');
            @endphp
            <x-tabel-nilai :nilais="$nilais" :sort="$sort"/>
        @endisset
    </div>
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
    
        @if(request('jurusan') === 'IPS' && isset($eligiblesIps))
            <x-tabel-eligible :data="$eligiblesIps" />
        @elseif(isset($eligiblesMipa))
            <x-tabel-eligible :data="$eligiblesMipa" />
        @else
            <div class="text-center text-gray-500">Data eligible belum tersedia.</div>
        @endif
    @endif
    </div>
@endsection

@section('side')
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'dashboard' }">
        <a href="{{ route('dashboard.wakel', ['tab' => 'dashboard']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-home w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'siswa' }">
        <a href="{{ route('dashboard.wakel', ['tab' => 'siswa']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-user-graduate w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Input Data Siswa</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'nilai' }">
        <a href="{{ route('dashboard.wakel', ['tab' => 'nilai']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-chart-bar w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Input Nilai</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'eligible' }">
        <a href="{{ route('dashboard.wakel', ['tab' => 'eligible']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-check-circle w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Daftar Eligible</span>
        </a>
    </li>
@endsection