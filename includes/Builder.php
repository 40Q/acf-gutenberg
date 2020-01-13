<?php

namespace ACF_Gutenberg\Includes;

use ACF_Gutenberg\Includes\Lib;
use ACF_Gutenberg\Components;

/**
 * Builder
 * Use Blade.
 *
 * @since      1.1.0
 *
 * @package    ACF_Gutenberg
 * @subpackage ACF_Gutenberg/includes
 */

class Builder
{
    /**
     * Views for search blocks.
     *
     * @since    1.1.0
     * @access   protected
     * @var      array    $views    views.
     */
    public $views;

    /**
     * Blade components.
     *
     * @since    1.1.0
     * @access   protected
     * @var      array    $components    components.
     */
    public $components;

    /**
     * Blade modules.
     *
     * @since    1.1.0
     * @access   protected
     * @var      array    $modules    modules.
     */
    public $modules;

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
     * @var      array    $disabled_blocks.
     */
    protected $disabled_blocks;

    /**
     * Utility class to use blade functions.
     *
     * @since    1.1.0
     * @access   protected
     * @var      ACF_Gutenberg\Classes\Blade    $blade    blade object.
     */
    static $blade;

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
        $this->set_modules();
        $this->set_blocks_paths();
        $this->set_disabled_blocks();
        $this->load_blocks();
		$this->set_allowed_blocks();
        $this->load_blade();
        $this->compile_components();
        $this->compile_modules();

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
//            get_template_directory() . '/acf-gutenberg/blocks/block-templates',
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
        $default_components = $this->load_components();
        $default_components['wrapper'] = 'components.wrapper';
        $components = apply_filters('acfgb_components', $default_components);
        $this->components = $components;
    }

    /**
     * Set blade modules
     *
     * Set path and name for modules.
     *
     * @since    1.1.0
     */
    public function set_modules()
    {
        $default_modules = $this->load_modules();
        $modules = apply_filters('acfgb_modules', $default_modules);
        $this->modules = $modules;
    }

	/**
	 * Search and load for any component theme acf-gutenberg's folder.
	 *
	 * @since    1.1.0
	 */
	public function load_components()
	{
		$components = [];
		$components_path = get_template_directory() . '/acf-gutenberg/components';
		if ( is_dir( $components_path ) ) {
			$components_files = array_diff( scandir( $components_path ), ['..', '.'] );
			foreach ($components_files as $component) {
				$component_path = '';
				if ( is_dir( $components_path . '/' . $component ) ) {
					$component_path = $component . '.';
				}
				$component_slug = str_replace('.blade.php' , '', $component );
				$components[$component_slug] = 'components.' . $component_path . $component_slug;

			}
		}

		return $components;

	}

	/**
	 * Search and load for any module theme acf-gutenberg's folder.
	 *
	 * @since    1.1.0
	 */
	public function load_modules()
	{
		$modules = [];
		$modules_path = get_template_directory() . '/acf-gutenberg/modules';
		if ( is_dir( $modules_path ) ) {
			$modules_files = array_diff( scandir( $modules_path ), ['..', '.'] );
			foreach ($modules_files as $module) {
				$module_path = '';
				if ( is_dir( $modules_path . '/' . $module ) ) {
					$module_path = $module . '.';
				}
				$module_slug = str_replace('.blade.php' , '', $module );
				$modules[$module_slug] = 'modules.' . $module_path . $module_slug;

			}
		}

		return $modules;

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
    public function set_disabled_blocks()
    {
        $this->disabled_blocks = [];
        $this->disabled_blocks = apply_filters('acfgb_disabled_blocks', $this->disabled_blocks);
    }

    /**
     * Search and load for any blocks on plugin or theme acf-gutenberg's folder.
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
                            $this->blocks[$block_slug] = [
                                'slug' => $block_slug,
                                'class' => $class_name,
                                'file' => $class_file
                            ];
                        }
                    }
                }
            }
        }
    }

	/**
	 * Set allowed default blocks types
	 *
	 * @since    1.1.0
	 */
	public function set_allowed_blocks()
	{
		add_filter( 'allowed_block_types', function ($allowed_blocks) {
			$acf_blocks = [];
			if ($this->blocks && is_array($this->blocks)){
				foreach ($this->blocks as $block){
					$acf_blocks[] = 'acf/'.$block['slug'];
				}
			}
			$allowed_blocks = [];
			$allowed_blocks = apply_filters('acfgb_allowed_default_block', $allowed_blocks);

			$allowed_blocks = array_merge($allowed_blocks, $acf_blocks);
			return $allowed_blocks;
		});

	}

    /**
     * Load blade by ACF_Gutenberg\Classes\Blade
     *
     *
     * @since    1.1.0
     */
    public function load_blade()
    {
        self::$blade = new Blade($this->get_views(), $this->get_cache_directory());
    }

    /**
     * Define blade components.
     *
     * Use blade to create components.
     *
     * @since    1.1.0
     * @access   private
     */
    public function compile_components()
    {
        foreach ($this->get_components() as $component => $component_path) {
			if ( $component_path == 'components.block.block')
				continue;

        	$composer_path = get_template_directory() . '/acf-gutenberg/' . str_replace( '.', '/', $component_path ) . '.php';
        	if ( file_exists( $composer_path ) ) {

        		// Include Composer class for the component
        		require_once $composer_path;
        		$composer_dynamic_class = $this->get_composer_class( $component );
        		$composer = new $composer_dynamic_class;

        		// Get and send data to the component
				self::blade()->view()->composer($composer->getViews(), function ( $view ) use ( $composer, $component_path, $component ) {

					// Send data from Builder Class
					$view->with([ 'component' => $component]);

					// Send data from composer and avoid overwrite the current data
					$view->with( array_merge( $composer->with( $view->gatherData() ), $view->gatherData() ) );

					// Send data from composer and overwrite the current data
					$view->with( $composer->override( $view->gatherData() ) );

				});
			}
			self::blade()->getCompiler()->component($component_path, $component);
        }
    }

    /**
     * Define blade modules.
     *
     * Use blade to create modules.
     *
     * @since    1.1.0
     * @access   private
     */
    public function compile_modules()
    {
        foreach ($this->get_modules() as $module => $module_path) {
			if ( $module_path == 'modules.block.block')
				continue;

        	$composer_path = get_template_directory() . '/acf-gutenberg/' . str_replace( '.', '/', $module_path ) . '.php';
        	if ( file_exists( $composer_path ) ) {

        		// Include Composer class for the module
        		require_once $composer_path;
        		$composer_dynamic_class = $this->get_module_class( $module );
        		$composer = new $composer_dynamic_class;

        		// Get and send data to the module
				self::blade()->view()->composer($composer->getViews(), function ( $view ) use ( $composer, $module_path, $module ) {

					// Send data from Builder Class
					$view->with([ 'module' => $module]);

					// Send data from composer and avoid overwrite the current data
					$view->with( array_merge( $composer->with( $view->gatherData() ), $view->gatherData() ) );

					// Send data from composer and overwrite the current data
					$view->with( $composer->override( $view->gatherData() ) );

				});
			}
//			self::blade()->getCompiler()->component($module_path, $module);
        }
    }

	/**
	 * Compile Block Component.
	 *
	 * Compile Block Component from a custom action.
	 *
	 * @since    1.1.0
	 * @access   public
	 */
	public function compile_block_component()
	{
		$component_path = 'components.block.block';

		if ( $this->include_composer_class( $component_path ) ) {

			$composer_dynamic_class = $this->get_composer_class( 'block' );
			$composer = new $composer_dynamic_class;

			self::blade()->view()->composer($composer->getViews(), function ( $view ) use ( $composer, $component_path ) {
				if ( isset( $view->gatherData()['block'] ) && is_object( $view->gatherData()['block'] ) ) {

					// Send block object to block component
					$view->with( ['block' => $view->gatherData()['block']] );

					// Send data from composer and avoid overwrite the current data
					$view->with( array_merge( $composer->with( $view->gatherData() ), $view->gatherData() ) );

					// Send data from composer and overwrite the current data
					$view->with( $composer->override( $view->gatherData() ) );
				}
			});
		}
		self::blade()->getCompiler()->component($component_path, 'block');
	}

	/**
	 * Include View Composer Class.
	 *
	 * Check if composer file exists and include it.
	 *
	 * @param    $component_path string component path.
	 * @return   bool
	 * @since    1.1.0
	 * @access   public
	 */
	public function include_composer_class( $component_path )
	{
		$file_included = false;
		$composer_path = get_template_directory() . '/acf-gutenberg/' . str_replace( '.', '/', $component_path ) . '.php';

		if ( file_exists( $composer_path ) ) {
//			$component = explode('.', $component_path );
//			$key = array_key_last($component);
//			$component = $component[$key];

			require_once $composer_path;
			$file_included = true;
		}
		return $file_included;
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
     * @since    1.1.0
     * @access   public
     */
    public function register_blocks()
    {
        foreach ($this->blocks as $block) {
			// If block is not disabled
            if ( !in_array($block['slug'], $this->disabled_blocks) ) {

				// Call the block .class file
				require_once $block['file'];

				// Instantiate the class from each block .class file
				// https://stackoverflow.com/questions/8734522/dynamically-call-class-with-variable-number-of-parameters-in-the-constructor
				$dynamic_class = $block['class'];

				// Pass slug argument for constructor
				$instance = new $dynamic_class($block['slug']);

				// Register ACF Block In Gutenberg (on the Admin)
				$this->register_acf_block( $instance );

				if( property_exists($instance, 'fields') ) {
					// Does if makes sense to loop if we are supposed to have
					// all ACF Stout Logic fields set in 'fields' object property
					foreach ($instance->fields as $field) {

						// Create ACF Friendly JSON with Stout Logic Helper
						$block_content = $field->build();

						// Convert Stout Object to ACF Group (Build the field group)
						$this->convert_to_acf_fields( $block_content );

						// Get block field names
						$block_fields_names = array_column($block_content['fields'], 'name');

						// Inject Block into fields
						$this->inject_block_fields($block_fields_names, $block['slug']);
					}
				}
            }
        }
	}

	/**
	 * Register ACF Block from an instance
	 *
     * @param Block $instance
     *
	 * @return void
	 */
	public function register_acf_block(Block $instance)
	{
		// Register ACF Gutenberg Block with the parameters defined
		// on the block .class file
		if (function_exists('acf_register_block')) {
			acf_register_block($instance->get_settings());
		}
	}

	/**
	 * Register an array subset for fields reference
	 *
	 * @param array $instance
     *
	 * @return void
	 */
    public function convert_to_acf_fields(Array $block_content)
    {
        if (function_exists('acf_add_local_field_group')) {
			acf_add_local_field_group($block_content);
        }
    }

	/**
	 * Inject Fields to every Blocks object
	 *
	 * @param Array $block_fields_names
	 * @param [type] $slug
	 * @return void
	 */
	public function inject_block_fields(Array $block_fields_names, $slug)
	{
		$this->blocks[$slug]['field_names'] = $block_fields_names;

		return $this->blocks;
	}

    static function render_block($block)
    {
        $plugin_blade_file = glob(ACFGB_PATH . "/resources/blocks/{$block['slug']}/{,*/}{*}blade.php", GLOB_BRACE);

        $theme_blade_file = glob(get_template_directory() . "/acf-gutenberg/blocks/{$block['slug']}/{,*/}{*}blade.php", GLOB_BRACE);

        $block['block_obj']->set_props();
        $props = array_merge(
            $block['block_obj']->props,
            ['block' => $block['block_obj']]
        );

        if (isset($plugin_blade_file[0]) && file_exists($plugin_blade_file[0]) || isset($theme_blade_file[0]) && file_exists($theme_blade_file[0]) ) {
            echo self::getInstance()->blade()->view()->make("blocks.{$block['slug']}.{$block['slug']}", $props);
        } else {
            \wp_die("Blade view not exist for {$block['class']} Block");
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
     * The reference to the modules list.
     *
     * @since     1.1.0
     * @return    array    list of components.
     */
    public function get_modules()
    {
        return $this->modules;
    }

	/**
	 * Process and return composer class by component slug.
	 *
	 * @param     string  $component component directive.
	 * @return    string  class name.
	 * @since     1.1.0
	 */
    public function get_composer_class( $component )
    {
		$composer_class = 'ACF_Gutenberg\\Components\\' . ucfirst($component);
        return $composer_class;
    }

	/**
	 * Process and return composer class by module slug.
	 *
	 * @param     string  $module module directive.
	 * @return    string  class name.
	 * @since     1.1.0
	 */
	public function get_module_class( $module )
	{
		$composer_class = 'ACF_Gutenberg\\Modules\\' . ucfirst($module);
		return $composer_class;
	}

    /**
     * Return cache directory.
     *
     * @since     1.1.0
     * @return    string
     */
    public function get_cache_directory()
    {
        return wp_upload_dir()['basedir'] . '/cache';
    }

    /**
     * The reference to the class that compiler blocks and component using blade.
     *
     * @since     1.1.0
     * @return    ACF_Gutenberg\Classes\Blade    compile blocks and components.
     */
    static function blade()
    {
        return self::$blade;
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
