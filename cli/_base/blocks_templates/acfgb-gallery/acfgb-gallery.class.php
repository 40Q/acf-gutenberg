<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbGallery extends Block
{
    public $block_title = 'ACFGB Gallery';

    public $fields_config = [
        'bg_color' => true,
        'text_color' => true,
        'button' => true,
    ];

    public function init()
    {
        // Use this method in extended classes
        $this->set_custom_prop();
    }

    public function set_custom_prop()
    {
        $this->custom_prop = "Here the custom prop for: " . $this->title;
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
            ->addGallery('images', [
                'default_value' => 'Sample Text'
            ]);

        $this->fields = $fields;
    }
}
