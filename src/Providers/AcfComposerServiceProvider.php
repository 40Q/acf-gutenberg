<?php

namespace AcfGutenberg\Providers;

use Roots\Acorn\ServiceProvider;
use AcfGutenberg\AcfComposer;

class AcfComposerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AcfComposer', function () {
            return new AcfComposer($this->app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('AcfComposer');

        $this->publishes([
            __DIR__ . '/../../config/acf.php' => $this->app->configPath('acf.php'),
        ], 'config');

        $this->commands([
            \AcfGutenberg\Console\BlockMakeCommand::class,
            \AcfGutenberg\Console\FieldMakeCommand::class,
            \AcfGutenberg\Console\PartialMakeCommand::class,
            \AcfGutenberg\Console\WidgetMakeCommand::class,
            \AcfGutenberg\Console\OptionsMakeCommand::class,
        ]);
    }
}
