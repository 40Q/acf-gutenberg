<?php

namespace ACF_Gutenberg\Includes\Lib;
use ACF_Gutenberg\Includes;

function convert_to_class_name($str)
{
    $str = ucwords(str_replace('-', ' ', $str));
    $str = ucwords(str_replace('_', ' ', $str));
    return str_replace(' ', '', $str);
}

function get_compatibility_mode(){
    $compatibility_mode = false;
    $compatibility_mode = apply_filters('acfgb_compatibility_mode', $compatibility_mode);
    return $compatibility_mode;
}

function get_props_by_block_data($block_data){
    if (is_array($block_data)){
        foreach ($block_data as $field_key => $field_value){
            $acf_key = strpos($field_key, "_");
            $acf_value = strpos($field_value, "field_");
            if ($acf_key === 0 && $acf_value === 0){
                unset($block_data[$field_key]);
            }
        }
    }
    return $block_data;
}

if (file_exists(get_template_directory() . '/acf-gutenberg/config/settings.php')) {
    include get_template_directory() . '/acf-gutenberg/config/settings.php';
}

if (file_exists(get_template_directory() . '/acf-gutenberg/config/global_fields.php')) {
    include get_template_directory() . '/acf-gutenberg/config/global_fields.php';
}

if (file_exists(get_template_directory() . '/acf-gutenberg/config/global_fields.php')) {
	include get_template_directory() . '/acf-gutenberg/config/global_fields.php';
}

function config ( $setting ) {
	$setting = explodeConfig( $setting );
	$config = getConfigFile( $setting->file );

	return getConfig( $config, $setting->key );
}

function explodeConfig ( $setting ) {
	$setting = explode( '.', $setting );

	if ( ! is_array( $setting ) || count( $setting ) < 2 )
		return false;

	return ( object ) [
		'file' => $setting[0],
		'key'  => $setting[1],
	];
}

function getConfigFile ( $file_name ) {
	$config_path = get_template_directory() . '/acf-gutenberg/config/';
	$file = $config_path . $file_name . '.php';

	if ( ! file_exists( $file ) )
		return false;

	return include $file;
}

function getConfig ( $config, $key ) {
	return ( is_array( $config ) && isset( $config[$key] ) ) ? $config[$key] : false;
}

function getComponentClasses ( $class_base, $class_component, $class_custom = false ) {
	$classes = $class_base . ' ' . $class_component;
	return ( $class_custom ) ? $classes . ' ' . $class_custom : $class_custom;
}

function getBuilderClasses( $framework ) {

	return include ACFGB_PATH . '/config/' . $framework . '.php';
}

function valueOrDefault ( $value, $default ) {

}


