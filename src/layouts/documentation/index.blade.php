@extends('templates.documentation.layouts')
@section('content')
<style>
    pre {
      background-color: #f4f4f4;
      padding: 10px;
      border: 1px solid #ccc;
      overflow-x: auto;
      /* Set teks menjadi di kiri */
      text-align: left !important;
    }
</style>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <ul>
                <li>CARA INSTALASI
                    <ul>
                        <li>
                            Buka terminal atau command prompt ketikkan :
                            <pre>composer require fidpro/builder</pre>
                        </li>
                        <li>Setelah selesai instalasi ketikkan : 
                            <pre>php artisan vendor:publish --tag=fid-l8</pre>
                        </li>
                        <li>setting koneksi database di file .env</li>
                        <li>jalankan kan perintah "php artisan migrate"</li>
                        <li>
                            Buka file di direktori app/Providers/RouteServiceProvider.php.
                            aktifkan variable namespace.
                            <pre>protected $namespace = 'App\\Http\\Controllers';</pre>
                            lalu tambahkan route ke service builder seperti di bawah ini
                            <pre>Route::prefix('builder')
                ->namespace('builder')
                ->group(base_path('routes/builder.php'));</pre>
                        </li>
                        <li>
                            Tambahkan provider fidpro di direktori config/app.php
                            <pre>
                            'providers' => [
                                ....
                                App\Providers\FidproServiceProvider::class,
                                ....
                            ]
                            </pre>
                            Tambahkan aliases
                            <pre>
                            'aliases' => [
                                ....
                                "Widget"    => \fidpro\builder\Widget::class,
                                "Create"    => \fidpro\builder\Create::class,
                                "Bootstrap"    => \fidpro\builder\Bootstrap::class,
                                ....
                            ]
                            </pre>
                        </li>
                        <li>
                            Buka file composer.json tambahkan script berikut :
                            <pre>
                                "autoload": {
                                    "psr-4": {
                                        "fidpro\\builder\\" : "vendor/fidpro/builder/src"
                                    }
                                }
                            </pre>
                        </li>
                        <li>lalu ketikkan "composer dump-autoload"</li>
                        <li>jalankan server "php artisan serve"</li>
                        <li>buka halaman dengan web browser "localhost:8000/fidpro/documentation/index"</li>
                    </ul>
                </li>
                <li>BUILDER CRUD
                    <UL>
                        <li>Jika menggunakan database Mysql ketikkan di terminal
                            <pre>php artisan build:crud {Namatable} {--make="all"} {--routes=true} {--breadcrumbs=true}</pre>
                            <p>- {Namatable} diganti dengan nama table yang akan dibuat CRUD</p> 
                            <p>- {--make="all"} jika hanya ingin membuat controller/model/view saja ganti dengan controller/model/view pilih salah satu</p> 
                            <p>- {--routes=true} jika tidak ingin membuat route silahkan di isi false</p> 
                            <p>- {--breadcrumbs=true} jika tidak ingin membuat breadcrumbs silahkan di isi false</p>
                            <p>command ringkas nya seperti berikut</p>
                            <pre>php artisan build:crud {Namatable}</pre>
                        </li>
                        <li>Jika menggunakan database PostgreSql ketikkan di terminal
                            <pre>postgres:crud {name} {--schema=public} {--make="all"} {--routes=true} {--breadcrumbs=true}</pre>
                            <p>- {--schema=public} jika table tidak didalam schema public silahkan diganti schema public sesuai dengan schema didatabase</p>
                            <p>- {Namatable} diganti dengan nama table yang akan dibuat CRUD</p> 
                            <p>- {--make="all"} jika hanya ingin membuat controller/model/view saja ganti dengan controller/model/view pilih salah satu</p> 
                            <p>- {--routes=true} jika tidak ingin membuat route silahkan di isi false</p> 
                            <p>- {--breadcrumbs=true} jika tidak ingin membuat breadcrumbs silahkan di isi false</p>
                            <p>command ringkas nya seperti berikut</p>
                            <pre>php artisan postgres:crud {Namatable}</pre>
                        </li>
                    </UL>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection