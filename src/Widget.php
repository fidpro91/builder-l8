<?php
 
namespace fidpro\builder;

use Illuminate\Support\Str;

/**
 * Basic Calculator.
 * 
 */
class Widget extends Create
{
    public static $form = '';
    public static $formId = '';
    public static $formLabel = '';

    public static function _init(array $var){
        $css="";
        $scripts="";
        foreach ($var as $key => $value) {
            switch ($value) {
                case 'select2':
                    echo '<link href="'.asset('assets/themes/assets/libs/select2/css/select2.min.css').'" rel="stylesheet" />
                    <script src="'.asset('assets/themes/assets/libs/select2/js/select2.full.min.js').'"></script> ';
                    break;
                case 'switcher':
                    echo '<link href="'.asset('assets/js/bootstrap-switch/bootstrap-switch.min.css').'" rel="stylesheet" />
                    <script src="'.asset('assets/js/bootstrap-switch/bootstrap-switch.min.js').'"></script>
                    <style>
                        .switch-container {
                            display: inline-block !important;
                            vertical-align: middle !important;
                            margin-left: 10px; /* Sesuaikan margin sesuai kebutuhan */
                        }
                    </style>';
                    break;
                case 'datepicker':
                    echo '<link href="'.asset('assets/themes/assets/libs/bootstrap-datepicker/bootstrap-datepicker.css').'" rel="stylesheet" type="text/css" />
                    <script src="'.asset('assets/themes/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js').'"></script> ';
                    break;
                
                case 'daterangepicker':
                    echo '<link href="'.asset('assets/themes/assets/libs/bootstrap-daterangepicker/daterangepicker.css').'" rel="stylesheet" type="text/css"/>
                    <script src="'.asset('assets/themes/assets/libs/moment/moment.js').'"></script> 
                    <script src="'.asset('assets/themes/assets/libs/bootstrap-daterangepicker/daterangepicker.js').'"></script> ';
                    break;
                case 'inputmask':
                    echo '
                    <script src="'.asset('assets/themes/assets/libs/input-mask/dist/jquery.inputmask.js').'"></script> 
                    <script src="'.asset('assets/themes/assets/libs/input-mask/dist/bindings/inputmask.binding.js').'"></script>'."\n".
                    '<script>
                    Inputmask.extendAliases({
                        "IDR": {
                            alias: "decimal",
                            allowMinus: false,
                            radixPoint: ".",
                            autoGroup: true,
                            groupSeparator: ",",
                            groupSize: 3,
                            autoUnmask: true,
                            removeMaskOnSubmit:true
                        }
                        });
                    </script>';
                    break;
                default:
                    # code...
                    break;
            }
        }
    }

    public static function select2($name,$attr=null,$selec2=null)
    {
        $form = self::dropDown($name,$attr)->render();
        $form .= "<script>\n
            $('#".$name."').select2();\n
        </script>";
        return self::_set_output($form,$name);
    }

    private function _set_output ($form,$name){
        static::$form = $form;
        static::$formId = $name;
        static::$formLabel = Str::ucfirst(Str::replace('_', ' ', $name));
        return new static;
    }

    public static function render($type = null,$label = null) {
        $render =  static::$form;
        if($type == 'group') {
            $render = '<div class="form-group">
                <label for="' . static::$formId . '">' .($label??static::$formLabel). '</label>';
            $render .= static::$form."</div>";

        }
        return $render;
    }

    public static function datePicker($name,$picker=null,$attr=null)
    {
        $form     = "<div class='input-group'>";
        $form     .= self::input($name,$attr)->render();
        $form     .= '<div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="ti-calendar"></span>
                                    </span>
                                </div>
                            </div>';
        $defaulPicker = [
            "autoclose"   => "true"
        ];
        if(is_array($picker)){
            $defaulPicker = array_merge($defaulPicker,$picker);
        }
        $form     .= '<script>
                        $("#'.$name.'").datepicker('.json_encode($defaulPicker).');
                    </script>';
        return self::_set_output($form,$name);
    }

    public static function daterangePicker($name,$attr=null)
    {
        $form   = "<div class='input-group'>";
        $form   .= self::input($name,$attr)->render();
        $form   .= '<div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="ti-calendar"></span>
                                    </span>
                                </div>
                            </div>';
        $form   .= '<script>
                    $("#'.$name.'").daterangepicker({
                        showDropdowns: true
                    });
                </script>';
        
        return self::_set_output($form,$name);
    }

    public static function inputMask($name,$attr)
    {
        $prop      = $attr['prop']??null;
        $form      = self::input($name,$prop)->render();
        $form     .= "<script>\n";
        if (is_array($attr['mask'])) {
            $form .= "$('#" . $name . "').inputmask(\"" . $attr['mask'][0] . '",' . json_encode($attr['mask'][1]) . ")\n";
        } else {
            $form.= "$('#" . $name . "').inputmask(\"" . json_encode($attr['mask']) . "\")\n";
        }
        $form     .= '</script>'."\n";
        return self::_set_output($form,$name);
    }

    public static function switcher($name,$attr)
    {
        $form   = '<div class="switch-container">';
        $form   .= self::input($name,[
            "type"  => "checkbox"
        ])->render();
        $form .= "</div>";
        $form     .= "<script>\n";
        $json = json_encode($attr["option"], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        $json = preg_replace('/"([^"]+)"\s*:\s*/', '$1:', $json);
        $form     .= "$('#" . $name . "').bootstrapSwitch(" . $json . ")\n";
        if (isset($attr["onchange"])) {
            $form .= "
                $('#$name').on('switchChange.bootstrapSwitch',".$attr["onchange"].");
            ";
        }
        $form     .= "</script>\n";

        return self::_set_output($form,$name);
    }
}