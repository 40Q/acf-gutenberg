<?php

namespace ACF_Gutenberg\Components;

use ACF_Gutenberg\Includes\Composer;
use ACF_Gutenberg\Includes\Lib;

class Button extends Composer {

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected $views = [
        'components.button.button',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function with( $data = false )
    {
        $target = false;
        $title  = false;
        $url    = false;

        if ( isset( $data['link'] ) && is_array( $data['link'] ) ) {
            $link   = $data['link'];
            $target = ( isset( $link['target'] ) ) ? $link['target'] : false;
            $title  = ( isset( $link['title'] ) ) ? $link['title'] : false;
            $url    = ( isset( $link['url'] ) ) ? $link['url'] : false;
        }

        return [
            'target' => $target,
            'title'  => $title,
            'url'    => $url,
            'tag'    => 'a',
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
            'class'   => $this->getButtonClass( $data ),
        ];
    }

    /**
     * Get button class.
     *
     * @return string
     */
    public function getButtonClass( $data )
    {
        $button_class = Lib\config('builder.btn_class_base');
        $button_class .= ( isset( $data['style'] ) ) ? ' ' . $data['style'] : false;
        $button_class .= ' ' . $this->getClass( $data );

        return $button_class;
    }

}
