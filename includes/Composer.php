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
		$class_module = null;

		if ( $this->isModule( $data ) ) {
			$class_module = ' ' . $this->getModuleClass( $data['module'] );
		}

		return $class_base . $class_component . $class_custom;
//		return $class_component;
//		return $class_module;
//		return $class_custom;
//		return $class_base . $class_component . $class_module . $class_custom;
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

	/**
	 * Return the class for the module based on the module data
	 *
	 * @return string
	 */
	public function getModuleClass( $module )
	{
		$prefix = $class_base = Lib\config('modules.class_prefix');
		return $prefix .  strtolower( $module['acf_fc_layout'] );
	}

	/**
	 * Get row class
	 *
	 * @return array
	 */
	public function getRowClass( $data = false )
	{

		$row_class = Flexible::get_row_class();
		$row_class = ( $row_class ) ? ' ' . $row_class : $row_class;

		return $row_class;
	}

	/**
	 * Get column class
	 *
	 * @return array
	 */
	public function getColumnClass( $data = false )
	{
		$col_class = null;

		if ( isset( $data['cols'] ) && is_numeric( $data['cols'] ) ) {
			$col_class = Flexible::get_column_class( 'col_' . $data['cols'] );
			$col_class = ( $col_class ) ? ' ' . $col_class : $col_class;
		}

		return $col_class;
	}

	/**
	 * Get module props
	 *
	 * @return array
	 */
	public function getModuleProps( $data )
	{
		$props = [];
		if ( isset( $data['module'] ) && is_array( $data['module'] ) ){
			foreach ( $data['module'] as $prop => $value ) {
				if ( 'acf_fc_layout' != $prop ) {
					$props[$prop] = $value;
				}
			}
		}

		return $props;
	}

	/**
	 * Get module props
	 *
	 * @return string
	 */
	public function getModuleLayout( $data )
	{
		$layout = null;

		if ( $this->isModule( $data ) ){
			$layout_name = $data['module']['acf_fc_layout'];
			$layout_path = get_template_directory() . "/acf-gutenberg/modules/{$layout_name}/{$layout_name}.blade.php";
			if ( file_exists( $layout_path ) ) {
				$layout = "modules.{$layout_name}.{$layout_name}";
			}
		}

		return $layout;
	}

	/**
	 * Get module props
	 *
	 * @return string
	 */
	public function getStyleClass( $data, $styles )
	{
		$style_class = false;
		if ( isset( $data['module'] ) ) {

			if ( is_array( $styles ) ) {

				foreach ( $styles as $style ) {
	//				$style_class .= ( isset( $data[$style] ) ) ? ' ' . $data[$style] : false;
					$style_class .= ( isset( $data['module'][$style] ) ) ? ' ' . $data['module'][$style] : false;
				}

			}else {
				$style_class .= ( isset( $data[$styles] ) ) ? ' ' . $data[$styles] : false;
			}
		}




		return $style_class;
	}

	public function getClassAnsStyles( $data = false )
	{
		$classes = $this->getClass( $data );
		$classes .= $this->getStyleClass( $data, [
			'bg_color',
			'text_align',
			'text_color',
			'padding',
			'margin',
			'shadow',
		]);

		$preset_classes = isset( $data['module']['preset_classes'] ) ? $data['module']['preset_classes'] : false;
		$classes .= is_array( $preset_classes ) ? ' ' . join( ' ', $preset_classes ) : false;
		$classes .= isset( $data['module']['custom_classes'] ) ? ' ' . $data['module']['custom_classes'] : false;

		return $classes;
	}

	public function isModule( $data ) {
		$module = false;

		if ( isset( $data['module'] ) && isset( $data['module']['acf_fc_layout'] ) ) {
			$module = true;
		}

		return $module;
	}
}
