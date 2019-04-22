<?php

namespace ACF_Gutenberg\Blocks;
use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class OopBlock extends Block
{

    public function set_slug()
    {
        $this->slug = 'oop-block';
    }

    public function set_settings()
    {
        $this->settings = [
            'title' => __('OOP Block'),
            'description' => __('OOP Block.'),
            'category' => 'common',
            'icon' => 'menu',
            'keywords' => [$this->slug,],
        ];
    }

    public function set_fields()
    {
        $fields[$this->slug] = new FieldsBuilder($this->slug);
        $fields[$this->slug]
            ->addText('title')
            ->addText('content')
            ->addImage('img')
            ->setLocation('block', '==', 'acf/'.$this->slug);

        $this->fields = $fields;
    }
}
