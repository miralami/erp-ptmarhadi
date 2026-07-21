<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Badge extends Component
{
    public function __construct(
        public string $label,
        public string $color = 'slate',
    ) {}

    public function render(): View
    {
        return view('components.badge');
    }
}
