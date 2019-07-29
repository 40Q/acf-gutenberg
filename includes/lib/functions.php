<?php

namespace ACF_Gutenberg\Lib;
use ACF_Gutenberg\Includes;;



function convert_to_class_name($str)
{
    $str = ucwords(str_replace('-', ' ', $str));
    $str = ucwords(str_replace('_', ' ', $str));
    return str_replace(' ', '', $str);
}

function get_compatibility_mode(){
    $compatibility_mode = false;
    $compatibility_mode = apply_filters('acfgb_compatibility_mode', $compatibility_mode);
    return $compatibility_mode;
}


function my_acf_block_render_callback($block)
{
    $slug = str_replace('acf/', '', $block['name']);
    $class_name = 'ACF_Gutenberg\\Blocks\\' . convert_to_class_name($slug);
    $block_instance = new $class_name($slug);

    // Set Position
    $block_instance->set_block_id();

    $plugin_blade_file = glob(ACFGB_PATH . "/resources/blocks/{$block_instance->slug}/{,*/}{*}blade.php", GLOB_BRACE);
    $theme_blade_file = glob(get_template_directory() . "/acf-gutenberg/blocks/{$block_instance->slug}/{,*/}{*}blade.php", GLOB_BRACE);


    $compatibility_mode = get_compatibility_mode();
    $old_props = ['block' => $block_instance];

    if ($compatibility_mode){
        $old_props['content'] = $block_instance->props['content'];
        $old_props['design'] = $block_instance->props['design'];
        $old_props['custom_classes'] = $block_instance->props['custom_classes'];
    }

    $props = array_merge(
        $block_instance->props,
        $old_props
    );

    if (isset($plugin_blade_file[0]) && file_exists($plugin_blade_file[0]) || isset($theme_blade_file[0]) && file_exists($theme_blade_file[0]) ) {
        echo Includes\ACF_Gutenberg::getInstance()->builder()->blade()
            ->view()->make("blocks.{$block_instance->slug}.{$block_instance->slug}", $props);
    } else {
        wp_die("Blade view not exist for $class_name Block");
    }
}

if (file_exists(get_template_directory() . '/acf-gutenberg/settings.php')) {
    include get_template_directory() . '/acf-gutenberg/settings.php';
}

if (file_exists(get_template_directory() . '/acf-gutenberg/global_fields.php')) {
    include get_template_directory() . '/acf-gutenberg/global_fields.php';
}
