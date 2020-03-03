<?php

namespace ACF_Gutenberg\Includes;

use ACF_Gutenberg\Includes\FieldsController;
use ACF_Gutenberg\Includes\Builder;
use ACF_Gutenberg\Includes\Lib;

/**
 * Class Block
 * @since      1.0.0
 *
 * @package    ACF_Gutenberg
 * @subpackage ACF_Gutenberg/includes
 *
 * This class is meant to be extended in order to separate the functionality of
 * a builder block from its presentation.
 *
 * @property-read $position
 * @property-read $title
 * @property-read $text
 */

class Block
{
    /**
     * block ID.
     *
     * @var string
     */
    public $id;

    /**
     * Internal prefix for a block's data.
     *
     * @var string
     */
    public $prefix = '';

    /**
     * Slug for this block.
     *
     * @var string
     */
    public $slug = '';

    /**
     * Render function callback for this block.
     *
     * @var string
     */
    public $render_callback = '';

    /**
     * Current position inside a loop of blocks.
     *
     * @var int
     */
    public static $position = 0;

    /**
     * Main HTML class for the current block.
     *
     * @var string
     */
    public $class_prefix = 'b__';

    /**
     * Array of fields for the main HTML element.
     *
     * @var array
     */
    public $fields = [];

    /**
     * Array of fields created in extended blocks.
     *
     * @var array
     */
    public $custom_fields = [];

    /**
     * FieldsController Class.
     *
     * @var array
     */
    public $FieldsController ;

    /**
     * Array of fields config in child block class.
     *
     * @var array
     */
    public $fields_config = [];

    /**
     * Array of settings for the block.
     *
     * @var array
     */
    public $settings = [];

    /**
     * Array of classes for the main HTML element.
     *
     * @var array
     */
    public $classes = [];

    /**
     * Array of inline styles for the main HTML element.
     *
     * @var array
     */
    public $styles = [];

    /**
     * Title of the current block.
     *
     * @var bool|string
     */
    public $title = '';

    /**
     * Text of the current block.
     *
     * @var bool|string
     */
    public $mode = 'preview';

    /**
     * Globals fields settings.
     *
     * @since    0.1.0
     * @access   public
     * @var      array  $global_fields
     */
    public $global_fields = [
        // CONTENT TAB
        'button' => false,
        'button_target' => true,
        'button_class' => true,
        'button_icon' => false,

        // DESIGN TAB
        'section' => true,
        'bg_color' => true,
        'text_color' => true,
        'full_height' => false,

        // CLASS TAB
        'custom_id' => true,
        'custom_class' => true,
    ];

    /**
     * Default fields options to reuse.
     *
     * @since    1.1.0
     * @access   public
     * @var      array  $field_options
     */
    public $field_options = [
        'align' => [
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ],
        'text_align' => [
            'text-left' => 'Left',
            'text-center' => 'Center',
            'text-right' => 'Right',
        ],
        'cols' => [
            'col-md-1' => 1,
            'col-md-2' => 2,
            'col-md-3' => 3,
            'col-md-4' => 4,
            'col-md-5' => 5,
            'col-md-6' => 6,
            'col-md-7' => 7,
            'col-md-8' => 8,
            'col-md-9' => 9,
            'col-md-10' => 10,
            'col-md-11' => 11,
            'col-md-12' => 12,
        ],
    ];

    /**
     * Default theme colors.
     *
     * @since    0.1.0
     * @access   public
     * @var      array  $theme_colors
     */
    public $theme_colors = [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'light' => 'Light',
        'dark' => 'Dark',
    ];

    /**
     * Array of templates available for this block.
     *
     * @var array
     */
    public $templates;

    /**
     * Block path.
     *
     * @var array
     */
    public $path;

