<?php

namespace ACF_Gutenberg\Includes;

use ACF_Gutenberg\Includes\Lib;

class Flexible {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function get_row_class( $row = false ) {

		$class = null;

		$classes = Lib\config('builder.classes');
		if ( isset( $classes['grid'] ) && isset( $classes['grid']['row'] ) ) {
			$class = $classes['grid']['row']['default'];
		}

		return $class;
	}


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function get_column_class( $column ) {

		$class = null;

		$classes = Lib\config('builder.classes');
		if ( isset( $classes['grid'] ) && isset( $classes['grid'][$column] ) ) {
			$class = $classes['grid'][$column];
		}

		return $class;
	}

}
