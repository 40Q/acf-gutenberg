<?php

namespace ACF_Gutenberg\Lib;

/**
 * ACF INIT
 */
add_action('acf/init', function () {
    // check function exists
    if (function_exists('acf_register_block')) {
        collect(glob(\ACF_Gutenberg\_get_plugin_directory() . '/blocks/{,*/}settings.php', GLOB_BRACE))->map(function ($field) {
            $block_params = require_once $field;
            if (is_array($block_params)) {
                acf_register_block($block_params);
            }
        });
    }
});

/**
 * Initialize ACF Builder
 */
add_action('init', function () {
    // Register Classes/Controller
    collect(glob(\ACF_Gutenberg\_get_plugin_directory() . '/classes/*.php'))->map(function ($field) {
        return require_once $field;
    });

    // Register Fields
    collect(glob(\ACF_Gutenberg\_get_plugin_directory() . '/blocks/{,*/}fields.php', GLOB_BRACE))->map(function ($field) {
        return require_once $field;
    })->map(function ($fields) {
        foreach ($fields as $field) {
            $block_content = $field->build();
            \ACF_Gutenberg\Classes\Config::createDynamic(str_replace('group_', '', $block_content['key']), array_column($block_content['fields'], 'name'));
            acf_add_local_field_group($block_content);
        }
    });
// echo '<pre>';
    // print_r(\ACF_Gutenberg\Classes\Config::threecolumns());
    // echo '</pre>';
    // die();
}, 12);

/**
 * Render Fields
 */
function my_acf_block_render_callback($block)
{
    // convert name ("acf/testimonial") into path friendly slug ("testimonial")
    $slug = str_replace('acf/', '', $block['name']);

    $array = call_user_func(['ACF_Gutenberg\Classes\Config', $slug]);

    if (is_array($array)) {
        $class = 'ACF_Gutenberg\\Classes\\Block';
        $block = new $class($array, $slug);
    }

    if (file_exists(\ACF_Gutenberg\_get_plugin_directory() . "/blocks/{$slug}/index.blade.php")) {
        \render_blade_view("{$slug}.index", ['block' => $block]);
    }
}
