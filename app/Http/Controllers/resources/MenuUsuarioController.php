<?php

namespace App\Http\Controllers\resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

use App\Models\Administrador\LMR_LOGIN_MENU_ROL;
use App\Models\Administrador\LMP_LOGIN_MENU_PADRE;


class MenuUsuarioController extends Controller
{
    
    public function busquedaMenuUsuarioXIdRol(Request $request){

        $rolMenus = [];
        $menusId = [];
        $menusPadre = [];
        $menuCompleto = [];
        $menusHijos = [];

        $rolMenus = LMR_LOGIN_MENU_ROL::select(
            'LMR_ID',	
            'LMR_LMH_ID',	
            'LMR_LOR_ID',	
            'LMR_ESTADO',            
            'LMH_ID',	
            'LMH_NOMBRE',	
            'LMH_ESTADO',	
            'LMH_ICONO',	
            'LMH_DESCRIPCION',	
            'LMH_LMP_ID',	
            'LMH_RUTA',
        )
        ->join('SEGURIDAD.LMH_LOGIN_MENU_HIJO as b',function($join){
            $join->on('LMR_LMH_ID','=','LMH_ID');
            $join->on('LMH_ESTADO','=',DB::raw(1));
        })
        ->join('SEGURIDAD.USU_LOGIN as c','LMR_LOR_ID','=','USU_LOR_ID')
        ->where('USU_LOR_ID',$request->rolUsuario)
        ->where('USU_USERNAME',$request->usernameUsuario)
        ->where('LMR_ESTADO',1)
        ->orderBy('LMH_ORDEN','ASC')
        ->get();

        foreach ($rolMenus as $key => $value) {
            $menusId[] = $value->LMH_LMP_ID;
        }

        $menusPadre = LMP_LOGIN_MENU_PADRE::whereIn('LMP_ID',array_unique($menusId))
        ->where('LMP_SAA_ID',env('APLICACION_ID'))
        ->orderBy('LMP_ORDEN_MENU','ASC')
        ->get();


        foreach($menusPadre as $menuPadre){

            $menusHijos = [];

            foreach($rolMenus as $rolMenu){
                if($rolMenu->LMH_LMP_ID == $menuPadre->LMP_ID){
                    $menusHijos[] = $rolMenu;
                }
            }

            $menuCompleto[] = [
                'idMenuPadre' => $menuPadre->LMP_ID,
                'nombreMenuPadre' => $menuPadre->LMP_NOMBRE,
                'iconoMenuPadre' => $menuPadre->LMP_ICONO,
                'menusHijos' => $menusHijos
            ];

        }

        return $menuCompleto;

    }

}
