<?php

namespace fidpro\builder;

use Illuminate\Support\Str;

/**
 * FORMULA GENERATOR
 * 
 */

class UiDatatable extends Bootstrap
{
    public static $scripts='',$table = '';
    public static $tfoot = '';
    public static $filter = '';
    public static $thead = '';
    public static $tbody = '';
    public static $column = '';
    public static $settings = [
        "processing" => "true", 
        "serverSide" => "true"
    ];
    public static $name = '', $url = '', $raw = [];

    public static function init($data)
    {
        self::$table = '
        <style>
            tfoot {
                display: table-header-group;
            }
        </style>'."\n".'
        <table id="' . $data['name'] . '" ' . self::array_to_attr($data["attr"]) . '>';
        self::$name = $data['name'];
        self::$url  = $data['url'];
        return new static;
    }

    private static function __set_header($row) {
        self::$thead = "<thead>\n<tr>";
        foreach ($row as $key => $val) {
            if (is_array($val)) {
                self::$thead .= '<th>' . Str::upper(Str::replace('_',' ',$key)) . '</th>' . "\n";
            }else{
                self::$thead .= '<th>' . Str::upper(Str::replace('_',' ',$val)) . '</th>' . "\n";
            }
        }
        self::$thead .= "</tr>\n</thead>";

        return new static;
    }

    function column($data) {

        self::$raw = $data;
        $this->__set_header($data);
        self::$tbody = "<tbody>\n</tbody>";

        foreach ($data as $key => $val) {
            if (is_array($val)) {
                self::$column .= "{";
                foreach ($val as $r => $v) {
                    if ($r == "settings") {
                        $settingC="";
                        foreach ($v as $rr => $vv) {
                            $settingC .= $rr." : "."$vv,\n";
                        }
                        self::$column .= rtrim($settingC,",\n");
                    }else{
                        self::$column .= "'$r'"." : "."'$v',\n";
                    }
                }
                self::$column .= "}";
            }else{
                self::$column .=  '{
                    "data" : "'.$val.'",
                    "name" : "'.$val.'"
                }';
            }
            self::$column .=",\n";
        }
        self::$column = rtrim(self::$column,",\n");
        return new static;
    }

    public static function filter($filter)
    {
        foreach ($filter as $key => $value) {
            self::$filter .= "d.".$key." = ".$value.";\n";
        }
        return new static;
    }

    public static function filterColumn($col)
    {
        self::$tfoot = "<tfoot>\n<tr>";
        
        foreach (self::$raw as $key => $value) {
            $name = $value;
            if (is_array($name)) {
                $name = $name['data'];
            }
            if (isset($col[$name])) {
                self::$tfoot .= "<td>".$col[$name]."</td>";
                self::$filter .= "d.$name = $('#$name').val();\n";
            }else {
                self::$tfoot .= "<td></td>";
            }
        }
        self::$tfoot .= "</tr></tfoot>";
        return new static;
    }
    
    public static function extensions($ext)
    {
        self::$settings = array_merge(self::$settings,$ext);
        return new static;
    }

    public static function render()
    {
        $render = self::$table."\n".self::$thead."\n".self::$tfoot."\n".self::$tbody."\n</table>";
        self::_set_script();
        $render .= "\n".self::$scripts;

        echo $render;
    }

    public static function _set_script()
    {
        $variableTable = "tb_".str_replace('-','_',self::$name);
        $varSetting="";
        foreach (self::$settings as $key => $setting) {
            if (is_array($setting)) {
                $settinger = "{\n";
                foreach ($setting as $set => $res) {
                    if (is_array($res)) {
                        $res = implode(',',$res);
                        $res = "[".$res."]";
                    }
                    $settinger .= $set." : ".$res.",\n";
                }
                $settinger = rtrim($settinger,",\n")."\n}";
            }else{
                $settinger = $setting.",\n";
            }
            $varSetting .= $key .":". $settinger;
        }
        self::$settings = rtrim($varSetting,",\n");
        self::$scripts = '
        <script>
        var '.$variableTable.';
        $(document).ready(function() {
            var filterCol = $(\'table#'.self::$name.' tfoot\').find("input, select");
            '.$variableTable.'  = $("#'.self::$name.'").DataTable({
                "ajax" : {
                        "url":"'.url(self::$url).'",
                        "data" : function(d){
                            '.self::$filter.'
                        }
                },
                "columns" :[
                    '.self::$column.'
                ],
                '.self::$settings.'
            });

            $(\'table#'.self::$name.' tfoot\').find("input, select").on("change",function(){
                '.$variableTable.'.draw();
            });
        })
        </script>
        ';

        return new static;
    }
}