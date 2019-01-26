<?php

return [
    'name' => 'hero',
    'title' => __('Hero'),
    'description' => __('Default Hero Block.'),
    'render_callback' => 'Gutenberg_Blocks\Lib\my_acf_block_render_callback',
    'category' => 'common',
    'icon' => 'admin-comments',
    'keywords' => ['hero', 'parallax', 'video'],
];
