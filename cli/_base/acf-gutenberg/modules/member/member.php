<?php

namespace ACF_Gutenberg\Modules;

use ACF_Gutenberg\Includes\Composer;
use ACF_Gutenberg\Includes\Lib;

class Member extends Composer {

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected $views = [
        'modules.member.member',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function with( $data = false )
    {
        return [
            'attr' => ['class' => 'img-fluid rounded'],
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
            'class'   => $this->getClass( $data ),
        ];
    }

}
