<?php

namespace App\Http\Controllers\Obligaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Administrativo\COS_CODIGO_SEDES;
use App\Models\Administrativo\PER_PERIODOS;
use App\Models\Administrativo\TER_TERCERO;
use App\Models\Obligaciones\OSP_OBLIGACIONES_X_SEDE_X_PERIODO;
use App\Models\Obligaciones\TEO_TERCERO_X_OBLIGACION;
use DB;
use Auth;

class obligacionesController extends Controller
{
    public function index(){    
        $listaCategorias = self::listaCategorias();        
        $listaPeriodos = self::listaPeriodos();
        $fechames = date('Ym');
        
        return view('obligaciones.nuevaObligacion', compact('listaCategorias','listaPeriodos','fechames'));
    }
    
    private function listaCategorias(){
        return $lista = DB::table('Sedes.CAS_CATEGORIA_OBLIGACION')
                        ->get();        
        /* return response()->json($lista,200);    */
    }
  
    private function listaPeriodos(){
        return $lista = PER_PERIODOS::select('PER_ID', 'PER_DESCRIPCION', 'PER_CODIGO')
                        ->where('PER_CERRADO',0)
                        ->orderBy('PER_ID','ASC')
                        ->get();
    }

    Public function consultarSedes($parametro){
        //dd($parametro);
        $resplist = array();
        $resplist = COS_CODIGO_SEDES::select('COS_ID',
                        DB::raw("CONCAT(COS_CODIGO,' - ',COS_NOMBRE_SEDE) AS SEDE") )
                    ->join('Sedes.ESS_ESTADO_SEDE','ESS_ID','=','COS_ESS_ID')
                    ->where('ESS_NOMBRE','ACTIVA')
                    ->where('COS_CODIGO','like','%'.strval($parametro).'%')
                    ->orWhere('COS_NOMBRE_SEDE','like','%'.strval($parametro).'%')
                    ->get();
        return response()->json($resplist);                     
    }

    public function consultarObligaciones($sede = null, $periodo = null, $categoria = null){          
        $resplist = array();
        $obligaciones = [];
        $resplist = DB::table('Sedes.OBL_OBLIGACION')
                    ->select('OBL_ID','OBL_NOMBRE','OBL_RELACIONAR_TERCERO', 'OBL_TIPO_DATO','OSP_ID', 'OSP_VALOR_INT', 
                            'OSP_VALOR_FLOAT', 'OSP_VALOR_TEXTO', 'OSP_FECHA_PAGO', 'OSP_REFERENCIA', 'TER_ID',
                            DB::raw(" IIF(OBL_RELACIONAR_TERCERO = 0 , '', CONCAT(TER_NUMERO_DOCUMENTO,'  ',TER_NOMBRE)) AS TERCERO" ))
                    ->leftjoin('Sedes.OSP_OBLIGACIONES_X_SEDE_X_PERIODO',function($join) use($sede,$periodo){
                            $join->on('OSP_OBL_ID','=','OBL_ID');
                            $join->on('OSP_COS_ID','=',DB::raw($sede));
                            $join->on('OSP_PER_ID','=',DB::raw($periodo));
                    })                             
                    ->leftjoin('Sedes.TEO_TERCERO_X_OBLIGACION','TEO_OSP_ID','=','OSP_ID')  
                    ->leftjoin('Sedes.TER_TERCERO','TER_ID','=','TEO_TER_ID')  
                    ->where('OBL_CAS_ID',$categoria)
                    ->orderBy('OBL_NOMBRE','ASC')
                    ->get();        
                
        foreach ($resplist as $value) {
            $VALOR = '';
            switch ($value->OBL_TIPO_DATO) {
                case 'entero':
                    $VALOR = $value->OSP_VALOR_INT;
                break;
                case 'flotante':
                    $VALOR = $value->OSP_VALOR_FLOAT;
                break;
                case 'texto':
                    $VALOR = $value->OSP_VALOR_TEXTO;
                break;                
            }
            $chcek = 0;
            if($value->OSP_ID != ''){
                $chcek = 1;
            }
            $obligaciones[] = [
                'OBL_ID' => $value->OBL_ID,
                'OBL_NOMBRE' => $value->OBL_NOMBRE,
                'OBL_RELACIONAR_TERCERO' => $value->OBL_RELACIONAR_TERCERO,
                'OBL_TIPO_DATO' => $value->OBL_TIPO_DATO,
                'OBL_CHECK' => $chcek,
                'OSP_ID' => $value->OSP_ID,
                'VALOR' => $VALOR,
                'OSP_FECHA_PAGO' => $value->OSP_FECHA_PAGO,                
                'OSP_REFERENCIA' => $value->OSP_REFERENCIA,                
                'TERCEROCARG' => [
                    'TER_ID' => $value->TER_ID,
                    'TERCERO' => $value->TERCERO,
                ],                
            ];
        }        
        return response()->json($obligaciones);        
    }

