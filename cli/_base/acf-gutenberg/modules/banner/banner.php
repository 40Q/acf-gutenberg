<?php

namespace ACF_Gutenberg\Modules;

use ACF_Gutenberg\Includes\Composer;
use ACF_Gutenberg\Includes\Lib;

class Banner extends Composer {

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected $views = [
        'modules.banner.banner',
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
            'class'   => $this->getClassAnsStyles( $data ),
        ];
    }

//    public function getClassAnsStyles( $data = false )
//    {
//        $classes = $this->getClass( $data );
//        $classes .= ' banner__inner';
//        $classes .= $this->getStyleClass( $data, [
//            'bg_color',
//            'padding',
//            'margin',
//        ]);
//
//        return $classes;
//    }

}
