<h1>Dashboard Kepala Sekolah</h1>
<p>Selamat datang, {{ $user->username }} ({{ $user->role }})</p>
<form method="POST" action="{{ route('logout') }}">@csrf <button type="submit">Logout</button></form>
