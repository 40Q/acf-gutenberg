<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbHero extends Block
{
    public $block_title = 'ACFGB Hero';
    public $icon = 'edit';

    public $fields_config = [
        // CONTENT TAB
        'button' => true,
        'button_target' => true,
        'button_class' => true,

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
        $this->add_classes([$this->bg_image_size]);
    }

    public function set_fields()
    {
        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addTrueFalse('add_video',[
                'ui' => 1
            ])
            ->addTextarea('heading', [
                'rows' => 2
            ])
            ->addWysiwyg('intro');

        $tabs['design']['fields'] = new FieldsBuilder($this->slug);
        $tabs['design']['fields']
            ->addImage('bg_image', [
                'return_format' => 'id',
                'preview_size' => 'large',
            ])
            ->addSelect('bg_image_size', [
                'choices' => [
                    'full-height' => 'Full Height',
                    'medium-height' => 'Medium Height',
                    'small-height' => 'Small Height',
                ],
                'default_value' => 'medium-height'
            ])
            ->addTrueFalse('overlay',[
                'ui' => 1,
                'default_value' => 1,
            ])
            ->addSelect('text_align', [
                'choices' => [
                    'text-left' => 'Left',
                    'text-center' => 'Center',
                    'text-right' => 'Right',
                ],
                'default_value' => 'text-center'
            ]);
        return $tabs;
    }
}
