<?php

namespace ACF_Gutenberg\Blocks;
use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class LatestNews extends Block
{

    public function init()
    {
        //$this->latest_posts = $this->get_posts();
        $this->latest_posts_array = $this->get_posts();
    }

    public function set_settings()
    {
        // Available options: title, icon, category, description, keywords
        $this->settings = [
            'title' => __('Latest News'),
            'icon' => 'admin-comments',
        ];
    }

    public function set_fields()
    {
        $fields[$this->slug] = new FieldsBuilder($this->slug);
        $fields[$this->slug]
            ->addText('title')
            ->setLocation('block', '==', 'acf/'.$this->slug);

        $this->fields = $fields;
    }

    private function get_posts()
    {
        $args = [
            'posts_per_page' => 3,
        ];

        //$latest_posts = new \WP_Query($args);

        $latest_posts = [
          ['title' => 'Post 2', 'content' => 'content 2'],
          ['title' => 'Post 3', 'content' => 'content 3'],
          ['title' => 'Post 1', 'content' => 'content 1'],
        ];

        return $latest_posts;
    }
}
