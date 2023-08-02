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
        // Perform any package specific bootstrapping here
        // Example:
        // $this->loadMigrationsFrom(__DIR__.'/path/to/migrations');
        // $this->loadViewsFrom(__DIR__.'/path/to/views', 'example');
        
        // Publish the commands folder to app/Console
        $this->publishes([
            __DIR__.'/../commands'  => app_path('Console/Commands'),
            __DIR__.'/../builder'   => resource_path('stubs'),
            __DIR__.'/../default-assets'   => public_path('assets'),
            __DIR__.'/../Helpers'   => app_path('Helpers'),
            __DIR__.'/../layouts'   => resource_path('views/templates'),
        ], 'fidpro-l8');

        // Copy the files inside the commands folder to app/Console/commands
        $filesystem->copy(__DIR__.'/../core/Controller.php', app_path('Http/Controllers/Controller.php'), true);
        $filesystem->copyDirectory(__DIR__.'/../commands', app_path('Console/Commands'));
        $filesystem->copyDirectory(__DIR__.'/../builder', resource_path('stubs'));
        $filesystem->copyDirectory(__DIR__.'/../layouts', resource_path('views/templates'));

        // Merge configuration if needed
        // $this->mergeConfigFrom(__DIR__.'/config/example.php', 'example');
    }
}

?>