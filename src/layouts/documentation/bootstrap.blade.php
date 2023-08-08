@extends('templates.documentation.layouts')
@section('content')
<?php
use \fidpro\builder\Create;
use \fidpro\builder\Widget;
use \fidpro\builder\Bootstrap;
use \fidpro\builder\Multirow;
Widget::_init(["select2","daterangepicker","datepicker","inputmask"]);
?>
<div class="col-md-12">
    <p>
        Untuk menggunakan bosstrap componen gunakan function dibawah ini
        <pre>use \fidpro\builder\Bootstrap;</pre>
    </p>
    <div id="accordion">
        <div class="card mb-0">
            <div class="card-header" id="headingOne">
                <h5 class="m-0">
                    <a href="#collapseOne" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Bootstrap Tabs
                    </a>
                </h5>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    {{
                        Bootstrap::tabs([
                            "tabs"  => [
                                "home"  => [
                                    "href"      => "home",
                                    "content"   => "<h1>ini home</h1>"
                                ],
                                "profil"  => [
                                    "href"      => "profil",
                                    "content"   => function(){
                                        return "<h1>ini profil</h1>";
                                    }
                                ],
                                "load"  => [
                                    "href"      => "loadpage",
                                    "url"       => "builder/example/bootsrap_component/load_tab_page"
                                ]
                            ]
                        ]);
                    }}
                    <pre>@verbatim
                    {{
                        Bootstrap::tabs([
                            "tabs"  => [
                                "home"  => [
                                    "href"      => "home",
                                    "content"   => "<h1>ini home</h1>"
                                ],
                                "profil"  => [
                                    "href"      => "profil",
                                    "content"   => function(){
                                        return "<h1>ini profil</h1>";
                                    }
                                ],
                                "load"  => [
                                    "href"      => "loadpage",
                                    "url"       => "builder/example/bootsrap_component/load_tab_page"
                                ]
                            ]
                        ]);
                    }}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="headingTwo">
                <h5 class="m-0">
                    <a href="#collapseTwo" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Widget Collaption
                    </a>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    {{
                        Bootstrap::collaption([
                            "id"            => "tester",
                            "collaption"    => [
                                [
                                    "name"      => "colap1",
                                    "content"   => "xnxxx"
                                ],
                                [
                                    "name"      => "colap2",
                                    "content"   => "xnxxxcs"
                                ]
                            ]
                        ])
                    }}
                    <pre>@verbatim
                    {{
                        Bootstrap::collaption([
                            "id"            => "tester",
                            "collaption"    => [
                                [
                                    "name"      => "colap1",
                                    "content"   => "xnxxx"
                                ],
                                [
                                    "name"      => "colap2",
                                    "content"   => "xnxxxcs"
                                ]
                            ]
                        ])
                    }}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="heading3">
                <h5 class="m-0">
                    <a href="#collapse3" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Modal Bootstrap
                    </a>
                </h5>
            </div>
            <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion">
                <div class="card-body">
                <button onclick="$('#modal_example').modal('show')" class="btn btn-primary">show modal</button>
                    {{
                        Bootstrap::modal('modal_example',[
                            "title"   => 'Download data tindakan',
                            "size"    => "modal-sm",
                            "body"    => [
                                            "content"   => "ini content"
                                        ]
                        ])
                    }}
                    <pre>@verbatim
                    {{
                        Bootstrap::modal('modal_example',[
                            "title"   => 'Download data tindakan',
                            "size"    => "modal-sm",
                            "body"    => [
                                            "content"   => "ini content"
                                        ]
                        ])
                    }}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="heading4">
                <h5 class="m-0">
                    <a href="#collapse4" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Input Multirows
                    </a>
                </h5>
            </div>
            <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
                <div class="card-body">
                    <pre>use \fidpro\builder\Multirow;</pre>
                    {!!
                        Multirow::build([
                            "id"    => "multi-item",
                            "title" => "List Aset",
                            "data"  => [
                                "STOCK"  => [
                                    "type"  => "group",
                                    "group" => [
                                        [
                                            "name"  => "hiden",
                                            "type"  => "hidden"
                                        ],
                                        [
                                            "name"  => "inputan",
                                            "type"  => "input"
                                        ]
                                    ]
                                ],
                                "JUMLAH"  => [
                                    "name"  => "qty",
                                    "type"  => "input"
                                ]
                            ]
                        ])
                    !!}
                    <pre>@verbatim
                    {!!
                        Multirow::build([
                            "id"    => "multi-item",
                            "title" => "List Aset",
                            "data"  => [
                                "STOCK"  => [
                                    "type"  => "group",
                                    "group" => [
                                        [
                                            "name"  => "hiden",
                                            "type"  => "hidden"
                                        ],
                                        [
                                            "name"  => "inputan",
                                            "type"  => "input"
                                        ]
                                    ]
                                ],
                                "JUMLAH"  => [
                                    "name"  => "qty",
                                    "type"  => "input"
                                ]
                            ]
                        ])
                    !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection