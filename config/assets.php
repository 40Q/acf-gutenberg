<?php

use function Roots\Clover\plugin;

return [

    /*
    |--------------------------------------------------------------------------
    | Assets Directory URL
    |--------------------------------------------------------------------------
    |
    | The asset manifest contains relative paths to your assets. This URL will
    | be prepended when using Clover's asset management system. Change this if
    | you are pushing to a CDN.
    |
    */

    'uri' => plugin('url') . '/dist',


    /*
    |--------------------------------------------------------------------------
    | Assets Directory Path
    |--------------------------------------------------------------------------
    |
    | The asset manifest contains relative paths to your assets. This path will
    | be prepended when using Clover's asset management system.
    |
    */

    'path' => plugin('directory') . '/dist',


    /*
    |--------------------------------------------------------------------------
    | Assets Manifest
    |--------------------------------------------------------------------------
    |
    | Your asset manifest is used by Clover to assist WordPress and your views
    | with rendering the correct URLs for your assets. This is especially
    | useful for statically referencing assets with dynamically changing names
    | as in the case of cache-busting.
    |
    */

    'manifest' => plugin('directory') . '/dist/assets.json',
];
