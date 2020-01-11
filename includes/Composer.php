<?php

namespace ACF_Gutenberg\Includes;

use ACF_Gutenberg\Includes\Lib;

class Composer
{
    protected  $views;

	/**
	 * Return composer views.
	 *
	 * @return array
	 */
	public function getViews()
	{
		return $this->views;
	}

	/**
	 * Data to be passed to view before rendering, but after merging.
	 *
	 * @return array
	 */
	public function with( $data = false )
	{
	}

	/**
	 * Data to be passed to view before rendering, but after merging.
	 *
	 * @return array
	 */
	public function override( $data = false )
	{
		return [
			'class'   => $this->getClass( $data ),
		];
	}

	/**
	 *
	 *
	 * @return string
	 */
	public function getClass( $data = false )
	{
		$class_base = Lib\config('components.class_base');
		$class_custom = ( isset( $data['class'] ) ) ? ' ' . $data['class'] : null;
		$class_component = ' ' . $this->getComponentClass();
		return $class_base . $class_component . $class_custom;
	}

	/**
	 * Return the class for the component based on the class name
	 *
	 * @return string
	 */
	public function getComponentClass()
	{
		$prefix = $class_base = Lib\config('components.class_prefix');
		$class_name = get_class( $this );
		$class_name = ( $pos = strrpos($class_name, '\\')) ? substr( $class_name, $pos + 1) : $class_name;
		return $prefix .  strtolower( $class_name );
	}
}
