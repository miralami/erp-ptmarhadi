<?php

namespace App\View\Components;

use App\Enums\OrderStatus;
use Illuminate\View\Component;
use Illuminate\View\View;

class Badge extends Component
{
    public string $label;
    public string $color;
    public string $icon;

    public function __construct(public OrderStatus $status)
    {
        $this->label = $status->label();
        $this->color = $status->color();
        $this->icon = $status->icon();
    }

    public function render(): View
    {
        return view('components.badge');
    }
}
