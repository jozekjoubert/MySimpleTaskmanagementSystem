<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $name;
    public $show;

    public function __construct($name, $show = false)
    {
        $this->name = $name;
        $this->show = $show;
    }

    public function render()
    {
        return view('components.modal');
    }
}
