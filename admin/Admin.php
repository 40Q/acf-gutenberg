<?php

namespace ACF_Gutenberg\Admin;

use StoutLogic\AcfBuilder\FieldsBuilder;
use ACF_Gutenberg\Includes\Lib;

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

		wp_enqueue_style( $this->plugin_name, ACFGB_URL . 'admin/assets/css/acf-gutenberg-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'font', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( $this->plugin_name, ACFGB_URL . 'admin/assets/js/acf-gutenberg-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function enqueue_acf_scripts() {

		wp_enqueue_script( $this->plugin_name . '-acf', ACFGB_URL. 'admin/assets/js/acf-scripts.js', array( 'jquery' ), $this->version, false );

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

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.1.0
	 */
	public function builder_settings() {

		/**
		 * Add ACF Option pages
		 */
		if  ( function_exists('acf_add_options_page') && function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_options_page([
				'page_title' => 'ACF Gutenberg Settings',
				'menu_title' => 'ACFG Builder',
				'menu_slug'  => 'acfg-builder-settings',
//				'parent_slug'	=> 'acfg-builder-settings',
				'capability' => 'manage_options',
				'redirect'   => false
			]);


			$wrapper_1_3 = [
				'width' => '33%',
			];

			$wrapper_1_2 = [
				'width' => '50%',
			];

			$wrapper_1_4 = [
				'width' => '25%',
			];

			/**
			 * -------------------------------------
			 * SINGLE OPTIONS
			 * -------------------------------------
			 */

			$o__theme_colors = new FieldsBuilder('o__theme_colors');
			$o__theme_colors
				->addGroup('theme_colors', ['label' => '',])
					->addColorPicker('primary', [ 'wrapper' => $wrapper_1_3 ] )
					->addColorPicker('secondary', [ 'wrapper' => $wrapper_1_3 ])
					->addColorPicker('tertiary', [ 'wrapper' => $wrapper_1_3 ])
				->endGroup();

			$buttons_styles = Lib\config( 'builder.button_styles' );

			$o__button_type = new FieldsBuilder('o__button_type');
			$o__button_type
				->addTrueFalse( 'use_btn_presets', ['ui' => 1] )
				->addRadio( 'btn-primary',
					[
						'label' => '',
						'wrapper' => [
							'class' => 'option-flex',
						],
						'choices' => ( $buttons_styles ) ? $buttons_styles : [],
					])
					->conditional('use_btn_presets', '==', '1');

			$o__text_align = new FieldsBuilder('o__text_align');
			$o__text_align
				->addRadio( 'text_align',
					[
						'wrapper' => [
							'class' => 'option-flex',
						],
						'choices' => [
							'left'    => '<i class="fa fa-align-left" aria-hidden="true"></i>',
							'center'  => '<i class="fa fa-align-center" aria-hidden="true"></i>',
							'right'   => '<i class="fa fa-align-right" aria-hidden="true"></i>',
							'justify' => '<i class="fa fa-align-justify" aria-hidden="true"></i>',
						],
					]);

			/**
			 * -------------------------------------
			 * OPTION GROUPS
			 * -------------------------------------
			 */

			$g__theme_colors = new FieldsBuilder('g__theme_colors');
			$g__theme_colors
				->addAccordion('theme_colors', [ 'label' => 'Theme Colors' ])
					->addFields( $o__theme_colors )
				->addAccordion('theme_colors_end')->endpoint();

			$g__button_primary = new FieldsBuilder('g__button_primary');
			$g__button_primary
				->addAccordion('button_primary', [ 'label' => 'Button Primary' ])
					->addFields( $o__button_type )
				->addAccordion('button_primary_end')->endpoint();


			$tab_settings = ['placement' => 'left'];

			/**
			 * ACF Global Settings
			 */
			$fields['acfg_builder_settings'] = new FieldsBuilder('acfg_builder_settings');
			$fields['acfg_builder_settings']
				->addTab( 'appearance', $tab_settings )
					->addFields( $g__theme_colors )
					->addFields( $g__button_primary )
					->addFields( $o__text_align )
				->addTab( 'header', $tab_settings )
				->addTab( 'sidebar', $tab_settings )
				->addTab( 'footer', $tab_settings )
				->addTab( 'icons', $tab_settings )
				->addTab( 'layout', $tab_settings )
				->addTab( 'integration', $tab_settings )
				->setLocation( 'options_page', '==', 'acfg-builder-settings' );

			foreach ( $fields as $field ) {
				acf_add_local_field_group( $field->build() );
			}

		}
	}

	public function register_menu () {

		add_submenu_page( 'acfg-builder-settings', 'Settings', 'Settings', 'manage_options', 'acfg-builder-settings' );
		add_submenu_page( 'acfg-builder-settings', 'Blocks Library', 'Blocks Library', 'manage_options', 'edit.php?post_type=wp_block' );

	}

}
