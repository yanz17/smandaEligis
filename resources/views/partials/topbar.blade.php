<div class="flex justify-between items-center mb-6">
    <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">Menu</label>
    <h1 class="text-2xl font-bold">@yield('title')</h1>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline">Logout</button>
    </form>
</div>
