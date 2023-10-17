<?php

namespace fidpro\builder\providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class ServiceFidpro extends ServiceProvider
{
    public function register()
    {
        // Daftarkan service atau binding di sini
    }

    public function boot(Filesystem $filesystem)
    {
        if ($this->app->runningInConsole() && !$this->hasRunBefore()) {
            // Publish the commands folder to app/Console/Commands
            $this->publishes([
                __DIR__.'/../Commands' => app_path('Console/Commands'),
                __DIR__.'/../providers/FidproServiceProvider.php' => app_path('Providers/FidproServiceProvider.php'),
                __DIR__.'/../routes/builder.php' => base_path('Routes/builder.php'),
                __DIR__.'/../Libraries' => app_path('Libraries'),
                __DIR__.'/../builder' => resource_path('stubs'),
                __DIR__.'/../default-assets' => public_path('assets'),
                __DIR__.'/../plugins' => public_path('plugins'),
                __DIR__.'/../Helpers' => app_path('Helpers'),
                __DIR__.'/../layouts' => resource_path('views/templates'),
                __DIR__.'/../config/fidproConf.php' => config_path('fidproConf.php'),
            ], 'fidpro-l8');

            $this->publishes([
                __DIR__.'/../src' => base_path('vendor/fidpro/builder/src'),
            ], 'autoload');

            // Copy the files inside the commands folder to app/Console/Commands
            $filesystem->copy(__DIR__.'/../core/Controller.php', app_path('Http/Controllers/Controller.php'), true);
            $filesystem->copyDirectory(__DIR__.'/../Commands', app_path('Console/Commands'));
            $filesystem->copyDirectory(__DIR__.'/../Libraries', app_path('Libraries'));
            $filesystem->copyDirectory(__DIR__.'/../builder', resource_path('stubs'));
            $filesystem->copyDirectory(__DIR__.'/../layouts', resource_path('views/templates'));
        }

        // Perform any other package specific bootstrapping here
        // Example:
        $this->loadMigrationsFrom(__DIR__.'/../Migrations');
        // $this->loadViewsFrom(__DIR__.'/path/to/views', 'example');

        // Merge configuration if needed
        // $this->mergeConfigFrom(__DIR__.'/config/example.php', 'example');
    }

    private function hasRunBefore()
    {
        return config('fidproConf.running', false);
    }
}
