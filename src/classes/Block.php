<?php

namespace ACF_Gutenberg\Classes;

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
    public $text = '';
    public $text_group = [];

    public $template = '';

    public $is_button_empty = true;

    /**
     * Block constructor.
     *
     * @param array $args
     */
    public function __construct(array $args = [], $slug)
    {
        global $count;

        /**
         * Register properties
         */
        foreach ($args as $prop) {
            if (function_exists('get_field')) {
                $this->{$prop} = get_field($prop);
            }
        }
        $this->slug = $slug;

        $this->class = 'block b-' . str_replace('_', '-', $this->slug);

        // $this->register_gutenberg_block($params);

        $this->set_classes();
        $this->set_styles();

        $this->position = intval($count++);
        $this->id = $this->id ?: "block-{$this->position}";
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
     * Register Gutenberg Block
     */
    private function register_gutenberg_block($params)
    {
        acf_register_block($params);
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
