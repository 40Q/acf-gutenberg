<?php

return [

    'class_base'   => 'b__component',
    'class_prefix' => 'c__',

    /*
    |--------------------------------------------------------------------------
    | Components Paths
    |--------------------------------------------------------------------------
    |
    | Most template systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your components.
    |
    */

    'paths' => [
        get_theme_file_path('/acf-gutenberg/components'),
    ],

];
