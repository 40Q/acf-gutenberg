<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;

class Section extends Component
{

    /**
     * The block objetc.
     *
     * @var string
     */
    public $block;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $block = null)
    {
        $this->block = $block;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return $this->view('components.section');
    }
}
