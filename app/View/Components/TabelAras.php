<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TabelAras extends Component
{
    /**
     * Create a new component instance.
     */
    public $hasilAras;
    public $langkah;
    public function __construct($hasilAras, $langkah = 'peringkat')
    {
        $this->hasilAras = $hasilAras;
        $this->langkah = $langkah;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tabel-aras', ['hasilAras' => $this->hasilAras]);
    }
}
