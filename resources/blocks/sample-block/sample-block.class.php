<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class SampleBlock extends Block
{
    public $block_title = 'Sample Block';

    public $fields_config = [
        'bg_color' => true,
        'text_color' => true,
        'button' => true,
    ];

    public function init()
    {
        // Use this method in extended classes
    }

    public function set_settings()
    {
        // Available options: title, icon, category, description, keywords
        $this->settings = [
            'title' => __($this->block_title),
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

        $this->fields = $fields;
    }
}
