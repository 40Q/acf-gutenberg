<?php

namespace ACF_Gutenberg;

use Roots\Clover\Plugin as Clover;

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
     * Paths to search blocks.
     *
     * @since    0.1.0
     * @access   public
     * @var      array  $actions
     */
    public $blocks_paths;

    /**
     * Run the plugin.
     */
    public function run()
    {
        /** Lifecycle hooks & actions */
        register_activation_hook($this->get('path'), [$this, 'activate']);
        register_deactivation_hook($this->get('path'), [$this, 'deactivate']);
        add_action($this->getTag('upgrade'), [$this, 'upgrade']);

        $this->set_prefix();
        $this->set_actions();
        $this->set_blocks_paths();
        $this->acf_load_blocks();
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
    public function enqueue_block_editor_assets()
    {
        \Assets::enqueue_block_editor_assets();
    }

    /**
     * Enqueue block editor only JavaScript and CSS.
     */
    public function enqueue_assets()
    {
        \Assets::enqueue_assets();
    }

    /**
     * Enqueue block editor only JavaScript and CSS.
     */
    public function enqueue_frontend_assets()
    {
        \Assets::enqueue_frontend_assets();
    }

    public function set_blocks_paths()
    {
        $this->blocks_paths = [
            ACFGB_PATH_RESOURCES . '/blocks/',
            get_template_directory() . '/acf-gutenberg/blocks/'
        ];
    }

    public function acf_load_blocks()
    {
        $class_name = 'ACF_Gutenberg\\Classes\\Block';
        new $class_name('acf-block');
        if (is_array($this->blocks_paths)) {
            foreach ($this->blocks_paths as $path) {
                if (is_dir($path)) {
                    $blocks = array_diff(scandir($path), ['..', '.']);

                    foreach ($blocks as $block_slug) {
                        $class_file = $path . $block_slug . '/' . $block_slug . '.class.php';
                        if (file_exists($class_file)) {
                            require_once $class_file;
                            $class_name = 'ACF_Gutenberg\\Blocks\\' . Lib\convert_to_class_name($block_slug);
                            $block = new $class_name($block_slug);
                        }
                    }
                }
            }
        }
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
