<?php

namespace ACF_Gutenberg;

use Roots\Clover\Plugin as Clover;
use ACF_Gutenberg\Lib;

class Plugin extends Clover
{
    /**
     * The prefix of this plugin.
     *
     * @since    0.1.0
     * @access   public
     * @var      array  $actions
     */
    public $prefix;

    /**
     * The actions of this plugin.
     *
     * @since    0.1.0
     * @access   public
     * @var      array  $actions
     */
    public $actions;

    /**
     * Run the plugin.
     */
    public function run()
    {
        /** Lifecycle hooks & actions */
        register_activation_hook($this->get('path'), [$this, 'activate']);
        register_deactivation_hook($this->get('path'), [$this, 'deactivate']);
        add_action($this->getTag('upgrade'), [$this, 'upgrade']);

        $this->load_dependencies();
        $this->set_prefix();
        $this->set_actions();
        $this->do_actions();
    }

    /**
     * Register plugin globals vars.
     */
    public function set_prefix()
    {
        $this->prefix = 'acfgb_';
    }

    /**
     * Run when the plugin is activated.
     */
    public function activate()
    {
    }

    /**
     * Run when the plugin is deactivated.
     */
    public function deactivate()
    {
    }

    /**
     * Run when the plugin is upgraded.
     */
    public function upgrade()
    {
    }

    /**
     * Load plugin dependencies.
     */
    public function load_dependencies()
    {

    }

    /**
     * Set actions plugin.
     */
    public function set_actions()
    {
        $this->actions = [
            'plugin_load',
            'register_globals',
            ['enqueue_block_editor_assets', 'enqueue_block_editor_assets'],
            ['enqueue_block_assets', 'enqueue_assets'],
            ['enqueue_block_assets', 'enqueue_frontend_assets'],
            ['acf/init', 'acf_init'],
            ['init', 'acf_builder_init'],
        ];
    }

    /**
     * Do actions plugin.
     */
    public function do_actions()
    {
        Lib\do_actions($this->actions, $this, $this->prefix);
    }



    /**
     * Register plugin globals vars.
     */
    public function plugin_load()
    {
        // Check and create cache folder
        $cache_directory = wp_upload_dir()['basedir'] . '/cache';
        if (!is_dir($cache_directory)) {
            mkdir($cache_directory, 0755, true);
        }

    }


    /**
     * Register plugin globals vars.
     */
    public function register_globals()
    {
        $cache_directory = wp_upload_dir()['basedir'] . '/cache';
        $plugin_views = ACFGB_PATH_RESOURCES . '/blocks';
        $theme_views = get_template_directory() . '/acf-gutenberg/blocks';

        $GLOBALS['plugin_blade_engine'] = new \Philo\Blade\Blade($plugin_views, $cache_directory);
        $GLOBALS['theme_blade_engine'] = new \Philo\Blade\Blade($theme_views, $cache_directory);
    }


    /**
     * Enqueue block editor only JavaScript and CSS.
     */
    function enqueue_block_editor_assets()
    {
        \Assets::enqueue_block_editor_assets();
    }

    /**
     * Enqueue block editor only JavaScript and CSS.
     */
    function enqueue_assets()
    {
        \Assets::enqueue_assets();
    }

    /**
     * Enqueue block editor only JavaScript and CSS.
     */
    function enqueue_frontend_assets()
    {
        \Assets::enqueue_frontend_assets();
    }

    function acf_init()
    {
        \ACF_Blocks::acf_init();
    }

    function acf_builder_init()
    {
        \ACF_Blocks::acf_builder_init();
    }



    /**
     * Get plugin prefix.
     */
    public function get_prefix()
    {
        return $this->prefix;
    }


    /**
     * Get plugin actions.
     */
    public function get_actions()
    {
        return $this->actions;
    }

}