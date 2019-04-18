<?php

namespace ACF_Gutenberg\Blocks;
use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class OopBlock extends Block
{

    public function init()
    {
        // Use this method in extended classes
    }

    public function set_settings()
    {
        $this->settings = [
            'name' => 'oop-block',
            'title' => __('OOP Block'),
            'description' => __('OOP Block.'),
            'render_callback' => 'ACF_Gutenberg\Lib\my_acf_block_render_callback',
            'category' => 'common',
            'icon' => 'menu',
            'keywords' => ['oop-block'],
        ];
    }

    public function set_fields()
    {
        $fields['oop-block'] = new FieldsBuilder('oop-block');
        $fields['oop-block']
            ->addText('title')
            ->addText('content')
            ->addImage('img')
            ->setLocation('block', '==', 'acf/oop-block');

        $this->fields = $fields;
    }
}
