<?php

namespace ACF_Gutenberg\Lib;

/**
 * @param string $view
 * @param array $attributes
 */
function render_blade_view($view, array $attributes = [])
{
    echo $GLOBALS['blade_engine']->view()->make($view, $attributes);
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
