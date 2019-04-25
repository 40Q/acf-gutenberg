<?php

namespace ACF_Gutenberg;

return [
    'name' => 'ACF Gutenberg',
    'version' => '1.0.3',
    'key' => 'acf-gutenberg',
    'namespace' => __NAMESPACE__,
    'requirements' => [
        [
            'type' => 'file_exists',
            'file' => $autoload = dirname(__DIR__) . '/vendor/autoload.php',
            'error.title' => sprintf(__('%s not found', 'acf-gutenberg'), 'Composer'),
            'error.message' => sprintf(__('You must run <code>composer install</code> from the %s plugin directory.', 'acf-gutenberg'), 'Plugin Name'),
        ],
        [
            'type' => 'version_compare',
            'name' => 'PHP',
            'required' => '7.1',
            'current' => phpversion()
        ],
        [
            'type' => 'version_compare',
            'name' => 'WordPress',
            'required' => '4.7',
            'current' => get_bloginfo('version')
        ]
    ],
];
