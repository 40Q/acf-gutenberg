<?php

namespace ACF_Gutenberg\Classes;
use StoutLogic\AcfBuilder\FieldsBuilder;
use ACF_Gutenberg\Lib;

/**
 * Class Block
 * @package App\Builder
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
     * Internal prefix for a block's data.
     *
     * @var string
     */
    public $prefix = '';

    /**
     * Current position inside a loop of blocks.
     *
     * @var int
     */
    public $position = 0;

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

    /**
     * The actions of this plugin.
     *
     * @since    0.1.0
     * @access   public
     * @var      array  $actions
     */
    public $actions;
    public $text = '';
    public $text_group = [];

    public $template = '';

    public $is_button_empty = true;

    /**
     * Block constructor.
     *
     * @param array $args
     */
    public function __construct()
    {
        $this->set_settings();
        $this->set_slug();
        add_action('acf/init', array($this, 'register_block'));
        $this->set_class();
        $this->set_fields();
        add_action('init', array($this, 'build_fields'));
        $this->set_props();
        $this->set_position();
        $this->set_classes();
        $this->set_styles();

        $this->init();
    }

    public function init()
    {
        // Use this method in extended classes
    }
    public function set_slug()
    {
        $this->slug = $this->settings['name'];
    }
    public function set_class()
    {
        $this->class = 'block b-' . str_replace('_', '-', $this->slug);
    }
    public function set_position()
    {
        global $count;
        $this->position = intval($count++);
        $this->id = $this->id ?: "block-{$this->position}";
    }
    public function set_settings()
    {
        $this->settings = [
            'name' => 'acf-block',
            'title' => __('ACF Block'),
            'description' => __('ACF Block.'),
            'render_callback' => 'ACF_Gutenberg\Lib\bmy_acf_block_render_callback',
            'category' => 'common',
            'icon' => 'menu',
            'keywords' => ['acf-block'],
        ];
    }

    public function set_fields()
    {
        $fields['acf-block'] = new FieldsBuilder('acf-block');
        $fields['acf-block']
            ->addText('title')
            ->setLocation('block', '==', 'acf/acf-block');
        $this->fields = $fields;

    }

    public function build_fields(){
        if (function_exists('acf_add_local_field_group')) {
            foreach ($this->fields as $field) {
                $block_content = $field->build();
                \ACF_Gutenberg\Classes\Config::createDynamic(str_replace('group_', '', $block_content['key']), array_column($block_content['fields'], 'name'));
                acf_add_local_field_group($block_content);
            }
        }
    }

    public function register_block()
    {
            if (function_exists('acf_register_block')) {
                acf_register_block($this->settings);
            }
    }

    public function render_block()
    {
        if (file_exists(ACFGB_PATH_RESOURCES . "/blocks/{$this->slug}/{$this->slug}.blade.php")) {
            Lib\render_plugin_view("{$this->slug}.{$this->slug}", ['block' => $this]);
        } elseif (file_exists(ACFGB_PATH_RESOURCES . "/blocks/{$this->slug}/index.blade.php")) {
            Lib\render_plugin_view("{$this->slug}.index", ['block' => $this]);
        } elseif (get_template_directory() . "/acf-gutenberg/blocks/{$this->slug}/{$this->slug}.blade.php") {
            Lib\render_theme_view("{$this->slug}.{$this->slug}", ['block' => $this]);
        }
    }


    public function set_props()
    {
        /**
         * Register properties
         */
        $props = call_user_func(['ACF_Gutenberg\Classes\Config', $this->slug]);
        if(is_array($props)){
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
     * Create Dynamic Array
     */
    public static function createDynamic($variable, $value)
    {
        self::$myvariablearray[$variable] = $value;
    }

    /**
     * Call Static
     */
    public static function __callstatic($name, $arguments)
    {
        return self::$myvariablearray[$name];
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
}
