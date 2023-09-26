@extends('templates.documentation.layouts')
@section('content')
<?php

use fidpro\builder\Widget;

Widget::_init(["select2"]);
?>
<div class="card border-0 shadow rounded">
    {!! Form::open(['url' => 'builder/build_element','id'=>'form_creator']) !!}
    <div class="card-header">
        FORM CREATOR MODEL VIEW CONTROLLER (MVC)
    </div>
    <div class="card-body">
        {!!
            Create::dropDown("dbms",[
                "data" => [
                    "1"     => ["Mysql"],
                    "2"     => ["PostgreSql"]
                ]
            ])->render("group","Database Manajemen Sistem");
        !!}
        {!!
            Widget::select2("schema_name",[
                "data" => []
            ])->render("group","Nama Table");
        !!}
        {!!
            Widget::select2("table_name",[
                "data" => []
            ])->render("group","Nama Table");
        !!}
        {!!
            Create::checkbox("form_element",[
                "data" => [
                    "1"     => ["Controller"],
                    "2"     => ["Model"],
                    "3"     => ["View"],
                ]
            ])->render("group");
        !!}
    </div>
    <div class="card-footer text-center">
        {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
        {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
        <a href="javascript:void(0)" class="btn btn-primary btn-hasil" target="blank"><i class="fa fa-eye"></i> Hasil Generate</a>
    </div>
    {!!Form::close()!!}
</div>

<script>
    $(document).ready(()=>{
        $("#dbms").on("change",function(){
            $("#schema_name").empty();
            $.get("{{url('builder/get_schema')}}",function(resp){
                var data = resp.response;
                for (i in data) {
                    var option = new Option(data[i]);
                    $('#schema_name').append(option);
                }
            },'json')
        })

        $("#schema_name").on("change",function(){
            $("#table_name").empty();
            $.post("{{url('builder/get_table')}}",{
                dbms    : $("#dbms").val(),
                schema  : $("#schema_name").val()
            },function(resp){
                var data = resp.response;
                for (i in data) {
                    var option = new Option(data[i]);
                    $('#table_name').append(option);
                }
            },'json')
        })

        $('#form_creator').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function() {
            Swal.fire({
                title: 'Simpan Data?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        'data': $('#form_creator').serialize(),
                        'dataType': 'json',
                        'type'   : 'post',
                        'url'    : '{{url("builder/build_element")}}',
                        'success': function(data) {
                            if (data.code == 200) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $(".btn-hasil").attr("href",data.response.url);
                                });
                            }else{
                                Swal.fire("Oopss...!!", data.message, "error");
                            }
                        }
                    });
                }
            })
            return false;
        });
    })
</script>
@endsection