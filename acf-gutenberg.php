<?php
/**
 * Plugin Name:     ACF Gutenberg
 * Plugin URI:      https://github.com/40Q/acf-gutenberg
 * Description:     Use and Create Gutenberg Blocks with ACF
 * Author:          Jos&eacute; Debuchy
 * Author URI:      http://40q.com.ar
 * Text Domain:     acf-gutenberg
 * Domain Path:     /resources/languages
 * Version:         0.1.0
 *
 * @package         ACF_Gutenberg
 */

namespace ACF_Gutenberg;

//  Exit if accessed directly.
defined('ABSPATH') || exit;


$plugin = (object) [
    'name' => 'ACF Gutenberg',
    'version' => '0.1.0',

    'requiredPHP' => '7.1',
    'requiredWP' => '4.7.0',

    'composer' => __DIR__.'/vendor/autoload.php',
];


define( 'ACFGB_PATH', dirname( __FILE__ ) );
define( 'ACFGB_FOLDER', basename( ACFGB_PATH ) );
define( 'ACFGB_URL', plugin_dir_url( __FILE__ ) );


define( 'ACFGB_PATH_RESOURCES', dirname( __FILE__ ) . '/resources' );
define( 'ACFGB_URL_RESOURCES', ACFGB_URL . '/resources' );
define( 'ACFGB_PATH_SRC', dirname( __FILE__ ) . '/src' );
define( 'ACFGB_URL_SRC', ACFGB_URL . '/src' );
define( 'ACFGB_PATH_BIN', dirname( __FILE__ ) . '/bin' );
define( 'ACFGB_URL_BIN', ACFGB_URL . '/bin' );


/** Initialize error collector */
$errors = [];



/**
 * Helper function for displaying errors.
 * @param $errors []
 * @param $is_admin_notice bool
 */
$display_errors = function ($errors, $is_admin_notice) use ($plugin) {
    $content = '';
    $header = $is_admin_notice ? "<h4>{$plugin->name} disabled, because an error has occurred:</h4>" : '';
    $styles = $is_admin_notice ? '' : '<style>.error{color:#444;font:13px sans-serif}</style>';
    foreach ($errors as $error) {
        $content .= "<p><strong>{$error->title}:</strong> {$error->message}</p>";
    }
    echo "{$styles}<div class=\"error\">{$header}{$content}</div>";
};

/**
 * Ensure the correct PHP version is being used.
 */
version_compare($plugin->requiredPHP, phpversion(), '<')
    ?: $errors[] = (object) [
    'title' => __('Invalid PHP version', 'plugin-name'),
    'message' => __(
        sprintf('You must be using PHP %s or greater.', $plugin->requiredPHP),
        'plugin-name'
    ),
];

/**
 * Ensure the correct WordPress version is being used.
 */
version_compare($plugin->requiredWP, get_bloginfo('version'), '<')
    ?: $errors[] = (object) [
    'title' => __('Invalid WordPress version', 'plugin-name'),
    'message' => __(
        sprintf('You must be using WordPress %s or greater.', $plugin->requiredWP),
        'plugin-name'
    ),
];

/**
 * Ensure dependencies can be loaded.
 */
file_exists($plugin->composer)
    ?: $errors[] = (object) [
    'title' => __('Autoloader not found', 'plugin-name'),
    'message' => __(
        sprintf('You must run <code>composer install</code> from the %s plugin directory.', $plugin->name),
        'plugin-name'
    ),
];

/**
 * If there are no errors, boot the plugin, or else display errors:
 * - and prevent activation if it was being activated.
 * - and disable the plugin (i.e. do nothing) if previously activated.
 */
if (empty($errors)) {
    include ACFGB_PATH_SRC . '/lib/functions.php';
    include ACFGB_PATH_SRC . '/classes/controller-Assets.php';
    include ACFGB_PATH_SRC . '/classes/Config.php';
    include ACFGB_PATH_SRC . '/classes/Block.php';
    include ACFGB_PATH_SRC . '/classes/controller-ACF_Blocks.php';
    require_once $plugin->composer;
    (new Plugin(__FILE__, $plugin->name, $plugin->version))->run();
} else {
    /** This only runs if the plugin was just activated */
    register_activation_hook(__FILE__, function () use ($errors, $display_errors) {
        $display_errors($errors, false);
        die(1);
    });
    /** If previously activated, we create an admin notice. */
    add_action('admin_notices', function () use ($errors, $display_errors) {
        $display_errors($errors, true);
    });
}