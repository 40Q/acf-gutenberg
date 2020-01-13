<?php

use ACF_Gutenberg\Includes\Lib;

return [

    'css_framework' => 'bootstrap', // tailwind || bootstrap
    'classes' => Lib\getBuilderClasses( 'bootstrap' ),

    'templates_dir' => 'templates',

    'btn_class_base' => 'btn',


    /*
    |--------------------------------------------------------------------------
    | Design Options
    |--------------------------------------------------------------------------
    |
    | Set options for the flexible block
    |
    */

    'bg_color' => [
        'bg-primary'   => 'Primary',
        'bg-secondary' => 'Secondary',
        'bg-tertiary'  => 'Tertiary',
        'bg-white'     => 'White',
        'bg-gray'  	   => 'Gray',
        'bg-black'     => 'Black',
    ],

    'text_align' => [
        'text-left'    => 'Left',
        'text-center'  => 'Center',
        'text-right'   => 'Right',
        'text-justify' => 'Justify',
    ],

    'text_color' => [
        'text-primary'   => 'Primary',
        'text-secondary' => 'Secondary',
        'text-tertiary'  => 'Tertiary',
        'text-white'     => 'White',
        'text-gray'  	 => 'Gray',
        'text-black'     => 'Black',
    ],

    'text_font' => [
        'text-primary'   => 'Primary',
        'text-secondary' => 'Secondary',
        'text-tertiary'  => 'Tertiary',
    ],

    'button' => [
        'btn-primary'           => 'Primary',
        'btn-secondary'         => 'Secondary',
        'btn-primary-outline'   => 'Primary Outline',
        'btn-secondary-outline' => 'Secondary Outline',
    ],

    'heading_tag' => [
        'h1' => 'H1',
        'h2' => 'H2',
        'h3' => 'H3',
        'h4' => 'H4',
        'h5' => 'H5',
        'h6' => 'H6',
    ],

    'padding' => [
        'p-1'   => 'SM',
        'p-3'   => 'MD',
        'p-5'   => 'LG',
    ],

    'margin' => [
        'm-1'   => 'SM',
        'm-3'   => 'MD',
        'm-5'   => 'LG',
    ],

    'shadow' => [
        'shadow-sm' => 'LG',
        'shadow'    => 'MD',
        'shadow-lg' => 'LG',
    ],

    'new_option' => [
    ],


];
