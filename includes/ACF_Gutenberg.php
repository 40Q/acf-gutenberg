<?php

namespace ACF_Gutenberg\Includes;

use ACF_Gutenberg\Admin;
use ACF_Gutenberg\Resources;
use ACF_Gutenberg\Includes\Loader;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.0
 * @package    ACF_Gutenberg
 * @subpackage ACF_Gutenberg/includes
 */

class ACF_Gutenberg
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.1.0
     * @access   protected
     * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The builder that's responsible for register and compile blocks and components path
     *
     * @since    1.1.0
     * @access   protected
     * @var      Builder    $builder    compile blocks and components.
     */
    protected $builder;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.1.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.1.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    private $_instance = null;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.1.0
     */
    public function __construct()
    {
        if (defined('ACF_GUTENBERG_VERSION')) {
            $this->version = ACF_GUTENBERG_VERSION;
        } else {
            $this->version = '1.1.0';
        }
        $this->plugin_name = 'ACF Gutenberg';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_acf_gutenberg_hooks();

        $this->set_locale();
        $this->plugin_load();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Loader. Orchestrates the hooks of the plugin.
     * - i18n. Defines internationalization functionality.
     * - ACF_Gutenberg_Admin. Defines all hooks for the admin area.
     * - ACF_Gutenberg_Public. Defines all hooks for the public area.
     * - Builder. compiler blocks and components using blade.
     * - Block. parent class for all blocks.
     * - Config. magic array for register ACF local field groups.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.1.0
     * @access   private
     */
    private function load_dependencies()
    {
        $this->loader = new Loader();
        $this->builder = new Builder();

        require_once ACFGB_PATH . '/includes/Blade_Abs.php';
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.1.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Admin\Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('enqueue_block_editor_assets', $plugin_admin, 'enqueue_block_editor_assets');
        $this->loader->add_action('enqueue_block_assets', $plugin_admin, 'enqueue_block_assets');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.1.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Resources\Acfgb_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('enqueue_block_assets', $plugin_public, 'enqueue_frontend_assets');
    }

    /**
     * Register all of the hooks related to the ACF and gutenberg blocks
     *
     * @since    1.1.0
     * @access   private
     */
    private function define_acf_gutenberg_hooks()
    {

        add_action('init', function (){
            $this->builder = new Builder();

           $cache_directory = wp_upload_dir()['basedir'] . '/cache';
            global $ACFB_Blade;
            $ACFB_Blade = new Blade($this->builder()->views(), $cache_directory);

        });

        $this->loader->add_filter('block_categories', $this->builder, 'block_categories', 10, 2);
		$this->loader->add_action('acf/init', $this->builder, 'register_blocks');

		// echo "<pre>";
		// print_r( $this->get_builder_fields() );
		// echo "</pre>";
		// die();
        // $this->loader->add_action('acf/init', $this->builder, 'register_field_group');
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the ACF_Gutenberg_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.1.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Add actions to plugin load.
     *
     * @since    1.1.0
     * @access   private
     */
    private function plugin_load()
    {
        $this->loader->add_action('plugin_loaded', $this, 'config_cache');
    }

    /**
     * Config cache.
     *
     * @since    1.1.0
     * @access   private
     */
    public function config_cache()
    {
        // Check and create cache folder
        $cache_directory = wp_upload_dir()['basedir'] . '/cache';
        if (!is_dir($cache_directory)) {
            mkdir($cache_directory, 0755, true);
        }
    }

    public static function getInstance()
    {
        return new ACF_Gutenberg();
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.1.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.1.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.1.0
     * @return    Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * The reference to the class that compiler blocks and component using blade.
     *
     * @since     1.1.0
     * @return    Builder    compile blocks and components.
     */
    public function builder()
    {
        return $this->builder;
	}

	/**
     * Get Builder fields
     *
     * @since     1.1.0
     * @return
     */
    public function get_builder_fields()
    {
        return $this->builder->get_blocks();
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.1.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
