<?php

namespace ACF_Gutenberg\Blocks;
use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class LatestNews extends Block
{
    public $latest_posts = '';

    public function init()
    {
        //$this->latest_posts = $this->get_posts();
    }

    public function set_settings()
    {
        $this->settings = array(
            'name' => 'latest-news',
            'title' => __('Latest News'),
            'description' => __('Latest News.'),
            'render_callback' => array($this, 'render_block'),
            'category' => 'common',
            'icon' => 'menu',
            'keywords' => ['latest-news'],
        );
    }

    public function set_fields()
    {
        $fields['latest-news'] = new FieldsBuilder('latest-news');
        $fields['latest-news']
            ->addText('title')
            ->setLocation('block', '==', 'acf/latest-news');

        $this->fields = $fields;
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
