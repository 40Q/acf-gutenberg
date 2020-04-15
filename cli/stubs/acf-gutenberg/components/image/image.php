<?php

namespace ACF_Gutenberg\Components;

use ACF_Gutenberg\Includes\Composer;
use ACF_Gutenberg\Includes\Lib;

class Image extends Composer {

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected $views = [
        'components.image.image',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function with( $data = false )
    {
        return [
            'container'    => $this->getContainer( $data ),
            'caption'      => $this->getCaption( $data ),
            'class_base'   => $this->getComponentClass(),
            'image_size'   => ( isset( $data['image_size'] ) ) ? isset( $data['image_size'] ) : 'full',
            'icon'         => ( isset( $data['icon'] ) ) ? isset( $data['icon'] ) : false,
            'attr'         => ( isset( $data['attr'] ) ) ? isset( $data['attr'] ) : false,
            'link'         => false,
            'aspect_ratio' => 'image-square',
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

    public function getContainer( $data ) {

        $container_class = $this->getComponentClass() . '__container';

        if ( ( isset( $data['container'] ) && $data['container'] === false ) ) {
            $container_class = false;
        }

        return $container_class;
    }

    public function getCaption( $data ) {
        $caption = false;

        if ( isset( $data['use_caption'] ) && $data['use_caption'] ) {
            // Use custom image as caption
            $caption = ( isset( $data['caption'] ) ) ? $data['caption'] : false;

            if ( ! $caption ) {
                $image = ( isset( $data['image'] ) ) ? $data['image'] : false;

                if ( isset( $image['caption'] ) && ! empty( $image['caption'] ) ) {
                    // Use image caption as caption
                    $caption = $image['caption'];

                }elseif ( isset( $image['description'] ) && ! empty( $image['description'] ) ){
                    // Use image description as caption
                    $caption = $image['description'];

                }elseif ( isset( $image['alt'] ) && ! empty( $image['alt'] ) ){
                    // Use image alt as caption
                    $caption = $image['alt'];

                }elseif ( isset( $image['title'] ) ) {
                    // Use image title as caption
                    $caption = $image['title'];
                }
            }
        }

        return $caption;
    }


}
