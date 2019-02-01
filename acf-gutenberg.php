<?php
/**
 * Plugin Name:     ACF Gutenberg
 * Description:     Use and Create Gutenberg Blocks with ACF
 * Author:          Jos&eacute; Debuchy
 * Author URI:      http://40q.com.ar
 * Text Domain:     acf-gutenberg
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         ACF_Gutenberg
 */

namespace ACF_Gutenberg;

//  Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Gets this plugin's absolute directory path.
 *
 * @since  2.1.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_directory()
{
    return __DIR__;
}

/**
 * Gets this plugin's URL.
 *
 * @since  2.1.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_url()
{
    static $plugin_url;

    if (empty($plugin_url)) {
        $plugin_url = plugins_url(null, __FILE__);
    }

    return $plugin_url;
}

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

// Load vendor folder
require_once __DIR__ . '/vendor/autoload.php';

// Loaf Blade Templating
include __DIR__ . '/lib/blade-templating.php';

// Helpers
// include __DIR__ . '/lib/helpers.php';

// Enqueue JS and CSS
include __DIR__ . '/lib/enqueue-scripts.php';

// Register PHP Blocks
include __DIR__ . '/lib/acf-blocks.php';
