<?php

return [
    'name' => 'slider',
    'title' => __('Slider'),
    'description' => __('A custom slider block.'),
    'render_callback' => 'Gutenberg_Blocks\Lib\my_acf_block_render_callback',
    'category' => 'formatting',
    'icon' => 'admin-comments',
    'keywords' => ['slider', 'quote'],
];
