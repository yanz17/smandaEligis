@props(['title' => ''])

<div class="card flex flex-col self-center bg-gray-700 hover:bg-black text-neutral-content w-xs md:w-sm h-full">
    <div class="card-body items-center text-center">
      <h2 class="card-title text-lg md:text-xl">{{ $title }}</h2>
      <p class="text-sm md:text-lg">{{ $slot }}</p>
    </div>
</div>
