<?php

namespace Gutenberg_Blocks\Lib;

/**
 * ACF INIT
 */
add_action('acf/init', function () {
    // check function exists
    if (function_exists('acf_register_block')) {
        collect(glob(\Gutenberg_Blocks\_get_plugin_directory() . '/php-blocks/{,*/}index.php', GLOB_BRACE))->map(function ($field) {
            $block_params = require_once $field;
            if (is_array($block_params)) {
                acf_register_block($block_params);
            }
        });
    }
});

function my_acf_block_render_callback($block)
{
    // convert name ("acf/testimonial") into path friendly slug ("testimonial")
    $slug = str_replace('acf/', '', $block['name']);

    $block = '';
    $array = call_user_func(['Gutenberg_Blocks\Classes\Config', $slug]);

    if (is_array($array)) {
        $class = 'Gutenberg_Blocks\\Classes\\Block';
        $block = new $class($array, $slug);
    }

    if (file_exists(\Gutenberg_Blocks\_get_plugin_directory() . "/php-blocks/{$slug}/template.php")) {
        include \Gutenberg_Blocks\_get_plugin_directory() . "/php-blocks/{$slug}/template.php";
    }
}
