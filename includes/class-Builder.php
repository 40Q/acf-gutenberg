<?php

/**
 * Builder
 * Use Blade.
 *
 * @since      1.1.0
 *
 * @package    ACF_Gutenberg
 * @subpackage ACF_Gutenberg/includes
 */

namespace ACF_Gutenberg\Includes;

use ACF_Gutenberg\Classes\Blade;
use ACF_Gutenberg\Lib;
use function Roots\wp_die;

class Builder
{
    /**
     * Views for search blocks.
     *
     * @since    1.1.0
     * @access   protected
     * @var      array    $views    views.
     */
    protected $views;

    /**
     * Blade components.
     *
     * @since    1.1.0
     * @access   protected
     * @var      array    $components    components.
     */
    protected $components;

    /**
     * Paths to search blocks.
     *
     * @since    1.1.0
     * @access   protected
     * @var      array    $blocks_paths blocks paths.
     */
    protected $blocks_paths;

    /**
     * Array of blocks founded in all paths.
     *
     * @since    1.1.0
     * @access   protected
     * @var      array          $blocks  blocks list.
     */
    protected $blocks;

    /**
     * Array of blocks that will be disabled.
     *
     * @since    1.1.0
     * @access   protected
     * @var      array    $blocks_disable.
     */
    protected $blocks_disabled;

    /**
     * Utility class to use blade functions.
     *
     * @since    1.1.0
     * @access   protected
     * @var      ACF_Gutenberg\Classes\Blade    $blade    blade object.
     */
    protected $blade;

    /**
     * Blade compliler.
     *
     * @since    1.1.0
     * @access   protected
     * @var      object    $compiler    blade compiler.
     */
    protected $compiler;

