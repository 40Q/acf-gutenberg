<?php
/**
 * Plugin Name:     Gutenberg Blocks
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          José Debuchy
 * Author URI:      YOUR SITE HERE
 * Text Domain:     gutenberg-blocks
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Gutenberg_Blocks
 */

namespace Gutenberg_Blocks;

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

// Load vendor folder
require_once __DIR__ . '/vendor/autoload.php';

// Helpers
include __DIR__ . '/lib/helpers.php';

// Enqueue JS and CSS
include __DIR__ . '/lib/enqueue-scripts.php';

// Initialize Builder
include __DIR__ . '/lib/initialize-builder.php';

//
include __DIR__ . '/lib/acf-blocks.php';

// Register meta boxes
include __DIR__ . '/lib/meta-boxes.php';

// Block Templates
include __DIR__ . '/lib/block-templates.php';

// Dynamic Blocks
include __DIR__ . '/react-blocks/12-dynamic/index.php';
