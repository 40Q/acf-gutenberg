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
            'text_align' => true,
        'container' => true,

        // CLASS TAB
        'custom_id' => true,
        'custom_class' => true,
        'custom_button_class' => true,
    ];

    public function init()
    {
        $query = new \WP_Query( array( 'post_type' => 'post' ) );
        $this->content->custom_prop = $query;
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
            ->addImage('image', [
                'return_format' => 'url',
                'preview_size' => 'thumbnail',
            ])
            ->addRepeater('repeater', [
                'max' => 3,
                'layout' => 'row',
                'button_label' => 'Add item',
            ])
                ->addText('text', [
                    'default_value' => 'Sample Repeater Text'
                ])
                ->addImage('image', [
                    'return_format' => 'url',
                    'preview_size' => 'thumbnail',
                ])
            ->endRepeater()
            ->addWysiwyg('intro', [
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