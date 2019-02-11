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
