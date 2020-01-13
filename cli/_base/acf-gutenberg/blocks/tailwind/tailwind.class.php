<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Tailwind extends Block
{
    public $block_title = 'Tailwind';


    public function init()
    {
        // Add classes as array
        $this->add_classes( ['class-array', 'p-md-3'] );
        // Add classes as string
        $this->add_classes('class-string');


        // Add props to array directly
        $query = new \WP_Query( array( 'post_type' => 'post' ) );
        $this->props['custom_prop'] = $query;

        // Add props by method
        $this->add_props([
            'custom_text' => 'This is a custom text',
            'block_cols' => 'col-md-8',
        ]);
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


        return $tabs;
    }
}
