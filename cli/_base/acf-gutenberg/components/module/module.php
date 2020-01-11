<?php

namespace ACF_Gutenberg\Components;

use ACF_Gutenberg\Includes\Composer;
use ACF_Gutenberg\Includes\Lib;

class Module extends Composer {

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected $views = [
        'components.module.module',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function with( $data = false )
    {
        return [
            'non_overwritable' => 'Value from composer',
            'composerTest'     => $this->test( $data ),
            'composer'         => 'Composer Working!',
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
            'message' => 'Filtered message',
            'class'   => $this->getClass( $data ),
        ];
    }

    /**
     *
     *
     * @return string
     */
    public function test( $data = false )
    {
        $text = 'composing from Composer Class!';
        if ( isset( $data['message'] ) ) {
            $text .= " <br> I can read the message: {$data['message']}";
        }
        return $text;
    }
}