    protected $count;

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
        $this->set_views();
        $this->set_components();
        $this->set_blocks_paths();
        $this->set_blocks_disabled();
        $this->load_blocks();
        $this->load_blade();
        $this->compile_components();
        $this->count = 0;
    }

    /**
     * Set views path
     *
     * Long Description.
     *
     * @since    1.1.0
     */
    public function set_views()
    {
        $default_views = [
            get_template_directory() . '/acf-gutenberg',
            ACFGB_PATH . '/resources/'
        ];
        $views = apply_filters('acfgb_views', $default_views);
        $this->views = $views;
    }

    /**
     * Set blade components
     *
     * Set path and name for components.
     *
     * @since    1.1.0
     */
    public function set_components()
    {
        $default_components = [
            'wrapper' => 'components.wrapper',
            'container' => 'components.container',
            //'button' => 'components.button',
        ];
        $components = apply_filters('acfgb_components', $default_components);
        $this->components = $components;
    }

    /**
     * Set blocks_paths
     *
     * Set path for search blocks.
     *
     * @since    1.1.0
     */
    public function set_blocks_paths()
    {
        $default_block_paths = [
            ACFGB_PATH . '/resources/blocks/',
            get_template_directory() . '/acf-gutenberg/blocks/'
        ];

        $blocks_paths = apply_filters('acfgb_block_paths', $default_block_paths);
        $this->blocks_paths = $blocks_paths;
    }

    /**
     * Set blocks disabled
     *
     * Set array of blocks that will be disabled.
     *
     * @since    1.1.0
     */
    public function set_blocks_disabled()
    {
        $this->blocks_disabled = [];
        $this->blocks_disabled = apply_filters('acfgb_blocks_disabled', $this->blocks_disabled);
    }

    /**
     * Search and load blocks
     *
     * @since    1.1.0
     */
    public function load_blocks()
    {
        $this->blocks = [];
        if (is_array($this->blocks_paths)) {
            foreach ($this->blocks_paths as $path) {
                if (is_dir($path)) {
                    $blocks = array_diff(scandir($path), ['..', '.']);
                    foreach ($blocks as $block_slug) {
                        $class_file = $path . $block_slug . '/' . $block_slug . '.class.php';
                        if (file_exists($class_file)) {
                            $class_name = 'ACF_Gutenberg\\Blocks\\' . Lib\convert_to_class_name($block_slug);
                            $this->blocks[] = (object) [
                                'slug' => $block_slug,
                                'class' => $class_name,
                                'file' => $class_file
                            ];
                        }
                    }
                }
            }
        }

        $this->blocks = (object) $this->blocks;
    }

    /**
     * Load blade by ACF_Gutenberg\Classes\Blade
     *
     *
     * @since    1.1.0
     */
    public function load_blade()
    {
        $cache_directory = wp_upload_dir()['basedir'] . '/cache';

        $this->blade = new Blade($this->views, $cache_directory);
    }

    /**
     * Define blade components.
     *
     * Uses blade to create components.
     *
     * @since    1.1.0
     * @access   private
     */
    private function compile_components()
    {
        $this->compiler = $this->blade->getCompiler();

        foreach ($this->components as $component => $path) {
            $this->compiler->component($path, $component);
        }
    }

    /**
     * Register custom block categories for Gutenberg.
     *
     *
     * @since    1.1.0
     * @access   public
     *
     * @param $categories array
     * @param  $post  object
     *
     * @return array
     */
    public function block_categories($categories, $post)
    {
        /**
         * Use this conditional for filter categories bu post type
         */
        if ($post->post_type !== 'post') {
            //return $categories;
        }

        $default_blocks_category = [
            'slug' => 'acf-gutenberg-blocks',
            'title' => __('ACF Gutenberg Blocks', 'acf-gutenberg'),
            'icon' => 'wordpress',
        ];
        $blocks_category = apply_filters('acfgb_blocks_category', $default_blocks_category);
        return array_merge($categories, [$blocks_category]);
    }

    /**
     * Register ACF Blocks.
     *
     * .
     *
     * @since    1.1.0
     * @access   public
     */
    public function register_blocks()
    {
        foreach ($this->blocks as $block) {
            if ($block->slug !== 'oop-block' && !in_array($block->slug, $this->blocks_disabled)) {
                require_once $block->file;
                $instance = new $block->class($block->slug);
                if (function_exists('acf_register_block')) {
                    acf_register_block($instance->get_settings());
                }
            }
        }
    }

    public function register_field_group()
    {
        if (function_exists('acf_add_local_field_group')) {
            foreach ($this->blocks as $block) {
                require_once $block->file;
                $instance = new $block->class($block->slug);
                foreach ($instance->fields as $field) {
                    $block_content = $field->build();
                    \ACF_Gutenberg\Classes\Config::createDynamic(str_replace('group_', '', $block_content['key']), array_column($block_content['fields'], 'name'));
                    acf_add_local_field_group($block_content);
                }
            }
        }
    }

    public function render_block($block)
    {
//        print_r($block);
//        wp_die();
        $slug = str_replace('acf/', '', $block['name']);
        $class_name = 'ACF_Gutenberg\\Blocks\\' . Lib\convert_to_class_name($slug);
        $block_instance = new $class_name($slug);

        // Set Position
        $block_instance->set_block_id();

        $plugin_blade_file = glob(ACFGB_PATH . "/resources/blocks/{$block_instance->slug}/{,*/}{*}blade.php", GLOB_BRACE);
        $theme_blade_file = glob(get_template_directory() . "/acf-gutenberg/blocks/{$block_instance->slug}/{,*/}{*}blade.php", GLOB_BRACE);

        if (isset($plugin_blade_file[0]) && file_exists($plugin_blade_file[0]) || isset($theme_blade_file[0]) && file_exists($theme_blade_file[0])) {
            return $this->blade()->view()->make("blocks.{$block_instance->slug}.{$block_instance->slug}", ['block' => $block_instance]);
        } else {
            wp_die("Blade view not exist for $class_name Block");
        }
    }

    /**
     * The reference to the views list.
     *
     * @since     1.1.0
     * @return    array    Orchestrates the hooks of the plugin.
     */
    public function get_views()
    {
        return $this->views;
    }

    /**
     * The reference to the blocks list.
     *
     * @since     1.1.0
     * @return    array
     */
    public function get_blocks()
    {
        return $this->blocks;
    }

    /**
     * The reference to the components list.
     *
     * @since     1.1.0
     * @return    array    list of components.
     */
    public function get_components()
    {
        return $this->components;
    }

    /**
     * The reference to the class that compiler blocks and component using blade.
     *
     * @since     1.1.0
     * @return    ACF_Gutenberg\Classes\Blade    compile blocks and components.
     */
    public function blade()
    {
        return $this->blade;
    }

    /**
     * Increase Count
     *
     * @return void
     */
    public function incrementValue()
    {
        $this->count++;
    }
}
