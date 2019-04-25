<?php

namespace ACF_Gutenberg;

use Roots\Clover\ServiceProvider;

class AcfGutenbergServiceProvider extends ServiceProvider
{
    /**
     * Register the plugin with the application container.
     *
     * @return void
     */
    public function register()
    {
        $this->meta = $this->app['acf-gutenberg.meta'];
        $this->app->singleton('acf-gutenberg', AcfGutenberg::class);
        parent::register();

        //--
    }

    /**
     * Run the plugin
     *
     * @return void
     */
    public function boot()
    {
        //--

        parent::boot();
    }
}
