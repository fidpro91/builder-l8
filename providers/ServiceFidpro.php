<?php
namespace fidpro\builder\providers;

use Illuminate\Support\ServiceProvider;

class ServiceFidpro extends ServiceProvider
{
    public function register()
    {
        // Daftarkan service atau binding di sini
    }

    public function boot()
    {
        // Lakukan konfigurasi atau bootstrapping di sini
        /* $this->publishes([
            __DIR__.'/path/ke/file/konfigurasi.php' => app_path('nama_file_konfigurasi.php'),
        ], 'config'); */
        $this->publishes([
            __DIR__.'/commands' => app_path('Console'),
        ], 'Commands');
    }
}

?>