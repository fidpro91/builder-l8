@extends('templates.documentation.layouts')
@section('content')
<?php
use \fidpro\builder\Create;
use \fidpro\builder\Bootstrap;
?>
<div class="col-md-12">
    <p>
        Untuk menggunakan form inputan basic gunakan use dibawah ini
        <pre>use \fidpro\builder\Create;</pre>
    </p>
    <div id="accordion">
        <div class="card mb-0">
            <div class="card-header" id="headingOne">
                <h5 class="m-0">
                    <a href="#collapseOne" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Form Input Text
                    </a>
                </h5>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    {!! Create::input("px_norm",[
                        "required"  => "true"
                    ])->render("group");
                    !!}
                    <pre>@verbatim
                    {!! Create::input("px_norm",[
                        "required"  => "true"
                    ])->render("group");
                    !!}
                    @endverbatim
                    </pre>
                    {!! Create::input("px_norm",[
                        "required"  => "true"
                    ])->render();
                    !!}
                    <pre>@verbatim
                    {!! Create::input("px_norm",[
                        "required"  => "true"
                    ])->render();
                    !!}
                    @endverbatim
                    </pre>
                    {!! 
                        Create::input("emp_name",[
                            "required"      => "true"
                        ])
                        ->withIcon([
                            "prepend"       => '<i class="far fa-user"></i>',
                            "append"        => '<i class="far fa-user"></i>'
                        ])
                        ->render("horizontal"); 
                    !!}
                    <pre>@verbatim
                        {!! 
                            Create::input("emp_name",[
                                "value"         => $employee->emp_name,
                                "required"      => "true"
                            ])
                            ->withIcon([
                                "prepend"       => '<i class="far fa-user"></i>',
                                "append"        => '<i class="far fa-user"></i>'
                            ])
                            ->render("horizontal"); 
                        !!}
                    @endverbatim
                    </pre>
                    {!! 
                        Create::input("emp_name",[
                            "value"         => $employee->emp_name,
                            "required"      => "true"
                        ])
                        ->withButton([
                            "append"       => '<button class="btn btn-success" type="button"><i class="ti-search"></i></button>',
                        ])
                        ->render("horizontal"); 
                    !!}
                    <pre>@verbatim
                        {!! 
                            Create::input("emp_name",[
                                "value"         => $employee->emp_name,
                                "required"      => "true"
                            ])
                            ->withButton([
                                "append"       => '<button class="btn btn-success" type="button"><i class="ti-search"></i></button>',
                            ])
                            ->render("horizontal"); 
                        !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="headingTwo">
                <h5 class="m-0">
                    <a href="#collapseTwo" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Form Input Dropdown
                    </a>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    {!!
                        Create::dropDown("pegawai",[
                            "data" => [
                                "t"     => ["Aktif"],
                                "f"     => ["Non Aktif"]
                            ]
                        ])->render("group","pegawai");
                    !!}
                    <pre>@verbatim
                    Create::dropDown("pegawai",[
                        "data" => [
                            "t"     => ["Aktif"],
                            "f"     => ["Non Aktif"]
                        ]
                    ])->render("group","pegawai");
                    @endverbatim
                    </pre>
                    <p>Jika option berisi dari model</p>
                    <pre>@verbatim
                    {!! 
                        Create::dropDown("pegawai",[
                            "data" => [
                                "model"     => "Models_builder\Employee",
                                "custom"    => "tes_data",
                                "column"    => ["emp_id","emp_name"]
                            ]
                        ])->render("group","pegawai");
                    !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="heading3">
                <h5 class="m-0">
                    <a href="#collapse3" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Form Input Radio
                    </a>
                </h5>
            </div>
            <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion">
                <div class="card-body">
                    {!!
                        Create::radio("pegawai",[
                            "data" => [
                                "t"     => ["Aktif"],
                                "f"     => ["Non Aktif"]
                            ]
                        ])->render("group","pegawai");
                    !!}
                    <pre>@verbatim
                    Create::radio("pegawai",[
                        "data" => [
                            "t"     => ["Aktif"],
                            "f"     => ["Non Aktif"]
                        ]
                    ])->render("group","pegawai");
                    @endverbatim
                    </pre>
                    <p>Jika option berisi dari model</p>
                    <pre>@verbatim
                    {!! 
                        Create::radio("pegawai",[
                            "data" => [
                                "model"     => "Models_builder\Employee",
                                "custom"    => "tes_data",
                                "column"    => ["emp_id","emp_name"]
                            ]
                        ])->render("group","pegawai");
                    !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="heading4">
                <h5 class="m-0">
                    <a href="#collapse4" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Form Input Chekbox
                    </a>
                </h5>
            </div>
            <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
                <div class="card-body">
                    {!!
                        Create::checkbox("pegawai",[
                            "data" => [
                                "t"     => ["Aktif"],
                                "f"     => ["Non Aktif"]
                            ]
                        ])->render("group","pegawai");
                    !!}
                    <pre>@verbatim
                    Create::checkbox("pegawai",[
                        "data" => [
                            "t"     => ["Aktif"],
                            "f"     => ["Non Aktif"]
                        ]
                    ])->render("group","pegawai");
                    @endverbatim
                    </pre>
                    <p>Jika option berisi dari model</p>
                    <pre>@verbatim
                    {!! 
                        Create::checkbox("pegawai",[
                            "data" => [
                                "model"     => "Models_builder\Employee",
                                "custom"    => "tes_data",
                                "column"    => ["emp_id","emp_name"]
                            ]
                        ])->render("group","pegawai");
                    !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="heading5">
                <h5 class="m-0">
                    <a href="#collapse5" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Form Text Area
                    </a>
                </h5>
            </div>
            <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                <div class="card-body">
                    {!!
                        Create::text("keterangan",[
                            "option" => [
                                "rows"  => 5
                            ]
                        ])->render("group");
                    !!}
                    <pre>@verbatim
                    {!!
                        Create::text("keterangan",[
                            "option" => [
                                "rows"  => 5
                            ]
                        ])->render("group");
                    !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection