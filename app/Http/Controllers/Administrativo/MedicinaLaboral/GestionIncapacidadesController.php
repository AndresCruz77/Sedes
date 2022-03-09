<?php

namespace App\Http\Controllers\Administrativo\medicinalaboral;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Models\Administrativo\MedicinaLaboral\IBCR_INC_BASE_CRHB;
use App\Models\Administrativo\MedicinaLaboral\IBP_INC_BASE_PROCESO;
use App\Models\Administrativo\MedicinaLaboral\ILC_INC_LISTA_CLIENTE;

class GestionIncapacidadesController extends Controller
{
    public function consultarIncapacidadesUsuario(){
        $consulta = IBCR_INC_BASE_CRHB::
        join('MedicinaLaboral.IBP_INC_BASE_PROCESO','IBCR_IPB_ID','=','IBP_ID')
        ->join('MedicinaLaboral.ILC_INC_LISTA_CLIENTE','IBP_ILC_ID','=','ILC_ID')
        ->where('IBCR_USU_ID_RESPONSABLE',Auth::user()->USU_ID)
        ->get();

        return response()->json($consulta, 200);
    }
}
