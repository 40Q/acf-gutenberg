<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbTestimonials extends Block
{
    public $fields_config = [
        'bg_color' => true,
    ];

    public function init()
    {
        // Use this method in extended classes
    }

    public function set_settings()
    {
        // Available options: title, icon, category, description, keywords
        $this->settings = [
            'title' => __('Testimonials'),
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
                    'preview_size' => 'medium',
                    'wrapper' => [
                        'width' => '33%'
                    ],
                ])
                ->addText('text')
                ->addText('author');

        $this->fields = $fields;
    }
}
