<?php

namespace AcfGutenberg;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use AcfGutenberg\Contracts\Block as BlockContract;
use AcfGutenberg\Concerns\InteractsWithBlade;

abstract class Block extends Composer implements BlockContract
{
    use InteractsWithBlade;

    /**
     * block ID.
     *
     * @var string
     */
    public $id;

    /**
     * Current position inside a loop of blocks.
     *
     * @var int
     */
    public static $position = 0;

    /**
     * The block properties.
     *
     * @var array
     */
    public $block;

    /**
     * The block content.
     *
     * @var string
     */
    public $content;

    /**
     * The block preview status.
     *
     * @var bool
     */
    public $preview;

    /**
     * The current post ID.
     *
     * @param int
     */
    public $post;

    /**
     * The block classes.
     *
     * @param string
     */
    public $classes;

    /**
     * The block prefix.
     *
     * @var string
     */
    public $prefix = 'acf/';

    /**
     * The block namespace.
     *
     * @var string
     */
    public $namespace;

    /**
     * The block name.
     *
     * @var string
     */
    public $name = '';

    /**
     * The block slug.
     *
     * @var string
     */
    public $slug = '';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = '';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = '';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = '';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = [];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = '';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [];

    /**
     * Assets enqueued when rendering the block.
     *
     * @return void
     */
    public function enqueue()
    {
        //
    }

    /**
     * Get block slug based on the Class name.
     *
     * @return string
     */
    public function slug()
    {
        return str_replace('app-blocks-', '', $this->from_camel_case(get_class($this)));
    }

    public function from_camel_case($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }

    /**
     * Compose the defined field group and register it
     * with Advanced Custom Fields.
     *
     * @return void
     */
    public function compose()
    {
        if (empty($this->name)) {
            return;
        }

        if (! empty($this->name) && empty($this->slug)) {
            $this->slug = Str::slug(Str::kebab($this->name));
        }

        if (empty($this->view)) {
            $this->view = Str::start($this->slug, 'blocks.');
        }

        if (empty($this->namespace)) {
            $this->namespace = Str::start($this->slug, $this->prefix);
        }

        if (! Arr::has($this->fields, 'location.0.0')) {
            Arr::set($this->fields, 'location.0.0', [
                'param' => 'block',
                'operator' => '==',
                'value' => $this->namespace,
            ]);
        }

        // The matrix isn't available on WP > 5.5
        if (Arr::has($this->supports, 'align_content') && version_compare('5.5', get_bloginfo('version'), '>')) {
            if (! is_bool($this->supports['align_content'])) {
                $this->supports['align_content'] = true;
            }
        }

        if ($globalfields = $this->app->config->get('acf.globalfields')) {
            $this->appendGlobals($globalfields);
        }

        $this->register(function () {
            acf_register_block([
                'name' => $this->slug,
                'title' => $this->name,
                'description' => $this->description,
                'category' => $this->category,
                'icon' => $this->icon,
                'keywords' => $this->keywords,
                'parent' => $this->parent ?: null,
                'post_types' => $this->post_types,
                'mode' => $this->mode,
                'align' => $this->align,
                'align_text' => $this->align_text ?? $this->align,
                'align_content' => $this->align_content,
                'styles' => $this->styles,
                'supports' => $this->supports,
                'example' => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => $this->example,
                    ]
                ],
                'enqueue_assets' => function () {
                    return $this->enqueue();
                },
                'render_callback' => function ($block, $content = '', $preview = false, $post_id = 0) {
                    echo $this->render($block, $content, $preview, $post_id);
                }
            ]);
        });

        return $this;
    }

    /**
     * Render the ACF block.
     *
     * @param  array $block
     * @param  string $content
     * @param  bool $preview
     * @param  int $post_id
     * @return void
     */
    public function render($block, $content = '', $preview = false, $post_id = 0)
    {
        $this->set_id();

        $this->block = (object) $block;
        $this->content = $content;
        $this->preview = $preview;

        $this->post = get_post($post_id);
        $this->post_id = $post_id;

        $this->classes = collect([
            'slug' => Str::start(
                Str::slug($this->slug),
                'wp-block-'
            ),
            'custom-slug' => Str::start(
                Str::replaceFirst('acf/', '', $this->block->name),
                'b-'
            ),
            'align' => ! empty($this->block->align) ?
                Str::start($this->block->align, 'align') :
                false,
            'align_text' => ! empty($this->supports['align_text']) ?
                Str::start($this->block->align_text, 'align-text-') :
                false,
            'align_content' => ! empty($this->supports['align_content']) ?
                Str::start($this->block->align_content, 'is-position-') :
                false,
            'classes' => $this->block->className ?? false,
        ])->filter()->implode(' ');

        $acf_vars = [];
        foreach (array_column($this->fields['fields'], 'name') as $value) {
            $acf_vars[$value] = get_field($value);
        };

        return $this->view(
            Str::finish('blocks.', $this->slug),
            array_merge(['block' => $this], $acf_vars)
        );
    }

    /**
     * Set Block ID
     * Return an ID if set or the block position
     *
     * @return void
     */
    public function set_id()
    {
        $this->id = 'block-' . self::$position++;
    }

    /**
     * Append Global Fields
     *
     *  @return void
     */
    public function appendGlobals($globalfields)
    {
        foreach ($globalfields as $key => $global) {
            // Replace keys (This has to be improved)
            $block_key = str_replace('group_', '', $this->fields['key']);
            $global_key = str_replace('group_', '', $global['key']);

            array_walk_recursive($global['fields'], function (&$val) use ($global_key, $block_key) {
                $val = str_replace($global_key, $block_key, $val);
            });

            // Find the position in the array where the design tab is located
            $design_tab_pos = array_search($key, array_column($this->fields['fields'], 'name'));

            // If there isn't a design tab, merge the global settings, else append to the beginning of the tab
            if (!$design_tab_pos) {
                $this->fields['fields'] = array_merge($this->fields['fields'], $global['fields']);
            } else {
                $global_array = array_filter($global['fields'], function ($var) use ($key) {
                    return ($var['name'] !== $key);
                });
                array_splice($this->fields['fields'], $design_tab_pos + 1, 0, $global_array);
            }
        }
    }
}
