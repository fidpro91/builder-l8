<?php
 
namespace fidpro\builder;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * FORMULA GENERATOR
 * 
 */
class Create
{
    /**
     *
     * @param array $data
     * @return integer
     */
    public static $form = '';
    public static $formId = '';
    public static $formLabel = '';
    protected static $className = "form-control";

    public static function input($name,$attr=null)
    {
        $defaultAttr = [
            "class"         => self::$className,
            "type"          => "text",
            "name"          => "$name", 
            "placeholder"   => Str::ucfirst(Str::replace('_', ' ', $name))
        ];
        if(is_array($attr)){
            $defaultAttr = array_merge($defaultAttr,$attr);
        }
        $properti = self::array_to_attr($defaultAttr);
        $form = "<input id=\"$name\" $properti></input>";
        return self::_set_output($form,$name);
    }

    public static function upload($name,$attr=null)
    {
        $defaultAttr = [
            "class"         => self::$className."-file",
            "type"          => "file",
            "placeholder"   => Str::ucfirst(Str::replace('_', ' ', $name))
        ];
        if(is_array($attr)){
            $defaultAttr = array_merge($defaultAttr,$attr);
        }
        $properti = self::array_to_attr($defaultAttr);
        $form = "<input id=\"$name\" name = \"$name\" $properti></input>";
        return self::_set_output($form,$name);
    }

    private function _set_output ($form,$name){
        static::$form = $form;
        static::$formId = $name;
        static::$formLabel = Str::ucfirst(Str::replace('_', ' ', $name));
        return new static;
    }

    public static function withIcon ($data){
        
        $prepend=$append="";
        if (!empty($data["prepend"])) {
            $prepend = '<div class="input-group-prepend"><span class="input-group-text">'.$data["prepend"].'</span></div>';
        }
        if (!empty($data["append"])) {
            $append = '<div class="input-group-append"><span class="input-group-text">'.$data["append"].'</span></div>';
        }
        static::$form = "<div class=\"input-group mb-3\">
        $prepend \n ".static::$form." \n $append
        </div>";

        return new static;
    }

    public static function withButton ($data){
        
        $prepend=$append="";
        if (!empty($data["prepend"])) {
            $prepend = '<div class="input-group-prepend">'.$data["prepend"].'</div>';
        }
        if (!empty($data["append"])) {
            $append = '<div class="input-group-append">'.$data["append"].'</div>';
        }
        static::$form = "<div class=\"input-group mb-3\">
        $prepend \n ".static::$form." \n $append
        </div>";

        return new static;
    }

    public static function render($type = null,$label = null) {
        $render =  static::$form;
        if($type == 'group' || $type == 'vertical') {
            $render = '<div class="form-group">
                <label for="' . static::$formId . '">' .($label??static::$formLabel). '</label>';
            $render .= static::$form."</div>";
        }elseif ($type == 'horizontal') {
            $render = '<div class="form-group mt-4 row">
                <label for="' . static::$formId . '" class="col-md-3 col-form-label">' .($label??static::$formLabel). '</label>';
                
            $render .= '<div class="col-md-9">'.static::$form."
                        </div>
                        </div>";
        }
        return $render;
    }

    public static function text($name,$attr=null)
    {
        $defaultAttr = [
            "class"     => "form-control",
            "id"        => $name,
            "name"      => $name,
        ] ;
        if (isset($attr['option'])) {
            $defaultAttr=array_merge($defaultAttr,$attr['option']);
        }
        $form = "<textarea ".self::array_to_attr($defaultAttr).">".($attr['value']??"")."</textarea>";
        return self::_set_output($form,$name);
    }

    public static function formGroup($param)
    {
        $input="";
        foreach ($param['group'] as $key => $value) {
            $input .= self::set_input($key,$value);
        }
        $input = '<div class="input-group">'.$input.'</div>';
        return self::_set_output($input,'tes');
    }

    private static function set_input($type,$param)
    {
        if (isset($param["id"])) {
            $id = $name = $param["id"];
        }
        // $name = $param["name"];
        $val = "";
        if (isset($param['value'])) {
            $val = $param['value'];
        }
        switch ($type) {
            case 'input':
                $attr = [
                    "name"          => $name,
                    "class"         => "form-control ".$id,
                    "value"         => $val
                ];
                if (isset($param["attributes"])) {
                    $attr = array_merge($attr,$param["attributes"]);
                }
                $input = self::input($id,$attr)->render();
                break;
            case 'select':
                $param['option']["selected"] = $val;
                $param['option']['extra'] = [
                    'name'  => $name,
                ];
                $input = self::dropDown($id,$param['option'])->render();
                break;
            case 'hidden':
                $input = '<input type="hidden" value="'.$val.'" name="'.$name.'" class="'.$id.'" />';
                break;
            default:
                $input = $param["value"];
                break;
        }
        return $input;
    }