    /**
     * Title of the block, defined in child block class.
     *
     * @var string
     */
    public $block_title = 'Custom block';
    /**
     * Icon of the block, defined in child block class.
     *
     * @var string
     */
    public $icon = 'edit';
    /**
     * Description of the block, defined in child block class.
     *
     * @var string
     */
    public $description = 'ACF Block';
    /**
     * Keywords of the block, defined in child block class.
     *
     * @var string
     */
    public $keywords = ['acf-block'];
    /**
     * Keywords of the block, defined in child block class.
     *
     * @var string
     */
    public $category = false;
    /**
     *
     *
     * @var array
     */
    public $props = false;

    /**
     * Block constructor.
     *
     * @param string $block_slug
     */
    public function __construct($block_slug)
    {
        $this->load_dependencies();

        $this->set_slug($block_slug);
        $this->set_path();
        $this->set_render_callback();
		$this->set_templates();
        $this->set_field_options();
        $this->set_settings();
        $this->set_theme_colors();
        $this->set_fields();
        $this->build_fields();
    }

    public function init()
    {
        // Use this method in extended classes
    }

    /**
     * Load the required dependencies for this class.
     *
     * Include the following files that make up the plugin:
     *
     * - FieldsController. manage the fields of blocks.
     *
     * @since    1.1.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for manage the fields of blocks.
         */
        $this->FieldsController = new FieldsController;
    }

    /**
     * Set block slug.
     *
     */
    public function set_slug($block_slug)
    {
        $this->slug = $block_slug;
    }

    /**
     * Set block slug.
     *
     */
    public function set_path()
    {
    	$block_paths = Lib\config('blocks.paths');
    	foreach ( $block_paths as $path ) {
    		$block_path = $path . '/' . $this->slug;
    		if ( is_dir( $block_path ) ) {
        		$this->path = $block_path;
			}
		}
    }

    /**
     * Set block render callback.
     *
     */
    public function set_render_callback()
    {
        $this->render_callback = ['ACF_Gutenberg\Includes\Block', 'render'];
    }

    /**
     * Set Block ID
     * Return an ID if set or the block position
     *
     * @return void
     */
    public function set_block_id()
    {
        $this->id = (isset($this->block_id) && !empty($this->block_id)) ? $this->block_id : 'block-' . self::$position++;
		if (isset( $this->props['custom_id'] ) && !empty( $this->props['custom_id'] ) ) {
			$this->id = $this->props['custom_id'];
		}
	}

    /**
     * Set field options to reuse. Overwritten option by filter.
     *
     */
    public function set_field_options()
    {
        $this->field_options = apply_filters('acfgb_field_options', $this->field_options);
    }

    /**
     * Set basic settings by default. This method must be overwritten in extended classes.
     *
     */
    public function set_settings()
    {
        if ($this->category) {
            $category = $this->category;
        } else {
            $default_blocks_category = [
                'slug' => 'acf-gutenberg-blocks',
            ];
            $blocks_category = apply_filters('acfgb_blocks_category', $default_blocks_category);
            $category = $blocks_category['slug'];
        }

        $this->settings = [
            'name' => $this->slug,
            'render_callback' => $this->render_callback,
            'title' => $this->block_title,
            'icon' => $this->icon,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'mode' => $this->mode,
            'category' => $category,
        ];
    }

    /**
     * Set block colors by theme color filter.
     *
     */
    public function set_theme_colors()
    {
        $colors = apply_filters('acfgb_theme_colors', $this->theme_colors);
        $this->theme_colors = $colors;
    }

    /**
     * Set basic fields by default. This method must be overwritten in extended classes.
     *
     */
    public function set_fields()
    {
        $this->custom_fields = $this->FieldsController->set_fields();
    }

    public function build_fields()
    {
        $this->global_fields = apply_filters('acfgb_global_fields', $this->global_fields);
        $this->global_fields = $this->FieldsController->set_global_fields($this->global_fields, $this->fields_config);
        $this->fields = $this->FieldsController->build_fields(
            $this->set_fields(),
            $this->global_fields,
            $this->slug,
            $this->theme_colors,
            $this->field_options,
            $this->templates
        );
    }

    /**
     * Set block props by fields.
     *
     */
    public function set_props()
    {

        /**
         * Register properties
         */

        $props = $this->get_props();
        if (is_array($props)) {
            foreach ($props as $prop) {
                if (function_exists('get_field')) {
                    $value = get_field($prop);
                    if ($prop == 'image' && (!isset($value) || empty($value))) {
                        $value = 'https://via.placeholder.com/1400X800.png';
					}
					$this->props[$prop] = $value;
                }
            }

            // Set custom props and more
            $this->init();
            $this->set_styles();
            $this->set_classes();

        }

    }

    /**
     * Obtain the value of a public or private property.
     *
     * @param  string $property
     *
     * @return null
     */
    public function __get($property)
    {
        if (property_exists(get_called_class(), $property)) {
            if (null === $this->{$property} && method_exists($this, "set_{$property}")) {
                $this->{"set_{$property}"}();
            }

            return $this->{$property};
        }

        return null;
    }

    /**
     * Set classes for the main HTML element.
     */
    public function set_classes()
    {
		$class_base = Lib\config('blocks.class_base');

        // Add b to classes array
        array_push($this->classes, $class_base);

        // Add slug
        array_push($this->classes, $this->class_prefix . str_replace('_', '-', $this->slug));

        // Add preset classes
		if ($this->props['preset_classes']) {
			array_push($this->classes, join( ' ', $this->props['preset_classes'] ));
		}

		// Add custom block classes
		if ($this->props['block_classes']) {
			array_push($this->classes, $this->props['block_classes']);
		}


        // Add class if section has bg image
        if ( isset( $this->props['background_image'] ) && $this->props['background_image'] ) {
            array_push($this->classes, 'has-bg');
        }

        // Add custom classes
        $design_properties = [
            'bg-'   => 'bg_color',
            'text-' => 'text_color',
        ];

        foreach ($design_properties as $key => $value) {
            if ( isset( $this->props['section'][$value] ) && $this->props['section'][$value] ) {
                array_push($this->classes, $key . $this->props['section'][$value] );
            }
        }

    }

    /**
     * Add classes to the main HTML element.
     *
     * @param array | string $classes
     */
    public function add_classes( $classes = '' )
    {
    	if ( empty($classes) ) return;

    	if ( is_array( $classes ) ) {
			foreach ($classes as $class) {
				$this->classes[] = $class;
			}
		}else if ( is_string( $classes ) ){
			$this->classes[] = $classes;
		}

    }

	/**
	 * Add classes to the main HTML element.
	 *
	 * @param array $props block props
	 *
	 */
	public function add_props( $props )
	{
		if (is_array($props)) {
			foreach ($props as $key => $value) {
				$this->props[$key] = $value;
			}
		}
	}

    /**
     * Obtain a list of parsed classes for the main HTML element.
     *
     * @param array $classes
     *
     * @return string
     */
    public function get_parsed_classes(array $classes = [])
    {
        $this->add_classes($classes);

        return join(' ', $this->classes);
    }

    /**
     * Wrapper for `Block::get_parsed_classes()`
     *
     * @see Block::get_parsed_classes()
     *
     * @param array $classes
     *
     * @return string
     */
    public function get_classes(array $classes = [])
    {
        return $this->get_parsed_classes($classes);
    }

    /**
     * Set inline styles for the main HTML element.
     *
     * Inline styles can be quite different from one block to another, so this
     * method is just a placeholder for other classes.
     */
    public function set_styles()
    {
        if ( isset( $this->props['background_image'] ) ) {
            $this->styles['background-image'] = 'url(\'' . esc_url( $this->props['background_image'] ) . '\')';
        }
    }

    /**
     * Parse inline styles to be printed as HTML.
     *
     * @return string
     */
    public function get_parsed_styles()
    {
        $styles = [];
        foreach ($this->styles as $prop => $value) {
            $styles[] = $prop . ': ' . $value . ';';
        }

        return join(' ', $styles);
    }

    /**
     * Get block slug by block folder.
     *
     * @return string
     */
    public function get_default_slug()
    {
    }

    /**
     * Wrapper for `Block::get_parsed_styles()`
     *
     * @see Block::get_parsed_styles()
     *
     * @return string
     */
    public function get_styles()
    {
        return $this->get_parsed_styles();
    }

    /**
     * Get fields list
     *
     *
     * @return array
     */
    public function get_fields()
    {
        $block_fields = [];
        $acf_fields = [];
        foreach ($this->fields as $field) {
            $acf_fields = $field->build();
        }
        foreach ($acf_fields['fields'] as $field) {
            if ($field['type'] != 'tab') {
                if ($field['type'] == 'group') {
                    $group = $field['name'];
                    $block_fields[$group] = [];
                    foreach ($field['sub_fields'] as $sub_field) {
                        $field_slug = $sub_field['name'];
                        $block_fields[$group][$field_slug] = $field_slug;
                    }
                }
            }
        }

        return $block_fields;
    }

    /**
     * Get props list
     *
     *
     * @return array
     */
    public function get_props()
    {
        $block_props = [];
        $acf_fields = [];
        foreach ($this->fields as $field) {
            $acf_fields = $field->build();
        }

        foreach ($acf_fields['fields'] as $field) {
            if ($field['type'] != 'tab') {
                $block_props[] = $field['name'];
            }
        }

        return $block_props;
    }

    public function get_settings()
    {
        $this->settings['name'] = $this->slug;
        $this->settings['slug'] = $this->slug;
        $this->settings['render_callback'] = $this->render_callback;

        $this->settings['block_obj'] = clone $this;
        return $this->settings;
    }

    public function get_ipsum()
    {
        return "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
    }


    static function render($block)
    {

        $plugin_blade_file = glob(ACFGB_PATH . "/resources/blocks/{$block['slug']}/{,*/}{*}blade.php", GLOB_BRACE);

        $theme_blade_file = glob(get_template_directory() . "/acf-gutenberg/blocks/{$block['slug']}/{,*/}{*}blade.php", GLOB_BRACE);

		$block['block_obj']->set_block_id();
        $block['block_obj']->set_props();
        $props = array_merge(
            $block['block_obj']->props,
            ['block' => $block['block_obj']]
        );

        if (isset($plugin_blade_file[0]) && file_exists($plugin_blade_file[0]) || isset($theme_blade_file[0]) && file_exists($theme_blade_file[0]) ) {
			Builder::blade()->view()->composer( 'components.block.block', function ( $view ) use ( $block ) {

				// Reset Block classes to avoid repeated classes when a new instance of the same block is created.
				// Needs to be fixed
				$block['block_obj']->classes = [];
				$block['block_obj']->init();
				$block['block_obj']->set_classes();

				// Send data from Builder Class
				$view->with([
					'block' => $block['block_obj'],
					'id' => $block['block_obj']->id,
				]);
			});
			do_action('acfg_compile_components');
            echo  Builder::blade()->view()->make("blocks.{$block['slug']}.{$block['slug']}", $props);

        } else {
            \wp_die("Blade view not exist for {$block['class']} Block");
        }
    }

	public function set_templates()
	{
		$templates_dir = Lib\config('builder.templates_dir');
		$templates_path = $this->path . '/' . $templates_dir;
		$templates = [];
		if ( is_dir( $templates_path ) ) {
			$templates_files = array_diff( scandir( $templates_path ), ['..', '.'] );
			foreach ( $templates_files as $template ) {
				$template_name = explode('.', $template )[0];
				$template_path = "blocks.{$this->slug}.{$templates_dir}.{$template_name}";
				$templates[$template_path] = ucfirst( $template_name );
			}
		}
		$this->templates = $templates;
	}

	public function get_modules()
	{
		return $this->FieldsController->register_modules();
	}

	public function get_templates()
	{
		return "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
	}

}
