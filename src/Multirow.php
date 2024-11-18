<?php
 
namespace fidpro\builder;

use Illuminate\Support\Str;

/**
 * Basic Calculator.
 * 
 */
class Multirow extends Create
{
    protected static $i=0;
    
    public static function build($data,$val=null)
    {
        $th = "<th>NO</th>";
        foreach (array_keys($data['data']) as $key => $value) {
            $th .= "<th>$value</th>\n";
        }
        $th .= "<th>#</th>";
        $row="<tr><td>".(self::$i+1)."</td>";
        foreach ($data['data'] as $key => $value) {
            $input=self::get_input_type($data['id'],$value);
            $row .= "
                        <td>$input</td>
                    ";
        }
        $row .= "<td>
            <a href=\"javascript:void(0)\" class=\"remove-tr-".$data['id']." btn btn-xs btn-danger\"><i class=\"fas fa-trash\"></i></a>
        </td></tr>";

        if ($val) {
            $rowFirst = self::set_data_row($data,$val);
        }else{
            $rowFirst = Str::replace('num_row','0',$row);
        }
        $table = self::render_table($data['id'],$th,$rowFirst);
        $html = self::render_html($data['id'],$data['title'],$table);
        $scripts = self::render_scripts($data['id'],$row);
        return $html."\n".$scripts;
    }

    private static function set_data_row($data,$val)
    {
        $row="";
        foreach ($val as $x => $v) {
            $row .="<tr><td>".($x+1)."</td>";
            foreach ($data['data'] as $key => $value) {
                if ($value['type'] == 'group') {
                    $input = "";
                    foreach ($value['group'] as $y => $vl) {
                        $vl['value'] = $v->{$vl['name']};
                        $input .= self::get_input_type($data['id'],$vl);
                    }
                }else{
                    $value['value'] = $v->{$value['name']};
                    $input=self::get_input_type($data['id'],$value);
                }
                $input = Str::replace('num_row',$x,$input);
                $row .= "
                            <td>$input</td>
                        ";
            }
            $row .= "<td>
                <a href=\"javascript:void(0)\" class=\"remove-tr-".$data['id']." btn btn-xs btn-danger\"><i class=\"fas fa-trash\"></i></a>
            </td></tr>";
        }
        self::$i = $x;
        return $row;
    }

    private static function render_scripts($id,$row)
    {
        $i=self::$i;
        $scripts = '
        <script>
            var row'.$id.'='.$i.';
            $(document).ready(()=>{
                $("#btn-'.$id.'").click(()=>{
                    row'.$id.'++;
                    var inputan = \''.preg_replace('/\s+/', ' ', trim(addslashes($row))).'\';
                    inputan = inputan.replace(/num_row/g, row'.$id.');
                    $("#'.$id.' > tbody").append(inputan);
                    $("#'.$id.' > tbody tr:last").find("td:first").text(row'.$id.'+1);
                });
            });
            $(document).on("click", ".remove-tr-'.$id.'", function(){
                $(this).parents("tr").remove();
                row'.$id.' = row'.$id.'-1;
            });
        </script>
        ';

        return $scripts;
    }

    private static function render_table($id,$th,$row)
    {
        $table = '<table id="'.$id.'" class="table">
                    <thead>
                        <tr>
                            '.$th.'
                        </tr>
                    </thead>
                    <tbody>
                        '.$row.'
                    </tbody>
                </table>';
        return $table;
    }

    private static function render_html($id,$title,$table)
    {
        
        $div = '<div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title">'.$title.'</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="float-right">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-'.$id.'"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                '.$table.'
            </div>
        </div>';

        return $div;
    }

    private static function get_input_type($id,$param)
    {
        if (isset($param['name'])) {
            $name=$id.'[num_row]['.$param['name'].']';
        }
        $val = "";
        if (isset($param['value'])) {
            $val = $param['value'];
        }
        switch ($param['type']) {
            case 'input':
                $attr = [
                    "name"          => $name,
                    "class"         => "form-control ".$param['name'],
                    "placeholder"   => "",
                    "value"         => $val
                ];
                if (isset($param["attributes"])) {
                    $attr = array_merge($attr,$param["attributes"]);
                }
                $input = self::input($param['name'],$attr)->render();
                break;
            case 'select':
                $param['option']["selected"] = $val;
                $param['option']['extra'] = [
                    'name'      => $name,
                    'class'     => "form-control ".$param['name']
                ];
                $input = self::dropDown($param['name'],$param['option'])->render();
                break;
            case 'group':
                $input="";
                foreach ($param['group'] as $key => $value) {
                    $input .= self::get_input_type($id,$value);
                }
                break;
            case 'hidden':
                $input = '<input type="hidden" value="'.$val.'" name="'.$name.'" class="'.$param['name'].'" />';
                break;
            default:
                # code...
                break;
        }
        $input=Str::replace('id=','id-'.$id.'=',$input);
        return $input;
    }
}