<?php

add_filter('acfgb_theme_colors', function ($colors) {
    // Replace the default colors
    $colors = [
        'primary' => 'Primary',
        'gray-lighter' => 'Gray lighter',
        'gray-light' => 'Gray Light',
        'orange' => 'Orange',
    ];

    return $colors;
});

add_filter('acfgb_button_classes', function ($classes) {

    // Replace the default colors
    $classes = [
        'btn-primary'=>'Primary',
        'btn-secondary'=>'Secondary',
        'gray-light' => 'Gray Light',
        'orange' => 'Orange',
    ];

    return $classes;
});



add_filter('acfgb_views', function ($views) {
    /**
     * Add custom views path
     */
    //$views[] = get_template_directory() . '/custom/view/path';
    return $views;
});

add_filter('acfgb_components', function ($components) {
    /**
     * Add custom components
     */
    $components['button'] = 'components.button';
    return $components;
});

add_filter('acfgb_block_paths', function ($block_paths) {
    /**
     * Add custom block_paths path
     */
    //$block_paths[] = get_template_directory() . '/custom/blocks/path';
    return $block_paths;
});

add_filter('acfgb_blocks_category', function ($blocks_category) {
    /**
     * Add custom blocks_category path
     */
    $blocks_category = [
        'slug' => 'my-project-blocks',
        'title' => __( 'My Project Blocks', 'acf-gutenberg' ),
        'icon'  => 'wordpress',
    ];

    return $blocks_category;
});






