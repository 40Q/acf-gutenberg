<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbSampleBlock extends Block
{
    public $block_title = 'Sample block';
    public $icon = 'edit';
    public $description = 'ACF Block';
    public $keywords = ['acf-block'];
    //public $category = false; //Default project category defined in theme directory


    public $fields_config = [
        // CONTENT TAB
        'button' => true,
            'button_target' => true,
            'button_class' => true,
            'button_icon' => true,

        // DESIGN TAB
        'section' => true,
            'bg_color' => true,
            'text_color' => true,
        'container' => true,

        // CLASS TAB
        'custom_id' => true,
        'custom_class' => true,
        'custom_button_class' => true,
    ];

    public function init()
    {
        // Use this method in extended classes
        $this->set_custom_prop();
    }

    public function set_custom_prop()
    {
        $this->content['custom_prop'] = "Here the custom prop for: ". $this->content['title'];

    }

    public function set_fields()
    {
        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addText('title', [
                'default_value' => 'Sample Title'
            ])
            ->addText('text', [
                'default_value' => 'Sample Text'
            ])
            ->addTextarea('intro', [
                'rows' => 2,
                'default_value' => 'Sample Introduction'
            ]);

        $tabs['design']['fields'] = new FieldsBuilder($this->slug);
        $tabs['design']['fields']
            ->addText('background', [
                'default_value' => 'Sample Title'
            ])
            ->addTrueFalse('parallax', [
                'default_value' => 0,
                'ui' => 1,
            ]);

        $tabs['class']['fields'] = new FieldsBuilder($this->slug);
        $tabs['class']['fields']
            ->addText('custom_class', [
                'default_value' => 'custom-class'
            ]);

        return $tabs;
    }
}