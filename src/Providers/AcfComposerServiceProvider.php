<?php

namespace AcfGutenberg\Providers;

use ReflectionClass;
use Illuminate\Support\Str;
use AcfGutenberg\Composer;
use AcfGutenberg\Partial;
use Roots\Acorn\ServiceProvider;
use Symfony\Component\Finder\Finder;

class AcfComposerServiceProvider extends ServiceProvider
{
    /**
     * Default Paths
     *
     * @var \Illuminate\Support\Collection
     */
    protected $paths = [
        'Fields',
        'Blocks',
        'Widgets',
        'Options',
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->paths = collect($this->paths)->map(function ($path) {
            return $this->app->path($path);
        })->filter(function ($path) {
            return is_dir($path);
        });

        $this->app->singleton('AcfComposer', function () {
            return $this->compose();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->create_section_component();
        $this->create_global_settings();

        if (
            function_exists('acf') &&
            ! $this->paths->isEmpty()
        ) {
            $this->app->make('AcfComposer');
        }

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

    public function create_section_component() {

        if ( ! file_exists( $this->app->resourcePath('views/components/section.blade.php') ) ) {
            \FileManager::copy_file(
                __DIR__ . '/../../src/Console/components/section.blade.php',
                $this->app->resourcePath('views/components/'),
                'section.blade.php'
            );
        }

        if ( ! file_exists( $this->app->basePath('app/View/Components/Section.php') ) ) {
            \FileManager::copy_file(
                __DIR__ . '/../../src/Console/components/Section.php',
                $this->app->basePath('app/View/Components/'),
                'Section.php'
            );
        }

    }

    public function create_global_settings() {

        if ( ! is_dir( $dir = $this->app->basePath('app/Options') ) ) {
            $dir = $this->app->basePath('app/Options');
            exec("mkdir {$dir}");
        }

        if ( ! file_exists( $this->app->basePath('app/Options/GlobalSettings.php') ) ) {
            \FileManager::copy_file(
                __DIR__ . '/../../src/Console/Options/GlobalSettings.php',
                $this->app->basePath('app/Options/'),
                'GlobalSettings.php'
            );
        }

    }

    /**
     * Find and compose the available field groups.
     *
     * @return void
     */
    public function compose()
    {
        foreach ((new Finder())->in($this->paths->all())->files() as $composer) {
            $composer = $this->app->getNamespace() . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($composer->getPathname(), $this->app->path() . DIRECTORY_SEPARATOR)
            );

            if (
                is_subclass_of($composer, Composer::class) &&
                ! is_subclass_of($composer, Partial::class) &&
                ! (new ReflectionClass($composer))->isAbstract()
            ) {
                (new $composer($this->app))->compose();
            }
        }
    }
}
