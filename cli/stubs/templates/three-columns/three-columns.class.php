<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ThreeColumns extends Block
{
    public $block_title = 'Three Columns';

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
            ]);

        return $tabs;
    }
}
