<?php

namespace App\Composers;

use Roots\Acorn\View\Composer;

wp_die('composer');

class NewPropsComposer extends Composer {

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'new-props.new-props',
        'partials.test-composer',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function override()
    {

        return [
            'testBlock' => $this->test(),
        ];
    }

    /**
     * Returns the post title.
     *
     * @return string
     */
    public function test()
    {
        return 'composing from plugin';
    }
}
