@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')
@section('header', 'Dashboard Kepala Sekolah')

@section('content')
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
@endsection

@section('side')
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'dashboard' }">
        <a href="{{ route('dashboard.kepsek', ['tab' => 'dashboard']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-home w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li class="sidebar-item" :class="{ 'sidebar-active': tab === 'eligible' }">
        <a href="{{ route('dashboard.kepsek', ['tab' => 'eligible']) }}" class="flex items-center p-2 text-base font-normal rounded-lg">
            <i class="fas fa-check-circle w-6 h-6 text-gray-500"></i>
            <span class="ml-3">Daftar Eligible</span>
        </a>
    </li>
@endsection