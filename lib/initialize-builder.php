<?php

namespace Gutenberg_Blocks\Lib;

/**
 * Initialize ACF Builder
 */
add_action('init', function () {
    // Register Classes/Controller

    collect(glob(\Gutenberg_Blocks\_get_plugin_directory() . '/classes/*.php'))->map(function ($field) {
        return require_once $field;
    });

    // Test
    // echo '<pre>';
    // print_r(collect(glob(config('theme.dir') . '/app/fields/*.php'))->map(function ($field) {
    //     return require_once $field;
    // }));
    // echo '</pre>';
    // die();

    // Register Fields
    collect(glob(\Gutenberg_Blocks\_get_plugin_directory() . '/php-blocks/{,*/}fields.php', GLOB_BRACE))->map(function ($field) {
        return require_once $field;
    })->map(function ($fields) {
        foreach ($fields as $field) {
            $block_content = $field->build();
            \Gutenberg_Blocks\Classes\Config::createDynamic(str_replace('group_', '', $block_content['key']), array_column($block_content['fields'], 'name'));
            // echo '<pre>';
            // print_r($block_content);
            // echo '</pre>';
            acf_add_local_field_group($block_content);
        }
    });
}, 12);
