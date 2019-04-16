<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;

class LatestNews extends Block
{
    public $latest_posts = '';

    public function __construct(array $args = [], $slug)
    {
        parent::__construct($args, $slug);
        $this->latest_posts = $this->get_posts();
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
