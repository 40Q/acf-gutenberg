<?php

return [
    'assets' => [
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

        'uri' => plugins_url('dist', __FILE__),

        /*
        |--------------------------------------------------------------------------
        | Assets Directory Path
        |--------------------------------------------------------------------------
        |
        | The asset manifest contains relative paths to your assets. This path will
        | be prepended when using Clover's asset management system.
        |
        */

        'path' => dirname(__DIR__) . '/dist',

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

        'manifest' => dirname(__DIR__) . '/dist/assets.json',
    ],

    /*
    |--------------------------------------------------------------------------
    | View Storage Path
    |--------------------------------------------------------------------------
    |
    | Most template systems load templates from disk. Here you may specify
    | the location on your disk where your views are located.
    |
    */

    'views' => dirname(__DIR__) . '/resources/views',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        // Roots\Acorn\Assets\ManifestServiceProvider::class,
        // Roots\Acorn\View\ViewServiceProvider::class,
        PluginNamespace\PluginNameServiceProvider::class,
    ],
];