    public function consultarTerceros($parametro){        
        $resplist = array();
        $resplist = TER_TERCERO::select('TER_ID',
                    DB::raw(" CONCAT(TER_NUMERO_DOCUMENTO,' - ',TER_NOMBRE) AS TERCERO"))
                    ->where('TER_ACTIVO',1)
                    ->where('TER_NUMERO_DOCUMENTO','like','%'.strval($parametro).'%')
                    ->orWhere('TER_NOMBRE','like','%'.strval($parametro).'%')
                    ->get();
        return response()->json($resplist);       
    }

    public function saveObligaciones(Request $request){
        //dd($request->all());
        //print_r($request);
        //var_dump($request->all());
        //echo $request->obliga_5;
        $controla = 0;
        $obligaciones = json_decode($request->obligaciones);
        $sede = json_decode($request->sede);
       
        //dd($obligaciones);
        //$obligaciones = json_decode($request->obligaciones,true);                        
        foreach ($obligaciones as $value) {            
            //$llave = "obliga_".$value->OBL_ID;
            $valor = "valor_".$value->OBL_ID;
            $fecha = "fecha_".$value->OBL_ID;
            $refe = "ref_".$value->OBL_ID;
            if( $value->OBL_CHECK == 1){
                $controla = 1;
                $campovalor = '';
                switch ($value->OBL_TIPO_DATO) {
                    case 'entero':
                        $campovalor = 'OSP_VALOR_INT';
                    break;
                    case 'flotante':
                        $campovalor = 'OSP_VALOR_FLOAT';
                    break;
                    case 'texto':
                        $campovalor = 'OSP_VALOR_TEXTO';
                    break;                
                }
                //se verifica si existe obligacion
                $obligacion = OSP_OBLIGACIONES_X_SEDE_X_PERIODO::firstOrCreate([
                    'OSP_OBL_ID' => $value->OBL_ID,
                    'OSP_COS_ID' => $sede->COS_ID,
                    'OSP_PER_ID' => $request->periodo,
                ],
                [
                    $campovalor => $request->$valor,
                    'OSP_FECHA_PAGO' => $request->$fecha,
                    'OSP_REFERENCIA' => $request->$refe,
                    'OSP_USER_CREA' => Auth::user()->USU_USERNAME, 
                    'OSP_FECHA_CREA' => date('Y-m-d H:m:s'),
                ]); 
                $id = $obligacion->OSP_ID;                                                                 
                // actualizar datos
                if($obligacion){            
                    $obligacion->$campovalor = $request->$valor;
                    $obligacion->OSP_FECHA_PAGO = $request->$fecha;
                    $obligacion->OSP_REFERENCIA = $request->$refe;
                    $obligacion->OSP_USER_MODIFICA = Auth::user()->USU_USERNAME;
                    $obligacion->OSP_FECHA_MODIFICA = date('Y-m-d H:m:s');
                    $obligacion->save();
                }  
                $id = $obligacion->OSP_ID;
                //tercero  
                if($value->OBL_RELACIONAR_TERCERO == 1){              
                    $tercero = TEO_TERCERO_X_OBLIGACION::firstOrCreate([
                        'TEO_OSP_ID' => $id,
                    ],
                    [
                        'TEO_TER_ID' => $value->TERCEROCARG->TER_ID,
                        'TEO_USU_CREA' => Auth::user()->USU_USERNAME,
                    ]);
                    if($tercero){     
                        $tercero->TEO_TER_ID = $value->TERCEROCARG->TER_ID;
                        $tercero->TEO_USU_MODIFICA = Auth::user()->USU_USERNAME;
                        $tercero->save();
                    }
                }    
            }              
        } 
        if($controla == 0){
            return 0;
        }else{
            return 1;
        }
    }

















   

}
