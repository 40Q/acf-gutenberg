<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Timeline extends Block
{
    public $block_title = 'Timeline';

    public function init()
    {
        // Use this method in extended classes
    }

    public function set_fields()
    {
        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addText('title')
            ->addTextarea('intro', ['rows' => '3'])
            ->addRepeater('timeline', [
                'button_label' => 'Add milestone',
                'layout' => 'row',
            ])
                ->addImage('image')
                ->addText('date')
                ->addTextarea('text', ['rows' => '2'])
            ->endRepeater();

        return $tabs;
    }
}
