<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Login - SMANDA ELIGIS</title>
</head>
<body class="hero min-h-screen" style="background-image: url(/img/hero-bg.JPG)">
    <div class="hero-overlay"></div>
    <section class="flex items-center w-full" style="padding: 0rem 32.5rem 0rem 32.5rem">
        <div class="bg-black/80 pt-4 pb-8 px-8 rounded-3xl shadow-md w-full">
            <span>
                <a href="/" class="link-hover text-blue-600 text-sm font-normal">Kembali</a>
            </span>
            <h2 class="text-3xl font-bold mb-6 text-center text-yellow-500">Login</h2>
            
            @if (session('error'))
                <div class="alert alert-error shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728m0-12.728l12.728 12.728" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
    
            <form method="POST" action="{{ route('login') }}">
                @csrf
    
                <div style="padding: 0 3.5rem 0 3.5rem">
                    <div class="mb-4 text-white">
                        <label for="username" class="mb-5 font-medium text-lg">Username</label>
                        <input type="text" name="username" class="w-full p-2 border rounded" placeholder="Isi username" required autofocus>
                    </div>
        
                    <div class="mb-4 text-white">
                        <label for="username" class="mb-5 font-medium text-lg">Password</label>
                        <input type="password" name="password" class="w-full p-2 border rounded" placeholder="Isi password" required>
                    </div>
                </div>
                
                <div style="padding: 0 10rem 0 10rem">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Login</button>
                </div>
            </form>
        </div>
    </section>

</body>
</html>
