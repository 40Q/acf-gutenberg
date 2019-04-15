<?php

namespace Roots\Clover;

use Illuminate\Container\Container;

class Plugin extends Container
{
    protected static $instance;

    /**
     * Construct the plugin
     */
    public function __construct($file, $name, $version)
    {
        $this->setPluginHelpers($file);
        $this->__set('name', $name);
        $this->__set('version', $version);
        static::setInstance($this);
    }

    /**
     * A set of helpers to replace WordPress' useless plugin functions.
     */
    protected function setPluginHelpers($file)
    {
        $this->__set('path', $file);
        $this->__set('basename', plugin_basename($file));
        $this->__set('directory', rtrim(plugin_dir_path($file), '/'));
        $this->__set('url', rtrim(plugin_dir_url($file), '/'));
    }

    /**
     * Returns a tag prefixed with the plugin filename for usage in actions and filters.
     * @param string $tag
     * @return string
     */
    public function getTag($tag)
    {
        return basename($this->get('path'), '.php') . "_$tag";
    }
}