    public static function dropDown($id, $attr)
    {
        if (isset($attr['data']['model'])) {
            $data = $attr['data'];
            $key= $attr['data']['model'].'-'.base64_encode(json_encode($data['filter'] ?? []));
            $dataSelect = Cache::rememberForever($key, function () use ($data) {
                // Jika data tidak ada di cache, ambil dari model dan simpan ke cache
                $model = "\\App\\Models\\" . $data['model'];
                $filter = $data['filter'] ?? [];
                if (isset($data['custom'])) {
                    $model = new $model;
                    $dataSelect = $model->{$data['custom']}($filter);
                } else {
                    if (isset($data['filter'])) {
                        $dataSelect = $model::where($data['filter'])
                        ->get();
                    } else {
                        $dataSelect = $model::all();
                    }
                }
                return $dataSelect;
            });
            $dataDropdown = [];
            foreach ($dataSelect as $key => $value) {
                if (isset($data['column'])) {
                    if (count($data["column"])>1) {
                        $val = $value->{$data['column'][0]};
                        $text = $value->{$data['column'][1]};
                    }else{
                        $val = $text = $value->{$data['column'][0]};
                    }
                } else {
                    $val = (is_numeric($key)?$value:$key);
                    $text = $value;
                }
                $selected = "";
                if (!empty($attr["selected"]) && ($val == $attr["selected"])) {
                    $selected = "selected";
                }
                $dataDropdown[] = "<option $selected value=\"$val\">" . $text . "</option>";
            }
        }else{
            $data = $attr['data'];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $val = $key;
                    $text = current($value);
                } else {
                    $val = $text = $value;
                }
                $selected = "";
                
                if (!empty($attr["selected"]) && ($val == $attr["selected"])) {
                    $selected = "selected";
                }
                
                $dataDropdown[] = "<option $selected value=\"$val\">" . $text . "</option>";
            }
        }
        $defaultAttr = [
            "class"     => self::$className,
            "id"        => $id,
            "name"      => $id,
        ];
        if (isset($attr['extra'])) {
            $defaultAttr = array_merge($defaultAttr, $attr['extra']);
        }
        
        $select = "<select " . self::array_to_attr($defaultAttr) . ">\n";
        if (isset($attr["nullable"])) {
            $select .= "<option value=\"\">---</option>\n";
        }
        if (!empty($dataDropdown)) {
            $select .= implode("\n", $dataDropdown);
        }
        $select .= "</select>";
        return self::_set_output($select,$id);
    }

    public static function radio($id, $attr)
    {
        $defaultValue=($attr["value"]??null);
        if (isset($attr['data']['model'])) {
            $data = $attr['data'];
            $key= $attr['data']['model'].'-'.base64_encode(json_encode($data['filter'] ?? []));
            $dataSelect = Cache::rememberForever($key, function () use ($data) {
                // Jika data tidak ada di cache, ambil dari model dan simpan ke cache
                $model = "\\App\\Models\\" . $data['model'];
                $filter = $data['filter'] ?? [];
                if (isset($data['custom'])) {
                    $model = new $model;
                    $dataSelect = $model->{$data['custom']}($filter);
                } else {
                    if (isset($data['filter'])) {
                        $dataSelect = $model::where($data['filter'])
                        ->get();
                    } else {
                        $dataSelect = $model::all();
                    }
                }
                return $dataSelect;
            });
            $dataDropdown = [];
            foreach ($dataSelect as $key => $value) {
                $checked = false;
                if (isset($data['column'])) {
                    if ($defaultValue == $value->{$data['column'][0]}) {
                        $checked = 'checked';
                    }
                    $dataDropdown[] = '<div class="custom-control custom-radio" style="display: inline-block;">
                        <input type="radio" id="'.$id.$key.'" name="'.$id.'" class="custom-control-input" value="'. $value->{$data['column'][0]} .'" '.$checked.'>
                        <label class="custom-control-label" for="'.$id.$key.'">'.$value->{$data['column'][1]}.'</label>
                    </div>';
                } else {
                    if ($defaultValue == $value) {
                        $checked = 'checked';
                    }
                    $dataDropdown[] = '<div class="custom-control custom-radio" style="display: inline-block;">
                        <input type="radio" id="'.$id.$key.'" name="'.$id.'" class="custom-control-input" value="'. $value.'" '.$checked.'>
                        <label class="custom-control-label" for="'.$id.$key.'">'.$value.'</label>
                    </div>';
                }
            }
        }else{
            $data = $attr['data'];
            foreach ($data as $key => $value) {
                $checked = false;
                if (is_array($value)) {
                    if ($defaultValue == $key) {
                        $checked = 'checked';
                    }
                    $dataDropdown[] = '<div class="custom-control custom-radio" style="display: inline-block;">
                        <input type="radio" id="'.$id.$key.'" name="'.$id.'" class="custom-control-input" value="'.$key.'" '.$checked.'>
                        <label class="custom-control-label" for="'.$id.$key.'">'.current($value).'</label>
                    </div>';
                } else {
                    if ($defaultValue == $value) {
                        $checked = 'checked';
                    }
                    $dataDropdown[] = '<div class="custom-control custom-radio" style="display: inline-block;">
                        <input type="radio" id="'.$id.$key.'" name="'.$id.'" class="custom-control-input" value="'. $value.'" '.$checked.'>
                        <label class="custom-control-label" for="'.$id.$key.'">'.$value.'</label>
                    </div>';
                }
            }
        }
        $radio = "<br>".implode("\n", $dataDropdown);
        return self::_set_output($radio,$id);
    }
    
    public static function checkbox($id, $attr)
    {
        if (isset($attr['data']['model'])) {
            $data = $attr['data'];
            $key= $attr['data']['model'].'-'.base64_encode(json_encode($data['filter'] ?? []));
            $dataSelect = Cache::rememberForever($key, function () use ($data) {
                // Jika data tidak ada di cache, ambil dari model dan simpan ke cache
                $model = "\\App\\Models\\" . $data['model'];
                $filter = $data['filter'] ?? [];
                if (isset($data['custom'])) {
                    $model = new $model;
                    $dataSelect = $model->{$data['custom']}($filter);
                } else {
                    if (isset($data['filter'])) {
                        $dataSelect = $model::where($data['filter'])
                        ->get();
                    } else {
                        $dataSelect = $model::all();
                    }
                }
                return $dataSelect;
            });
            $dataDropdown = [];
            foreach ($dataSelect as $key => $value) {
                if (isset($data['column'])) {
                    $dataDropdown[] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" id="'.$id.$key.'" name="'.$id."[".$key."]".'" class="custom-control-input" value="'. $value->{$data['column'][0]} .'">
                        <label class="custom-control-label" for="'.$id.$key.'">'.$value->{$data['column'][1]}.'</label>
                    </div>';
                } else {
                    $dataDropdown[] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" id="'.$id.$key.'" name="'.$id."[".$key."]".'" class="custom-control-input" value="'. $value.'">
                        <label class="custom-control-label" for="'.$id.$key.'">'.$value.'</label>
                    </div>';
                }
            }
        }else{
            $data = $attr['data'];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $dataDropdown[] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" id="'.$id.$key.'" name="'.$id."[".$key."]".'" class="custom-control-input" value="'.$key.'">
                        <label class="custom-control-label" for="'.$id.$key.'">'.current($value).'</label>
                    </div>';
                } else {
                    $dataDropdown[] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" id="'.$id.$key.'" name="'.$id."[".$key."]".'" class="custom-control-input" value="'. $value.'">
                        <label class="custom-control-label" for="'.$id.$key.'">'.$value.'</label>
                    </div>';
                }
            }
        }
        $checkbox = implode("\n", $dataDropdown);
        return self::_set_output($checkbox,$id);
    }

    private static function array_to_attr($attr) {
        $ret = '';
        foreach ($attr as $key => $value) {
            $ret .= ' ' . htmlspecialchars($key, ENT_QUOTES) . '="' . htmlspecialchars($value) . '"';
        }
        return trim($ret);
    }

    public static function action($title,$attr) {
        $html = '<a href="javascript:void(0)" '.self::array_to_attr($attr).'>'.$title.'</a>';

        return $html;
    }

    public static function link($title,$attr) {
        $html = '<a '.self::array_to_attr($attr).'>'.$title.'</a>';

        return $html;
    }
}