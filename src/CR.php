<?php

namespace fidpro\builder;

use Illuminate\Support\Facades\Cache;
/**
 * FORMULA GENERATOR
 * 
 */

class CR extends Bootstrap
{
    public static $properties='',$element;

    public static function init($data)
    {
        self::$properties=$data;
        return new static;
    }

    public static function resource($type,$data)
    {
        if ($type == 'data') {
            $dataSelect = $data;
        }elseif ($type == "model") {
            $key= $data['model'].'-'.base64_encode(json_encode($data['filter'] ?? []));
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
        }

        $col = [];
        if (!empty($data["column"])) {
            $col = $data["column"];
        }
        $dataDropdown = self::build($dataSelect,$col);
        $checkbox = implode("\n", $dataDropdown);

        self::$element = $checkbox;
        return new static;
    }

    public static function build($data,$col=null)
    {
        $id = self::$properties['id'];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $dataDropdown[] = '<div class="'.self::$properties['class'].'">
                    <input type="'.self::$properties['type'].'" id="'.$id.$key.'" name="'.$id."[".$key."]".'" class="custom-control-input" value="'.$key.'">
                    <label for="'.$id.$key.'">'.current($value).'</label>
                </div>';
            } else {
                if (!empty($col)) {
                    $elVal  = $value->{$col[0]};
                    $elName = $value->{$col[1]};
                }else{
                    $elVal  = $elName  = $value;
                }
                $dataDropdown[] = '<div class="'.self::$properties['class'].'">
                    <input type="'.self::$properties['type'].'" id="'.$id.$key.'" name="'.$id."[".$key."]".'" class="custom-control-input" value="'. $elVal.'">
                    <label for="'.$id.$key.'">'.$elName.'</label>
                </div>';
            }
        }
        return $dataDropdown;
    }

    public static function buildOne($data)
    {
        $checked = (self::$properties["checked"]??false);
        self::$element = '<div class="'.self::$properties['class'].'">
            <input type="'.self::$properties['type'].'" '.$checked.'  id="'.self::$properties['id'].'" name="'.self::$properties['name'].'" value="'.$data["value"].'">
            <label for="'.self::$properties["id"].'">'.$data["label"].'</label>
        </div>';

        return new static;
    }

    public static function render($label = null) {
        $render =  static::$element;
        return $render;
    }

}