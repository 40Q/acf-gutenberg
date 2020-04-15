<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbTestimonials extends Block
{
    public $block_title = 'ACFGB Testimonials';

    public function init()
    {
        // Use this method in extended classes
    }

    public function set_fields()
    {
        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addText('text')
            ->addText('author');

        $tabs['design']['fields'] = new FieldsBuilder($this->slug);
        $tabs['design']['fields']
            ->addImage('bg_image', [
                'return_format' => 'id',
                'preview_size' => 'medium',
                'wrapper' => [
                    'width' => '33%'
                ],
            ]);

        return $tabs;
    }
}
