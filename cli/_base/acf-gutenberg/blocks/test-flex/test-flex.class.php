<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class TestFlex extends Block
{
    public $block_title = 'Custom Section';


    public function init()
    {
    }


    public function set_fields()
    {

        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addRepeater('rows', ['min' => 1])
                ->addRepeater('columns', [
                    'min' => 1,
                    'max' => 4,
                    'layout' => 'block',
                ])
                    ->addFields( $this->get_modules() );


        $tabs['design']['fields'] = new FieldsBuilder($this->slug);
        $tabs['design']['fields']
            ->addImage('background_image', [
                'return_format' => 'url'
            ]);


        return $tabs;
    }
}
