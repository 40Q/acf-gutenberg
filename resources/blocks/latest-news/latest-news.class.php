<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Includes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class LatestNews extends Block
{

    public $block_title = 'Latest News';
    public $icon = 'admin-comments';

    public $fields_config = [
        'button' => true,
    ];

    public function init()
    {
        $this->content['latest_posts'] = $this->get_posts();
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

    private function get_posts()
    {
        $args = [
            'posts_per_page' => 3,
        ];

        $latest_posts = new \WP_Query($args);

        return $latest_posts;
    }
}
