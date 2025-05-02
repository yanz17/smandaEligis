<label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label> 
<ul class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">
    <li class="mb-2 font-bold text-xl">SMANDA-ELIGIS</li>
    <li><a href="{{ route('dashboard.siswa') }}" class="{{ request()->routeIs('dashboard.siswa') ? 'active' : '' }}">Siswa</a></li>
</ul>
