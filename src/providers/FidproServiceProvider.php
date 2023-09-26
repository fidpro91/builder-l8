<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FidproServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $currentPath = request()->path();
        if (strpos($currentPath, "fidpro") !== false) {
            $view = str_replace('/','.',str_replace('fidpro','',$currentPath));
            Route::view($currentPath,"templates.".$view);
        }
    }
}
