<?php

namespace ACF_Gutenberg;

use Roots\Clover\Plugin as Clover;

class Plugin extends Clover
{
    /**
     * Run the plugin.
     */
    public function run()
    {
        /** Lifecycle hooks & actions */
        register_activation_hook($this->get('path'), [$this, 'activate']);
        register_deactivation_hook($this->get('path'), [$this, 'deactivate']);
        add_action($this->getTag('upgrade'), [$this, 'upgrade']);
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
}
