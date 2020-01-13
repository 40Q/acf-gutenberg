<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use Roots\Acorn\View\Composer;

class NewProps extends Block
{
    public $block_title = 'New props';


    public function init()
    {
        // Use this method in extended classes
    }


    public function set_fields()
    {
        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addText('title', [
                'default_value' => 'Sample Title'
            ])
            ->addImage('image', [
                'return_format' => 'url',
            ])
            ->addWysiwyg('intro', [
                'rows' => 2,
                'default_value' => 'Sample Introduction'
            ]);

        $tabs['design']['fields'] = new FieldsBuilder($this->slug);
        $tabs['design']['fields']
            ->addImage('background_image', [
                'return_format' => 'url'
            ]);


        return $tabs;
    }
}

