<?php

namespace ACF_Gutenberg\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.1.0
 *
 * @package    ACF_Gutenberg
 * @subpackage ACF_Gutenberg/admin
 */

class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in ACF_Gutenberg_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The ACF_Gutenberg_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_style( $this->plugin_name, ACFGB_PATH . '/admin/assets/css/acf-gutenberg-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, 'http://40q-acf-gutenberg.local/site/web/app/plugins/acf-gutenberg/admin/assets/css/acf-gutenberg-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_scripts() {


		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in ACF_Gutenberg_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The ACF_Gutenberg_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_script( $this->plugin_name, ACFGB_PATH . '/admin/assets/js/acf-gutenberg-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, 'http://40q-acf-gutenberg.local/site/web/app/plugins/acf-gutenberg/admin/assets/js/acf-gutenberg-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function enqueue_acf_scripts() {

		wp_enqueue_script( $this->plugin_name . '-acf', 'http://40q-acf-gutenberg.local/site/web/app/plugins/acf-gutenberg/admin/assets/js/acf-scripts.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript and styles for Gutenberg blocks.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_block_editor_assets() {


        // Make paths variables so we don't write em twice ;)
        $js_path = ACFGB_PATH . '/admin/assets/js/editor.blocks.js';
        $style_path = '/admin/assets/css/blocks.editor.css';

        // Enqueue the bundled block JS file
        wp_enqueue_script(
            'jsforwp-blocks-js',
            $js_path,
            ['wp-i18n', 'wp-element', 'wp-blocks', 'wp-components'],
            filemtime($js_path)
        );


        // Enqueue optional editor only styles
        wp_enqueue_style(
            'jsforwp-blocks-editor-css',
            $style_path,
            ['wp-blocks'],
            $style_path
        );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_block_assets() {

        // Make paths variables so we don't write em twice ;)
        $style_path = ACFGB_PATH . '/admin/assets/css/blocks.style.css';
        wp_enqueue_style(
            'jsforwp-blocks',
            $style_path,
            ['wp-blocks'],
            filemtime($style_path)
        );

	}

}
