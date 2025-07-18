<nav>
    <div class="drawer">
      <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
      <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <div class="navbar bg-gray-800 w-full">
          <div class="avatar pl-3">
            <div class="w-8 rounded">
              <img
                src="/img/logo-smanda.png"
                alt="Tailwind-CSS-Avatar-component" />
            </div>
          </div>
          <div class="mx-2 flex-1 px-2 font-bold text-white text-2xl">Smanda <span class="text-yellow-500">Eligis</span></div>
          <div class="flex-none lg:hidden">
            <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                class="inline-block h-6 w-6 stroke-white">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </label>
          </div>
          <div class="hidden flex-none lg:block">
            <ul class="menu menu-horizontal font-medium text-white text-base">
              <!-- Navbar menu content here -->
              <li><a href="/">Home</a></li>
              <li><a href="/cekStatus">Cek Status</a></li>
              <li><a href="/login">Login</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="drawer-side z-50 md:z-0">
        <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu bg-gray-700 min-h-full w-80 p-4 text-white text-xl font-semibold">
          <!-- Sidebar content here -->
          <li><a href="/">Home</a></li>
          <li><a href="/cekStatus">Cek Status</a></li>
          <li><a href="/login">Login</a></li>
        </ul>
      </div>
    </div>
</nav>