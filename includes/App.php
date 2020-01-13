<?php

namespace ACF_Gutenberg\Includes;

class App
{
	/**
	 * Display Images
	 *
	 * @param [type] $image
	 * @return void
	 */
	public static function display_image($image, $image_size = 'full', $icon = false, $attr = '')
	{
		$custom_attr = [
			'class' => 'img-fluid',
		];

		$attr = wp_parse_args( $attr, $custom_attr );

		// If it's an ID
		if (is_numeric($image) && wp_attachment_is_image($image)) {
			return wp_get_attachment_image($image, $image_size, $icon, $attr);
		}

		// If it's an String
		if (!is_array($image) && !is_object($image)) {
			$image_string = '<img class="img-fluid" src="' . $image . '" alt="">';
			return $image_string;
		}

		// If it's an svg
		if (is_array($image) && $image['mime_type'] === 'image/svg+xml') {
			$file_name = get_attached_file($image['ID']);
			if (file_exists($file_name)) {
				return file_get_contents($file_name);
			}
		}

		// If it's an Object (rare)
		if (is_object($image)) {
			return wp_get_attachment_image($image->ID, $image_size, $icon, $attr);
		}

		// If Image is empty
		if ($image == '') {
			return '<img src="http://via.placeholder.com/800x800" class="' . $class . '" alt="">';
		}

		// Else
		return wp_get_attachment_image($image['id'], $image_size, $icon, $attr);
	}

	/**
	 * Returns $value if it is set, otherwise returns $default.
	 *
	 * @param mixed $value Value.
	 * @param mixed $default Default value.
	 * @return mixed
	 */
	public static function value_or_default( $value, $default = false ) {
		return isset( $value ) ? $value : $default;
	}

	public static function get_theme_setting( $setting ) {
		$setting = false;

		if ( function_exists( 'get_field') ) {
			$setting = get_field( $setting , 'option');
		}

		return $setting;
	}

	public static function custom_styles() {
		$styles = false;

		if ( function_exists( 'get_field') ) {
			$use_custom_color = get_field( 'use_custom_color', 'option' );
			$theme_colors = get_field( 'theme_colors', 'option' );
			if ( $use_custom_color && is_array( $theme_colors ) ) {
				$styles = self::get_custom_styles( $theme_colors );
			}
		}
		echo $styles;
	}

	public static function get_custom_styles( $theme_colors ) {
		ob_start();
		?>

		<!-- Theme colors styles -->
		<style>
			a { color: <?= $theme_colors['primary'] ;?>; }
			.banner .logo .brand.property-site small { background-color: <?= $theme_colors['primary'] ;?>; }
			.btn.btn-primary {
				background-color: <?= $theme_colors['primary'] ;?>;
				border-color: <?= $theme_colors['primary'] ;?>;
			}
			button.slider-arrow svg path,
			.slick-lightbox button.slick-arrow svg path {
				fill: <?= $theme_colors['primary'] ;?>;
			}
		</style>

		<?php
		return ob_get_clean();
	}


}
