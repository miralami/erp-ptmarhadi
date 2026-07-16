<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Table extends Component
{
    public function __construct(
        public array $headers = [],
        public bool $striped = false,
    ) {}

    public function render(): View
    {
        return view('components.table');
    }
}
