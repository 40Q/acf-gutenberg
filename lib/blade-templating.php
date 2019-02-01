<?php

namespace ACF_Gutenberg\Lib;

use ACF_Gutenberg;

$plugin_directory = ACF_Gutenberg\_get_plugin_directory();
if (!is_dir($plugin_directory . '/blocks')) {
    mkdir($plugin_directory . '/blocks', 0755, true);
}

if (!is_dir($plugin_directory . '/cache/blade')) {
    mkdir($plugin_directory . '/cache/blade', 0755, true);
}

$views = $plugin_directory . '/blocks';
$cache = $plugin_directory . '/cache/blade';
$GLOBALS['blade_engine'] = new \Philo\Blade\Blade($views, $cache);
