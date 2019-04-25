<?php
use ACF_Gutenberg\AcfGutenberg;

/**
 * Plugin Name:     ACF Gutenberg
 * Plugin URI:      https://github.com/40Q/acf-gutenberg
 * Description:     Use and Create Gutenberg Blocks with ACF
 * Author:          Jos&eacute; Debuchy
 * Author URI:      http://40q.com.ar
 * Text Domain:     acf-gutenberg
 * Domain Path:     /resources/languages
 * Version:         1.0.3
 *
 * @package         ACF_Gutenberg
 */

define('ACFGB_PATH', dirname(__FILE__));
define('ACFGB_FOLDER', basename(ACFGB_PATH));
define('ACFGB_URL', plugin_dir_url(__FILE__));

define('ACFGB_PATH_RESOURCES', dirname(__FILE__) . '/resources');
define('ACFGB_URL_RESOURCES', ACFGB_URL . '/resources');
define('ACFGB_PATH_SRC', dirname(__FILE__) . '/src');
define('ACFGB_URL_SRC', ACFGB_URL . '/src');
define('ACFGB_PATH_BIN', dirname(__FILE__) . '/bin');
define('ACFGB_URL_BIN', ACFGB_URL . '/bin');

add_action('plugins_loaded', function () {
    $meta = require_once __DIR__ . '/resources/meta.php';
    $meta['plugin'] = __FILE__;
    $meta['basename'] = plugin_basename(__FILE__);

    $errors = array_reduce($meta['requirements'], function ($errors, $requirement) use ($meta) {
        $test = false;
        switch ($requirement['type']) {
            case 'version_compare':
                $title = $requirement['error.title'] ?? sprintf(
                    __('Invalid %s version', 'acf-gutenberg'),
                    $requirement['name']
                );
                $message = $requirement['error.message'] ?? sprintf(
                    __('You must be using %1s version %2s or greater.', 'acf-gutenberg'),
                    $requirement['name'],
                    $requirement['required']
                );
                $test = version_compare($requirement['required'], $requirement['current'], '<');
                break;

            case 'file_exists':
                $title = $requirement['error.title'] ?? __('File not found', 'acf-gutenberg');
                $message = $requirement['error.message'] ?? sprintf(
                    __('File <code>%s</code> cannot be located.', 'acf-gutenberg'),
                    $requirement['file']
                );
                $test = file_exists($requirement['file']);
                break;

            case 'custom':
                $test = is_callable($requirement['test'] ?? true)
                    ? call_user_func($requirement['test'], $requirement)
                    : $requirement['test'];
                // Intentional fallthrough
                // no break
            default:
                $title = $requirement['error.title'] ?? __('Something went wrong', 'acf-gutenberg');
                $message = $requirement['error.message'] ?? sprintf(
                    __('One of the %s plugin\'s requirements have not been met.', 'acf-gutenberg'),
                    'Plugin Name'
                );
                break;
        }

        if (!$test) {
            $errors[] = compact('title', 'message');
        }

        return $errors;
    }, []);

    /**
     * If there are errors else display errors
     * - and prevent activation if it was being activated.
     * - and disable the plugin (i.e. do nothing) if previously activated.
     */
    if (!empty($errors)) {
        /**
         * Helper function for displaying errors.
         * @param $errors []
         */
        $display_errors = function () use ($meta, $errors) {
            $header = sprintf(
                __('<h4>%s is disabled because an error has occurred</h4>', 'acf-gutenberg'),
                $meta['name']
            );
            $styles = '';
            $is_admin = doing_action('admin_notices');

            if (!$is_admin) {
                $header = '';
                $styles = ' style="color:#444;font:13px sans-serif"';
            }

            $content = array_reduce($errors, function ($html, $error) {
                return $html . "<p><strong>{$error['title']}:</strong> {$error['message']}</p>";
            }, '');

            echo "<div class=\"error\"{$styles}>{$header}{$content}</div>";

            if (!$is_admin) {
                die(1);
            }
        };

        /** This only runs if the plugin was just activated */
        register_activation_hook(__FILE__, $display_errors);

        /** If previously activated, we create an admin notice. */
        add_action('admin_notices', $display_errors);

        return;
    }

    require_once $autoload;

	$app = Roots\app();

    $app->booting(function () use ($meta, $app) {
        $app->singleton('acf-gutenberg.meta', function () use ($meta) {
            return new Roots\Clover\Meta($meta);
        });
        $app->register(ACF_Gutenberg\AcfGutenbergServiceProvider::class);
	});

	include ACFGB_PATH_SRC . '/lib/functions.php';
	include ACFGB_PATH_SRC . '/classes/controller-Assets.php';
	include ACFGB_PATH_SRC . '/classes/Config.php';
	include ACFGB_PATH_SRC . '/classes/Block.php';
	//include ACFGB_PATH_SRC . '/classes/controller-ACF_Blocks.php';

    Roots\add_actions(['after_setup_theme', 'rest_api_init'], 'Roots\bootloader', 5);
});
