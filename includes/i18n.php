<?php

namespace ACF_Gutenberg\Includes;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.0
 *
 * @package    ACF_Gutenberg
 * @subpackage ACF_Gutenberg/includes
 */

class i18n {


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.1.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'acf-gutenberg',
            false,
            ACFGB_PATH . '/languages/'
        );


    }



}
