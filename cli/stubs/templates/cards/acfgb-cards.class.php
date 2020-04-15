<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbCards extends Block
{
    public $block_title = 'ACFGB Cards';


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


        return $tabs;
    }
}