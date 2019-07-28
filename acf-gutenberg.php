<?php
/**
 *
 * @package         ACF_Gutenberg
 * @since           1.1.0
 *
 * @wordpress-plugin
 * Plugin Name:     ACF Gutenberg
 * Plugin URI:      https://github.com/40Q/acf-gutenberg
 * Description:     Use and Create Gutenberg Blocks with ACF
 * Author:          Jos&eacute; Debuchy
 * Author URI:      http://40q.com.ar
 * Text Domain:     acf-gutenberg
 * Domain Path:     /resources/languages
 * Version:         1.2.5
 *
 *
 */

namespace ACF_Gutenberg;

use ACF_Gutenberg\Includes\Activator;
use ACF_Gutenberg\Includes\Deactivator;
use ACF_Gutenberg\Includes\ACF_Gutenberg;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
//  Exit if accessed directly.
defined('ABSPATH') || exit;

$plugin = (object) [
    'name' => 'ACF Gutenberg',
    'version' => '1.2.5',

    'requiredPHP' => '7.1',
    'requiredWP' => '4.7.0',

    'composer' => __DIR__ . '/vendor/autoload.php',
];
define('ACF_GUTENBERG_VERSION', $plugin->version);

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
        'title' => __('Invalid PHP version', 'acf-gutenberg'),
        'message' => __(
            sprintf('You must be using PHP %s or greater.', $plugin->requiredPHP),
            'acf-gutenberg'
        ),
    ];

/**
 * Ensure the correct WordPress version is being used.
 */
version_compare($plugin->requiredWP, get_bloginfo('version'), '<')
    ?: $errors[] = (object) [
        'title' => __('Invalid WordPress version', 'acf-gutenberg'),
        'message' => __(
            sprintf('You must be using WordPress %s or greater.', $plugin->requiredWP),
            'acf-gutenberg'
        ),
    ];

/**
 * Ensure dependencies can be loaded.
 */
file_exists($plugin->composer)
    ?: $errors[] = (object) [
        'title' => __('Autoloader not found', 'acf-gutenberg'),
        'message' => __(
            sprintf('You must run <code>composer install</code> from the %s plugin directory.', $plugin->name),
            'acf-gutenberg'
        ),
    ];

/**
 * Define constants for the plugin
 */
define('ACFGB_PATH', dirname(__FILE__));
define('ACFGB_FOLDER', basename(ACFGB_PATH));
define('ACFGB_URL', plugin_dir_url(__FILE__));

/**
 * If there are no errors, boot the plugin, or else display errors:
 * - and prevent activation if it was being activated.
 * - and disable the plugin (i.e. do nothing) if previously activated.
 */
if (empty($errors)) {
    /**
     * Include composer files
     */
	require_once $plugin->composer;

    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-acf-gutenberg-activator.php
     */
    function activate_acf_gutenberg()
    {
        require_once ACFGB_PATH . '/includes/class-Activator.php';
        Activator::activate();
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-acf-gutenberg-deactivator.php
     */
    function deactivate_acf_gutenberg()
    {
        require_once ACFGB_PATH . '/includes/class-Deactivator.php';
        Deactivator::deactivate();
    }

    register_activation_hook(__FILE__, 'activate_acf_gutenberg');
    register_deactivation_hook(__FILE__, 'deactivate_acf_gutenberg');

    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require_once ACFGB_PATH . '/includes/lib/functions.php';

    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.1.0
     */
    function run_acf_gutenberg()
    {
        $plugin = new ACF_Gutenberg();
        $plugin->run();
    }
    run_acf_gutenberg();
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
