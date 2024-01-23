# Welcome to FidPRO Builder to Laravel 8.*

# Instalasi
-   Buka terminal atau command prompt ketikkan :
`composer require fidpro/builder`
 -   Setelah selesai instalasi ketikkan :
    `php artisan vendor:publish --tag=fidpro-l8`
-   setting koneksi database di file .env
-   jalankan kan perintah `php artisan migrate`
-   Buka file di direktori app/Providers/RouteServiceProvider.php. aktifkan variable namespace

  `protected $namespace = 'App\\Http\\Controllers';`
  
  lalu tambahkan route ke service builder seperti di bawah ini : 

  ` Route::prefix('builder')
                ->namespace('builder')
                ->group(base_path('routes/builder.php'));`

  Tambahkan provider fidpro di direktori config/app.php

  `App\Providers\FidproServiceProvider::class`
  
  tambkan inisialises : 
  
  `"Widget"    => \fidpro\builder\Widget::class,` 
  
  `"Create"    => \fidpro\builder\Create::class,` 
  
  `"Bootstrap"    => \fidpro\builder\Bootstrap::class,`
          
  Buka file composer.json tambahkan script berikut : 

  `"autoload": {
                                    "psr-4": {
                                        "fidpro\\builder\\" : "vendor/fidpro/builder/src"
                                    }
                                }`
                                
lalu ketikkan `composer dump-autoload`
jalankan server `php artisan serve`
buka halaman dengan web browser "localhost:8000/fidpro/documentation/index"

# Selamat mencoba ...
  
