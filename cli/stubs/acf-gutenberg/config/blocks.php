<?php

return [

	'class_prefix' => 'b__',
	'class_base' => 'b__block',

    /*
    |--------------------------------------------------------------------------
    | Blocks Paths
    |--------------------------------------------------------------------------
    |
    | Most template systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your blocks.
    |
    */

    'paths' => [
        get_theme_file_path('/acf-gutenberg/blocks'),
    ],

];
