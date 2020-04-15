<?php

namespace ACF_Gutenberg\Components;

use ACF_Gutenberg\Includes\Composer;
use ACF_Gutenberg\Includes\Flexible;
use ACF_Gutenberg\Includes\Lib;

class Row extends Composer {

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected $views = [
        'components.row.row',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function with( $data = false )
    {
        return [
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
            'class'   => $this->getClass( $data ) . $this->getRowClass( $data ),
        ];
    }
}
