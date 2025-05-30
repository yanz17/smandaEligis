<section class="bg bg-gray-800 py-6 h-screen shadow-xl flex flex-col items-center">
    <div class="flex content-center">
        <div class="avatar">
            <div class="w-8 rounded">
              <img
                src="/img/logo-smanda.png"
                alt="logo smanda" />
            </div>
        </div>
        <div class="flex-1 px-2 font-bold text-white text-2xl">
            Smanda <span class="text-yellow-300">Eligis</span>
        </div>
    </div>
    <ul class="w-full flex flex-col text-center mt-10 text-lg font-semibold text-gray-100">{{ $slot }}</ul>
</section>