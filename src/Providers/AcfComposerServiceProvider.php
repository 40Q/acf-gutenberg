<?php

namespace AcfGutenberg\Providers;

use ReflectionClass;
use Illuminate\Support\Str;
use AcfGutenberg\Composer;
use Roots\Acorn\ServiceProvider;
use Symfony\Component\Finder\Finder;

class AcfComposerServiceProvider extends ServiceProvider
{
    /**
     * Default Paths
     *
     * @var array
     */
    protected $paths = [
        'Fields',
        'Blocks',
        'Widgets',
        'Options'
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '/../../src/Console/FileManager.php';

        $this->paths = collect($this->paths)->map(function ($path) {
            return $this->app->path($path);
        })->filter(function ($path) {
            return is_dir($path);
        });

        if ($this->paths->isEmpty() || ! function_exists('acf')) {
            return;
        }

        foreach ((new Finder())->in($this->paths->all())->files() as $composer) {
            $composer = $this->app->getNamespace() . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($composer->getPathname(), $this->app->path() . DIRECTORY_SEPARATOR)
            );

            if (
                is_subclass_of($composer, Composer::class) &&
                ! (new ReflectionClass($composer))->isAbstract()
            ) {
                (new $composer($this->app))->compose();
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->copy_section_component();
        $this->copy_acf_settings();

//        $this->publishes([
//            __DIR__ . '/../../config/acf.php' => $this->app->configPath('acf.php'),
//        ], 'acf-composer');

        $this->commands([
            \AcfGutenberg\Console\FieldMakeCommand::class,
            \AcfGutenberg\Console\BlockMakeCommand::class,
            \AcfGutenberg\Console\WidgetMakeCommand::class,
            \AcfGutenberg\Console\OptionsMakeCommand::class,
        ]);
    }

    public function copy_acf_settings() {
        \FileManager::copy_file(
            __DIR__ . '/../../config/acf.php',
            $this->app->configPath('/'),
            'acf.php'
        );
    }

    public function copy_section_component() {

        \FileManager::copy_file(
            __DIR__ . '/../../src/Console/stubs/views/components/section.blade.php',
            $this->app->resourcePath('views/components/'),
            'section.blade.php'
        );

        \FileManager::copy_file(
            __DIR__ . '/../../src/Console/stubs/views/components/Section.php',
            $this->app->basePath('app/View/Components/'),
            'Section.php'
        );

    }
}
