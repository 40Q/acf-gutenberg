<?php

namespace ACF_Gutenberg\Blocks;
use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class SampleBlock extends Block
{

    public function init()
    {
        // Use this method in extended classes
    }

    public function set_settings()
    {
        // Available options: title, icon, category, description, keywords
        $this->settings = [
            'title' => __('Sample Block'),
            'icon' => 'admin-comments',
        ];
    }

    public function set_fields()
    {
        $fields[$this->slug] = new FieldsBuilder($this->slug);
        $fields[$this->slug]
            ->addText('title', [
                'default_value' => 'Exampleeee'
            ])
            ->addText('text', [
                'default_value' => 'Sample Text'
            ])
            ->addTextarea('intro', [
                'rows' => 2,
                'default_value' => 'Sample Introduction'
            ])
            ->setLocation('block', '==', 'acf/'.$this->slug);

        $this->fields = $fields;
    }
}
