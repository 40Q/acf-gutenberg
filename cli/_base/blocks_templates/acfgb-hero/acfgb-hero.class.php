<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbHero extends Block
{
    public $fields_config = [
        'bg_color' => true,
    ];

    public function init()
    {
        // Use this method in extended classes
        $this->add_classes([$this->bg_image_size]);
    }

    public function set_settings()
    {
        // Available options: title, icon, category, description, keywords
        $this->settings = [
            'title' => __('Hero'),
            'icon' => 'edit',
        ];
    }

    public function set_fields()
    {
        $fields[$this->slug] = new FieldsBuilder($this->slug);
        $fields[$this->slug]
            ->addTab('Content', [
                'wrapper' => [
                    'width' => '100%',
                    'class' => 'acfgb-tab acfgb-tab-content acfgb-tab-content-' . $this->slug,
                    'id' => 'acfgb-tab-content-' . $this->slug,
                ]
            ])
                ->addImage('bg_image', [
                    'return_format' => 'id',
                    'preview_size' => 'large',
                ])
                ->addTrueFalse('add_video',[
                    'ui' => 1
                ])
                ->addTrueFalse('overlay',[
                    'ui' => 1,
                    'default_value' => 1,
                ])
                ->addSelect('bg_image_size', [
                    'choices' => [
                        'full-height' => 'Full Height',
                        'medium-height' => 'Medium Height',
                        'small-height' => 'Small Height',
                    ],
                    'default_value' => 'medium-height'
                ])
                ->addSelect('text_align', [
                    'choices' => [
                        'text-left' => 'Left',
                        'text-center' => 'Center',
                        'text-right' => 'Right',
                    ],
                    'default_value' => 'text-center'
                ])
                ->addTextarea('heading', [
                    'rows' => 2
                ])
                ->addWysiwyg('intro');

        $this->fields = $fields;
    }
}
