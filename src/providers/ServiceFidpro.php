<?php
namespace fidpro\builder\providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class ServiceFidpro extends ServiceProvider
{
    protected static $published = false;

    public function register()
    {
        // Daftarkan service atau binding di sini
    }

    public function boot(Filesystem $filesystem)
    {
        if ($this->app->runningInConsole() && !$this->shouldBePublished()) {
            // Publish the commands folder to app/Console/Commands
            $this->publishes([
                __DIR__.'/../Commands' => app_path('Console/Commands'),
                // __DIR__.'/../providers/FidproServiceProvider.php' => app_path('Providers'),
                __DIR__.'/../Libraries' => app_path('Libraries'),
                __DIR__.'/../builder' => resource_path('stubs'),
                __DIR__.'/../default-assets' => public_path('assets'),
                __DIR__.'/../Helpers' => app_path('Helpers'),
                __DIR__.'/../layouts' => resource_path('views/templates'),
            ], 'fidpro-l8');

            // Copy the files inside the commands folder to app/Console/Commands
            $filesystem->copy(__DIR__.'/../core/Controller.php', app_path('Http/Controllers/Controller.php'), true);
            $filesystem->copy(__DIR__.'/../providers/FidproServiceProvider.php', app_path('Providers'), true);
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

    private function shouldBePublished()
    {
        return static::$published;
    }
}

?>