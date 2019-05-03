<?php

namespace ACF_Gutenberg\Lib;

/**
 * @param string $view
 * @param array $attributes
 */
function render_plugin_view($view, array $attributes = [])
{
    echo $GLOBALS['plugin_blade_engine']->view()->make($view, $attributes);
}

/**
 * @param string $view
 * @param array $attributes
 */
function render_theme_view($view, array $attributes = [])
{
    echo $GLOBALS['theme_blade_engine']->view()->make($view, $attributes);
}

/**
 * @param string $view
 * @param array $attributes
 *
 * @return string
 */
function get_rendered_blade_view($view, array $attributes = [])
{
    return $GLOBALS['blade_engine']->view()->make($view, $attributes);
}

/**
 * Modify
 */
function convert_to_class_name($str)
{
    $str = ucwords(str_replace('-', ' ', $str));
    $str = ucwords(str_replace('_', ' ', $str));
    return str_replace(' ', '', $str);
}

/**
 * Modify
 */
function do_actions($actions, $class = false, $prefix = null)
{
    foreach ($actions as $action):
        if (is_array($action) && count($action) >= 3) {
            // Action is a class method
            wp_die('Actions must have 1 or 2 index');
        } elseif (is_array($action) && count($action) == 2) {
            // Action has defined custom hook
            if ($class === false) {
                add_action($action[0], $action[1]);
            } else {
                add_action($action[0], [$class, $action[1]]);
            }
        } else {
            if (is_array($action)) {
                $action = $action[0];
            }
            if ($class === false) {
                add_action($prefix . $action, $action);
            } else {
                add_action($prefix . $action, [$class, $action]);
            }
            do_action($prefix . $action);
        }
    endforeach;
}

function my_acf_block_render_callback($block)
{
    $slug = str_replace('acf/', '', $block['name']);
    $class_name = 'ACF_Gutenberg\\Blocks\\' . convert_to_class_name($slug);
    $block_instance = new $class_name($slug);

    // Set Position
    $block_instance->set_block_id();

    $plugin_blade_file = glob(ACFGB_PATH_RESOURCES . "/blocks/{$block_instance->slug}/{,*/}{*}blade.php", GLOB_BRACE);
    $theme_blade_file = glob(get_template_directory() . "/acf-gutenberg/blocks/{$block_instance->slug}/{,*/}{*}blade.php", GLOB_BRACE);

    if (isset($plugin_blade_file[0]) && file_exists($plugin_blade_file[0])) {
        render_plugin_view("{$block_instance->slug}.{$block_instance->slug}", ['block' => $block_instance]);
    } elseif (isset($theme_blade_file[0]) && file_exists($theme_blade_file[0])) {
        render_theme_view("{$block_instance->slug}.{$block_instance->slug}", ['block' => $block_instance]);
    } else {
        wp_die("Blade view not exist for $class_name Block");
    }
}

if (file_exists(get_template_directory() . '/acf-gutenberg/settings.php')) {
    include get_template_directory() . '/acf-gutenberg/settings.php';
}
