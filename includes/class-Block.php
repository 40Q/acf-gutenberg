<?php

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


namespace ACF_Gutenberg\Classes;

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
    public $class = 'b';

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
            'text_align' => true,
        'container' => true,

        // CLASS TAB
        'custom_id' => true,
        'custom_class' => true,
        'custom_button_class' => true,

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
     * Array of field in Content Tab.
     * Array is defined in $FieldsController
     *
     * @var array
     */
    public $content;
    /**
     * Array of field in Design Tab.
     * Array is defined in $FieldsController
     *
     * @var array
     */
    public $design;
    /**
     * Array of field in Class Tab.
     * Array is defined in $FieldsController
     *
     * @var array
     */
    public $custom_classes;

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
     * Block constructor.
     *
     * @param string $block_slug
     */
    public function __construct($block_slug)
    {

        $this->load_dependencies();

        $this->set_slug($block_slug);
        $this->set_render_callback();
        $this->set_field_options();
        $this->set_settings();
        $this->set_theme_colors();
        $this->set_fields();
        $this->build_fields();
        $this->set_props();
        $this->set_class();
        $this->set_classes();
        $this->set_styles();

        $this->init();
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
    private function load_dependencies() {
        /**
         * The class responsible for manage the fields of blocks.
         */
        require_once ACFGB_PATH . '/includes/class-FieldsController.php';
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
     * Set block render callback.
     *
     */
    public function set_render_callback()
    {
        $this->render_callback = 'ACF_Gutenberg\Lib\my_acf_block_render_callback';
        //$this->render_callback = ['Builder', 'render_block'];
    }

    /**
     * Set block classes.
     *
     */
    public function set_class()
    {
        $custom_classes = (isset($this->block_classes)) ? $this->block_classes : '' ;
        $bg_classes = (isset($this->design['section']['bg_color'])) ? ' bg-' .$this->design['section']['bg_color'] : '' ;
        $text_classes = (isset($this->design['section']['text_color'])) ? ' text-' .$this->design['section']['text_color'] : '' ;
        $text_classes.= (isset($this->design['section']['text_align'])) ? ' '.$this->design['section']['text_align'] : '' ;
        $this->class = trim('block b-' . str_replace('_', '-', $this->slug) . ' ' . $custom_classes . $bg_classes . $text_classes);
        $this->class = str_replace('  ', ' ', $this->class);

        $this->container = (isset($this->container['bg_color']) && !empty($this->container['bg_color'])) ? ' bg-' .$this->container['bg_color'] : '' ;
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
        }else{
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
        $this->global_fields = $this->FieldsController->set_global_fields($this->global_fields, $this->fields_config);
        $this->fields = $this->FieldsController->build_fields(
            $this->set_fields(),
            $this->global_fields,
            $this->slug,
            $this->theme_colors,
            $this->field_options
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
        $props = call_user_func(['ACF_Gutenberg\Classes\Config', $this->slug]);
        if (is_array($props)) {
            foreach ($props as $prop) {
                if (function_exists('get_field')) {
                    $this->{$prop} = get_field($prop);
                }
            }
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
        $this->classes = [
            $this->class
        ];

        if ($intro_class = $this->extra_classes) {
            $this->classes[] = $intro_class;
        }
    }

    /**
     * Add classes to the main HTML element.
     *
     * @param array $classes
     */
    public function add_classes(array $classes)
    {
        foreach ($classes as $class) {
            $this->classes[] = $class;
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
    public function classes(array $classes = [])
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
        if ((is_array($this->background_image) && isset($this->background_image['url']))) {
            $this->styles['background-image'] = 'url(\'' . esc_url($this->background_image['url']) . '\')';
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
    public function styles()
    {
        return $this->get_parsed_styles();
    }

    public function get_settings()
    {
        $this->settings['name'] = $this->slug;
        $this->settings['render_callback'] = $this->render_callback;


        return $this->settings;
    }

    public function get_ipsum(){
        return "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
    }

}
