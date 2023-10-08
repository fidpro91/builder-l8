@extends('templates.documentation.layouts')
@section('content')
<?php
use \fidpro\builder\Create;
use \fidpro\builder\Widget;
Widget::_init(["select2","daterangepicker","datepicker","inputmask","switcher","ckeditor"]);
?>
<div class="col-md-12">
    <p>
        Untuk menggunakan form widget gunakan function dibawah ini
        <pre>use \fidpro\builder\Widget;</pre>
    </p>
    <div id="accordion">
        <div class="card mb-0">
            <div class="card-header" id="headingOne">
                <h5 class="m-0">
                    <a href="#collapseOne" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Widget select2
                    </a>
                </h5>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <pre>Widget::_init(["select2"]);</pre>
                    {!!
                        Widget::select2("pegawai",[
                            "data" => [
                                "t"     => ["Aktif"],
                                "f"     => ["Non Aktif"]
                            ]
                        ])->render("group","pegawai");
                    !!}
                    <pre>@verbatim
                    {!!
                        Widget::select2("pegawai",[
                            "data" => [
                                "t"     => ["Aktif"],
                                "f"     => ["Non Aktif"]
                            ]
                        ])->render("group","pegawai");
                    !!}
                    @endverbatim
                    </pre>
                    <p>Jika option berisi dari model</p>
                    <pre>@verbatim
                    {!! 
                        Widget::select2("pegawai",[
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
            <div class="card-header" id="headingTwo">
                <h5 class="m-0">
                    <a href="#collapseTwo" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Widget Datepicker
                    </a>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    <pre>Widget::_init(["datepicker"]);</pre>
                    {!! 
                        Widget::datepicker("tanggal_cair",[
                            "format"		=>"dd-mm-yyyy",
                            "autoclose"		=>true
                        ],[
                            "readonly"      => true,
                            "required"      => "true"
                        ])->render("group")
                    !!}
                    <pre>@verbatim
                    {!! 
                        Widget::datepicker("tanggal_cair",[
                            "format"		=>"dd-mm-yyyy",
                            "autoclose"		=>true
                        ],[
                            "readonly"      => true,
                            "required"      => "true"
                        ])->render("group")
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
                        Widget Daterange Picker
                    </a>
                </h5>
            </div>
            <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion">
                <div class="card-body">
                    <pre>Widget::_init(["daterangepicker"]);</pre>
                    {!! Widget::daterangePicker("periode_tindakan_kroscek")->render("group","Periode Tarikan Data") !!}
                    <pre>@verbatim
                    {!! Widget::daterangePicker("periode_tindakan_kroscek")->render("group","Periode Tarikan Data") !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="heading4">
                <h5 class="m-0">
                    <a href="#collapse4" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        Widget inputmask
                    </a>
                </h5>
            </div>
            <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
                <div class="card-body">
                    <pre>Widget::_init(["inputmask"]);</pre>
                    {!!
                        Widget::inputMask("gaji_pokok",[
                            "prop"      => [
                                "required"  => true,
                            ],
                            "mask"      => [
                                "IDR",[
                                    "rightAlign"    => false,
                                ]
                            ]
                        ])->render("group");
                    !!}
                    <pre>@verbatim
                    {!!
                        Widget::inputMask("gaji_pokok",[
                            "prop"      => [
                                "value"     => $employee->gaji_pokok,
                                "required"  => true,
                            ],
                            "mask"      => [
                                "IDR",[
                                    "rightAlign"    => false,
                                ]
                            ]
                        ])->render("group");
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
                        BOOSTRAP SWITCHER
                    </a>
                </h5>
            </div>
            <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                <div class="card-body">
                    <pre>Widget::_init(["switcher"]);</pre>
                    {!!
                        Widget::switcher("tes",[
                            'option'    => [
                                'onText'      =>  'Ya',
                                'offText'     => 'Tidak',
                                'onColor'     => 'success',
                                'offColor'    => 'danger',
                                'state'       => false
                            ],
                            'onchange'  => '
                                function(event, state) {
                                    if (state) {
                                        console.log("Switch diaktifkan (Ya)");
                                    } else {
                                        console.log("Switch dinonaktifkan (Tidak)");
                                    }
                                }
                            '
                        ])->render('group')
                    !!}
                    <pre>@verbatim
                    {!!
                        Widget::switcher("tes",[
                            'option'    => [
                                'onText'      =>  'Ya',
                                'offText'     => 'Tidak',
                                'onColor'     => 'success',
                                'offColor'    => 'danger',
                                'state'       => true
                            ],
                            'onchange'  => '
                                function(event, state) {
                                    if (state) {
                                        console.log("Switch diaktifkan (Ya)");
                                    } else {
                                        console.log("Switch dinonaktifkan (Tidak)");
                                    }
                                }
                            '
                        ])->render('group')
                    !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="heading6">
                <h5 class="m-0">
                    <a href="#collapse6" class="text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        CK EDITOR
                    </a>
                </h5>
            </div>
            <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordion">
                <div class="card-body">
                    <pre>Widget::_init(["ckeditor"]);</pre>
                    {!! 
                        Widget::ckeditor("alamat_domisili")->render("group"); 
                    !!}
                    <pre>@verbatim
                    {!! 
                        Widget::ckeditor("alamat_domisili")->render("group"); 
                    !!}
                    @endverbatim
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection