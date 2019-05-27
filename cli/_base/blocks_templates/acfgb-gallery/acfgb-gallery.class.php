<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbGallery extends Block
{
    public $block_title = 'ACFGB Gallery';
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
        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addGallery('images', [
                'default_value' => 'Sample Text'
            ]);

        return $tabs;
    }
}
