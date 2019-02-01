<?php

return [
    'name' => 'sample-block',
    'title' => __('Sample Block'),
    'description' => __('Sample Block.'),
    'render_callback' => 'ACF_Gutenberg\Lib\my_acf_block_render_callback',
    'category' => 'common',
    'icon' => 'admin-comments',
    'keywords' => ['sample-block'],
];
