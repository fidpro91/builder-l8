<?php
namespace App\Libraries;

use App\Models\Receiving_detail;
use App\Models\Stock_process;
use App\Models\Stock_unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 
class Servant
{
    public static function connect_simrs($method,$url,$data = array()){
        $ch = curl_init(); 
        $base_url = "localhost:88/ehos/api/api_internal/";
        $url = $base_url.$url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            echo 'Request Error:' . curl_error($ch);
        }
        curl_close($ch);
        return ($result);
    }

    public static function get_menu($id=0)
    {
        $datam =    DB::table('ms_menu as m')->where([
                        "menu_parent_id"	=> $id,
                        "menu_status"	    => 't',
                        // "ga.group_id"       => Auth::user()->group_id
                    ])->orderBy('menu_code');
                    // ->join("group_access as ga","ga.menu_id","=","m.menu_id");
        $menux='';
        foreach ($datam->get() as $key => $value) {
            if ( DB::table('ms_menu')->where(["menu_parent_id"	=> $value->menu_id])->count() > 0) {
                $menux .= "<li><a href=\"#\">
                                <i class=\"".(!empty($value->menu_icon)?$value->menu_icon:'fa fa-circle-o')."\"></i> <span>".strtoupper($value->menu_name)."</span> <span class=\"menu-arrow\"></span>
                                </a>
                                <ul class=\"nav-second-level\" aria-expanded=\"false\">";
                $menux .= self::get_menu($value->menu_id);
                $menux .= "</ul></li>";
            }else{
                $fun="";
                if (!empty($value->menu_function)) {
                    $fun = "onclick=\"$value->menu_function(this,event)\"";
                }
                $menux .= "<li><a $fun href=\"".URL("$value->menu_url")."\">
                        <i class=\"".(!empty($value->menu_icon)?$value->menu_icon:'')."\"></i><span>".strtoupper($value->menu_name)."</span>
                        </a></li>";
            }
        }
        return $menux;
    }

    public static function generate_code_transaksi($data){
		$query = DB::table($data['table'])->selectRaw("LPAD((max(COALESCE(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(".$data['column'].",'".$data['delimiterFirst']."',".$data['limit']."),'".$data['delimiterLast']."','".$data['number']."') AS UNSIGNED),0))+1),5,'0') AS nomax")->get()->first();
		if (empty($query->nomax)) {
            $query->nomax = 1;
        }
        return str_replace('NOMOR', $query->nomax, $data['text']);
	}

    public static function insert_log_stock($fk_id,$unit_id,$data)
    {
        $update_stok = true;
        if (isset($data["update_stock"])) {
            $update_stok = $data["update_stock"];
        }
        try {
            if (!isset($data['item'])) {
                $item = DB::table($data['table'])->find($fk_id)->latest()->first();
            }else{
                $item = $data['item'];
            }
            //last stock
            $stock = Stock_process::where([
                        "item_id"   => $item->item_id,
                        "model"     => $item->model,
                        "merek"           => $item->merek,
                        "satuan_unit"     => $item->satuan_unit,
                        "unit_id"   => $unit_id
                    ])->latest()->first();
            
            $input = [
                'item_id'          => $item->item_id,
                'model'            => $item->model,
                'merek'            => $item->merek,
                'satuan_unit'      => $item->satuan_unit,
                'unit_id'          => $unit_id,
                'trans_num'        => $data['header']['trans_num'],
                'trans_type'       => $data['header']['trans_type'],
                'fk_id'            => $fk_id,
                'stock_before'     => ($stock->stock_after??0),
                'debet'            => '0',
                'kredit'           => '0',
                'stock_after'      => '0',
                'item_price'       => $item->{$data['field']['price']},
                'total_price'      => ($item->{$data['field']['price']}*$item->{$data['field']['qty']}),
                'description'      => $data['description'],
                'type_act'         => $data['type_act'],
                'created_by'       => Auth::user()->id
            ];
            if ($data['log'] == 'debet') {
                $input['debet'] = $item->{$data['field']['qty']};
                $input['stock_after'] = $item->{$data['field']['qty']}+($stock->stock_after??0);
            }else{
                $input['kredit'] = $item->{$data['field']['qty']};
                $input['stock_after'] = ($stock->stock_after??0)-$item->{$data['field']['qty']};
            }
            Stock_process::create($input);
            if ($update_stok == true) {
                Self::update_stok($item,$unit_id,$input);
            }
            $resp = [
                'code'      => '200',
                'message'   => 'ok'
            ];
        } catch (\Exception $e) {
            $resp = [
                'code'      => '202',
                'message'   => $e->getMessage()
            ];
        }
        return $resp;
    }

    public static function insert_log_mutation($fk_id,$unit_id,$data)
    {
        $update_stok = true;
        if (isset($data["update_stock"])) {
            $update_stok = $data["update_stock"];
        }
        try {
            if (!isset($data['item'])) {
                $item = DB::table($data['table'])->find($fk_id)->latest()->first();
            }else{
                $item = $data['item'];
            }
            //last stock
            $stock = Stock_process::where([
                        "item_id"   => $item->item_id,
                        "model"     => $item->model,
                        "merek"           => $item->merek,
                        "satuan_unit"     => $item->satuan_unit,
                        "unit_id"   => $unit_id
                    ])->latest()->first();
            
            $input = [
                'item_id'          => $item->item_id,
                'model'            => $item->model,
                'merek'            => $item->merek,
                'satuan_unit'      => $item->satuan_unit,
                'unit_id'          => $unit_id,
                'trans_num'        => $data['header']['trans_num'],
                'trans_type'       => $data['header']['trans_type'],
                'fk_id'            => $fk_id,
                'stock_before'     => ($stock->stock_after??0),
                'debet'            => '0',
                'kredit'           => '0',
                'stock_after'      => '0',
                'item_price'       => $item->price,
                'total_price'      => ($item->price*$data['qty']),
                'description'      => $data['description'],
                'type_act'         => $data['type_act'],
                'created_by'       => Auth::user()->id
            ];
            if ($data['log'] == 'debet') {
                $input['debet'] = $data['qty'];
                $input['stock_after'] = $data['qty']+($stock->stock_after??0);
            }else{
                $input['kredit'] = $data['qty'];
                $input['stock_after'] = ($stock->stock_after??0)-$data['qty'];
            }

            Stock_process::create($input);
            if ($update_stok == true) {
                Self::update_stok($item,$unit_id,$input);
            }
            $resp = [
                'code'      => '200',
                'message'   => 'ok'
            ];
        } catch (\Exception $e) {
            $resp = [
                'code'      => '202',
                'message'   => $e->getMessage()
            ];
        }
        return $resp;
    }

    public function update_stok($item,$unit_id,$stockNew)
    {
        //cek data stock
        $stock = Stock_unit::where([
                    "item_id"   => $item->item_id,
                    "model"     => $item->model,
                    "merek"     => $item->merek,
                    "satuan_unit"     => $item->satuan_unit,
                    "unit_id"   => $unit_id
                ])->first();
        if ($stock) {
            $stock->update([
                "stock_summary"  => $stockNew['stock_after']
            ]);
        }else{
            //get last penerimaan
            $receiving = Receiving_detail::from('receiving_detail as rd')
                         ->join("receiving as r","rd.rec_id","=","r.rec_id")
                         ->where([
                            "rd.item_id"   => $item->item_id,
                            "rd.merek"     => $item->merek,
                            "rd.model"     => $item->model       
                         ])
                         ->orderBy('rd.recdet_id', 'DESC')->first();
            $stockUnit = [
                'item_id'           =>  $item->item_id,
                'unit_id'           =>  $unit_id,
                'merek'             =>  $item->merek,
                'model'             =>  $item->model,
                'satuan_unit'       =>  $item->satuan_unit,
                'nomor_seri'        =>  $item->nomor_seri,
                'nilai_residu'      =>  $item->nilai_residu,
                'stock_summary'     =>  $stockNew['stock_after'],
                'total_price'       =>  $stockNew['total_price'],
                'item_condition'    =>  1,
                'tahun_pengadaan'   =>  $receiving->tahun_pengadaan,
                'is_hibah'          =>  'f',
                'pengadaan_id'      =>  $receiving->recdet_id,
                'tanggal'           =>  date('Y-m-d'),
                'price'             =>  $stockNew['item_price'],
                'hibah_from'        =>  '',
                'masa_manfaat'      =>  $receiving->masa_manfaat,
                'satuan_manfaat'    =>  $receiving->satuan_manfaat
            ];
            Stock_unit::create($stockUnit);
        }
    }
}