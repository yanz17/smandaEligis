<!DOCTYPE html>
<html lang="en" x-data="{ tab: (new URLSearchParams(window.location.search)).get('tab') || 'dashboard', isDarkMode: localStorage.getItem('darkMode') === 'true' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <title>@yield('title') | SMANDA ELIGIS</title>
    <style>
        .sidebar-active {
            background-color: #4338ca;
            color: white;
            border-radius: 0.5rem;
        }
        
        .sidebar-item {
            transition: all 0.3s ease;
            margin-bottom: 0.25rem;
            border-radius: 0.5rem;
        }
        
        .sidebar-item:hover {
            background-color: #4f46e5;
            color: white;
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dark-mode {
            @apply bg-gray-900 text-white;
        }

        .light-mode {
            @apply bg-gray-50 text-gray-800;
        }
    </style>
</head>
<body x-bind:class="isDarkMode ? 'dark-mode' : 'light-mode'" class="transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar for large screens -->
        <div class="hidden lg:flex flex-col w-64 border-r border-gray-200" x-bind:class="isDarkMode ? 'bg-gray-800 border-gray-700 text-gray-300' : 'bg-white'">
            <!-- Logo and branding -->
            <div class="p-4 flex flex-col items-center border-b" x-bind:class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <div class="avatar flex justify-center mb-3">
                    <div class="w-20 rounded">
                        <img src="\img\logo-smanda.png" alt="SMANDA Logo" />
                    </div>
                </div>
                <h1 class="text-xl font-bold text-center" x-bind:class="isDarkMode ? 'text-yellow-400' : 'text-indigo-600'">SMANDA ELIGIS</h1>
                <p class="text-xs text-center mt-1 opacity-70">Sistem Informasi SMAN 2</p>
            </div>
            
            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-4 px-3">
                <ul class="space-y-2">
                    @yield('side')
                </ul>
            </div>
            
            <!-- User profile section -->
            <div class="p-4 border-t" x-bind:class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div>
                            <p class="text-sm font-medium">{{ Auth::user()->username }}</p>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm" title="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navigation -->
            <header class="flex items-center justify-between p-4 border-b shadow-sm" x-bind:class="isDarkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-200'">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold">@yield('header')</h1>
                </div>
                
                <div class="flex items-center space-x-3">
                    <!-- Dark mode toggle with localStorage update -->
                    <button @click="isDarkMode = !isDarkMode; localStorage.setItem('darkMode', isDarkMode)" class="btn btn-ghost btn-sm">
                        <i x-bind:class="isDarkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
                    </button>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-4 animate-fadeIn" x-bind:class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
                <div class="container mx-auto">
                    <div class="rounded-lg shadow-md p-6 animate-fadeIn" x-bind:class="isDarkMode ? 'bg-gray-800 text-gray-300' : 'bg-white'">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Sample script to make sidebar items show active state
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigation', () => ({
                init() {
                    const currentPath = window.location.pathname;
                    const links = document.querySelectorAll('.sidebar-item a');
                    
                    links.forEach(link => {
                        if (link.getAttribute('href') === currentPath) {
                            link.parentElement.classList.add('sidebar-active');
                        }
                    });
                }
            }));
        });
    </script>
</body>
</html>