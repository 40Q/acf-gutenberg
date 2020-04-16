<?php

namespace ACF_Gutenberg\Components;

use ACF_Gutenberg\Includes\Composer;
use ACF_Gutenberg\Includes\Lib;

class Block extends Composer {

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected $views = [
        'components.block.block',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function with( $data = false )
    {
        return [
            'id'     => ( isset( $data['block'] ) ) ? $data['block']->id : null,
            'styles' => ( isset( $data['block'] ) ) ? $data['block']->get_styles() : false,
        ];
    }

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function override( $data = false )
    {
        return [
            'class' => ( isset( $data['block'] ) ) ? $this->getClass( $data ) . ' ' . $data['block']->get_classes() : $this->getClass( $data ),
        ];
    }

}
