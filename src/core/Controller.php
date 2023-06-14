<?php

namespace App\Http\Controllers;

use App\Libraries\Servant;
use App\Models\Company_profile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function themes ($view,$data=null,$page) {
        if (is_object($page)) {
            $page = (new \ReflectionClass($page))->getShortName();
            $data['breadcumb']  = str_replace('controller','',strtolower($page));
        }else{
            $data['breadcumb']  = 'home';
        }
        $data['pageName']   = ucwords(str_replace('_',' ',$page));
        $menu='';
        if(!Cache::has('menuCache')){
            $menu = Servant::get_menu();
            Cache::add('menuCache', $menu, 60);
        }
        $menu = Cache::store('file')->get('menuCache');
        $data['menu']       = $menu;
        $data['profil']       = $this->get_profil();
        return view($view,$data);
    }

    public function get_profil()
    {
        return Company_profile::latest()->first();
    }
}
