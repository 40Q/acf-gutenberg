<?php

namespace Gutenberg_Blocks\PhpBlocks;

class Hero extends Block
{
    public static $myvariablearray = [];

    public $params = [
        'name' => 'hero',
        'title' => __('Hero'),
        'description' => __('Default Hero Block.'),
        'render_callback' => 'Gutenberg_Blocks\Lib\my_acf_block_render_callback',
        'category' => 'common',
        'icon' => 'admin-comments',
        'keywords' => ['hero', 'parallax', 'video'],
    ];

    public function _construct($params)
    {
        $this->set_acf_fields();
        parent::__construct(self::hero(), 'hero', $params);
    }

    /**
     * Create Builder
     */
    private function set_acf_fields()
    {
        $fields = new FieldsBuilder('hero');
        $fields
            ->addImage('image', [
                'preview_size' => 'large'
            ])
            ->addText('heading')
            ->addTextarea('intro', [
                'rows' => 2
            ])
            ->addText('video_url')
            ->setLocation('block', '==', 'acf/hero');

        $block_content = $fields->build();

        self::createDynamic(str_replace('group_', '', $block_content['key']), array_column($block_content['fields'], 'name'));

        acf_add_local_field_group($block_content);
    }
}
