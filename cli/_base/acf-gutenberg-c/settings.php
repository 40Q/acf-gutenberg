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






