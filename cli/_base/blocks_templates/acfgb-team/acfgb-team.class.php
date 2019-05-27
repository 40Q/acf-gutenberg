<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbTeam extends Block
{
    public $block_title = 'ACFGB Team';
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
    }

    public function set_fields()
    {
        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addText('title')
            ->addText('intro')
            ->addRepeater('members', [
                'layout' => 'block'
            ])
                ->addText('name')
                ->addText('title')
                ->addTextarea('text')
                ->addImage('image')
            ->endRepeater();

        return $tabs;
    }
}
