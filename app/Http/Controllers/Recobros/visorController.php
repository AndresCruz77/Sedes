<?php

namespace App\Http\Controllers\Recobros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Recobros\MRS_MAESTRA_RECOBROS;
use App\Models\Recobros\TDR_TIPO_DOCUMENTAL;
use App\Models\Recobros\ARS_AUTORIZACIONES_RECOBROS;
use App\Models\Recobros\SRS_SERVICIO_RECOBROS;
use App\Models\Recobros\FRS_FACTURA_RECOBROS;
use App\Models\Recobros\VW_AUTORIZACION;
use App\Models\Recobros\TCU_TECNOLOGIA_CUMS;
use App\Models\Recobros\ERS_ESTADOS_RECOBROS;
use App\Models\Recobros\EMR_ESTADO_MAESTRA_RECOBROS;
use App\Models\Recobros\IRS_INCONSISTENCIA_RECOBROS;
use DB;
use Auth;

class visorController extends Controller
{
    public function index()
    {    
        //$recobros = MRS_MAESTRA_RECOBROS::all();  
        $recobrosAsignados = MRS_MAESTRA_RECOBROS::select()
            ->join('RECOBROS.ASIGNACION_RECOBROS_USUARIO','ARU_MAESTRA_RECOBROS_ID','=','MRS_ID')         
            ->where('ARU_USUARIO_ASIGNADO',\Auth::user()->USU_USERNAME)
            ->whereIn('MRS_ESTADO_ID',['6','7'])            
            ->get();
                        
        $recobros = $this->consultarRecobrosDocumentos($recobrosAsignados);
        $recobros = self::orderMultiDimensionalArray($recobros, 'MRS_ID', '' );        

        $pendientes = MRS_MAESTRA_RECOBROS::select()
                        ->join('RECOBROS.ASIGNACION_RECOBROS_USUARIO','ARU_MAESTRA_RECOBROS_ID','=','MRS_ID')         
                        ->where('ARU_USUARIO_ASIGNADO',\Auth::user()->USU_USERNAME)
                        ->whereIn('MRS_ESTADO_ID',['6','7'])            
                        ->count();
                     
        $tramitados = MRS_MAESTRA_RECOBROS::select() 
                        ->where('MRS_USER_MODIFICA',\Auth::user()->USU_USERNAME)
                        ->whereIn('MRS_ESTADO_ID',['8','9','15'])  
                        ->where('MRS_FECHA_MODIFICACION','like','%'.strval(date('Y-m-d')).'%') 
                        ->count();  

        $utilidad = [
            'PENDIENTES' => $pendientes,
            'TRAMITADOS' => $tramitados
        ]; 


        return view('recobros.visor', compact('recobros','utilidad'));
    }

    private function consultarRecobrosDocumentos($listaRecobros){

        // dd($listaRecobros)->all();
        $documento = '';
        $recobros = [];
        
        foreach( $listaRecobros as $value){

            $autorizacionCmp = [
                'ARS_ID' => '' ,
                'ARS_MAESTRA_RECOBROS_ID' => '' ,
                'ARS_NRO_AUTORIZACION' => '' ,
                'ARS_NUMERO_ENTREGA' => '' ,
                'ARS_ID_DIRECCIONAMIENTO' => '', 
                'ARS_POSTFECHADA' => '' ,
                'ARS_FECHA_AUTORIZACION' => '' ,
                'ARS_NOMBRE_IPS_REMITE' => '' ,
                'ARS_NIT_IPS_REMITE' => '' ,
                'ARS_CANTIDAD' => '' ,
                'ARS_CODIGO_TECONOLOGIA' => '' ,
                'ARS_NUM_DOC_AFILIADO' => '' ,
                'ARS_TIPO_DOC_AFILIADO' => '' ,
                'ARS_NUMERO_PRESCRIPCION' => '' ,
                'ARS_FECHA_POSIBLE_ENTREGA' => '' ,
                'ARS_ESTADO_ID' => '' ,
                'ARS_FECHA_CREACION' => '' ,
                'ARS_FECHA_MODIFICACION' => '' ,
                'ARS_USER_CREA' => '' ,
                'ARS_USER_MODIFICA' => '' 
            ];

            $servicioCmp = [
                'SRS_ID' => '',
                'SRS_MAESTRA_RECOBROS_ID' => '',
                'SRS_CODIGO_TECNOLOGIA' => '',
                'SRS_NOMBRE_TECNOLOGIA' => '',
                'SRS_FECHA_PRESTACION' => '',
                'SRS_NRO_AUTORIZACION' => '',
                'SRS_CANTIDAD' => '',
                'SRS_VALOR_UNITARIO' => '',
                'SRS_VALOR_TOTAL' => '',
                'SRS_CUOTA_MODERADORA_COPAGO' => '',
                'SRS_TIPO_ID_USUARIO' => '',
                'SRS_NRO_ID_USUARIO' => '',
                'SRS_ESTADO_ID' => '',
                'SRS_FECHA_CREACION' => '',
                'SRS_FECHA_MODIFICACION' => '',
                'SRS_USER_CREA' => '',
                'SRS_USER_MODIFICA' => ''
            ];

            $facturaCmp = [
                'FRS_ID' => '',
                'FRS_MAESTRA_RECOBROS_ID' => '',
                'FRS_NRO_FACTURA' => '',
                'FRS_FECHA_FACTURA' => '',
                'FRS_NIT_IPS' => '',
                'FRS_NOMBRE_IPS' => '',
                'FRS_ESTADO_ID' => '',
                'FRS_FECHA_CREACION' => '',
                'FRS_FECHA_MODIFICACION' => '',
                'FRS_USER_CREA' => '',
                'FRS_USER_MODIFICA' => '',
            ];

            $estadoCmp = [
                'ERS_ID' => '',
                'ERS_NOMBRE' => '',
                'ERS_ESTADO' => '',
                'ERS_FECHA_CREACION' => '',
                'ERS_FECHA_MODIFICACION' => '',
                'ERS_USER_CREA' => '',
                'ERS_USER_MODIFICA' => '',
            ];    

            $inconsistenciaCmp = [
                'IRS_ID' => '',
                'IRS_MAESTRA_RECOBROS_ID' => '',
                'IRS_DOC_FACTURA' => '',
                'IRS_DOC_AUTORIZACION' => '',
                'IRS_DOC_ENTREGA' => '',
                'IRS_DOC_OTRO' => '',
                'IRS_OBSERVACION' => '',
                'IRS_ESTADO_ID' => '',
                'IRS_FECHA_CREACION' => '',
                'IRS_FECHA_MODIFICACION' => '',
                'IRS_USER_CREA',
                'IRS_USER_MODIFICA' => '',
            ];

            $documentos = TDR_TIPO_DOCUMENTAL::where('TDR_MAESTRA_RECOBROS_ID',$value->MRS_ID)->get();
            $autorizacion = ARS_AUTORIZACIONES_RECOBROS::where('ARS_MAESTRA_RECOBROS_ID',$value->MRS_ID)->get();
            $servicio = SRS_SERVICIO_RECOBROS::where('SRS_MAESTRA_RECOBROS_ID',$value->MRS_ID)->get();
            $factura =  FRS_FACTURA_RECOBROS::where('FRS_MAESTRA_RECOBROS_ID',$value->MRS_ID)->get();
            $inconsistencia = TDR_TIPO_DOCUMENTAL::select('TDR_ID','TDR_TIPO_DOC','IRS_ID','IRS_ESTADO_ID')
            ->leftJoin('recobros.IRS_INCONSISTENCIA_RECOBROS',function($join){
                $join->on('TDR_ID','=','IRS_TIPO_DOCUMENTAL_ID');
                $join->on('IRS_ESTADO_ID','=',DB::RAW(1));
            })
            ->where('TDR_MAESTRA_RECOBROS_ID',$value->MRS_ID)
            ->get();
            $estado =  ERS_ESTADOS_RECOBROS::where('ERS_ID',$value->MRS_ESTADO_ID)->get(); 
            //var_dump($estado);

            $recobros[] = [
                'MRS_ID' => $value->MRS_ID,
                'MRS_RADICADO_CMD' => $value->MRS_RADICADO_CMD,
                'MRS_DETALLE_CMD' => $value->MRS_DETALLE_CMD,
                'MRS_RADICADO_OPERADOR' => $value->MRS_RADICADO_OPERADOR,
                'MRS_NRO_FACTURA_UNIFICADO' => $value->MRS_NRO_FACTURA_UNIFICADO,
                'MRS_NIT_IPS' => $value->MRS_NIT_IPS,
                'MRS_PREFIJO_FACTURA' => $value->MRS_PREFIJO_FACTURA,
                'MRS_NRO_FACTURA' => $value->MRS_NRO_FACTURA,
                'MRS_VALOR_SUSCEPTIBLE' => $value->MRS_VALOR_SUSCEPTIBLE,
                'MRS_ESTADO_ID' => $value->MRS_ESTADO_ID,
                'MRS_OBSERVACION_INCONSISTENCIA' => $value->MRS_OBSERVACION_INCONSISTENCIA,
                'MRS_FECHA_CREACION' => $value->MRS_FECHA_CREACION,
                'MRS_FECHA_MODIFICACION' => $value->MRS_FECHA_MODIFICACION,
                'MRS_USER_CREA' => $value->MRS_USER_CREA,
                'MRS_USER_MODIFICA' => $value->MRS_USER_MODIFICA,
                'ARU_ID' => $value->ARU_ID,
                'ARU_MAESTRA_RECOBROS_ID' => $value->MRS_ID,
                'ARU_ESTADO_ID' => $value->ARU_ESTADO_ID,
                'ARU_FECHA_CREACION' => $value->ARU_FECHA_CREACION,
                'ARU_FECHA_MODIFICACION' => $value->ARU_FECHA_MODIFICACION,
                'ARU_USER_CREA' => $value->ARU_USER_CREA,
                'ARU_USER_MODIFICA' => $value->ARU_USER_MODIFICA,                
                'DOCUMENTOS' => $documentos,
                'AUTORIZACION' => (isset($autorizacion[0])) ? $autorizacion[0]: $autorizacionCmp,
                'SERVICIO' => (isset($servicio[0])) ? $servicio[0] : $servicioCmp,
                'FACTURA' => (isset($factura[0])) ? $factura[0] : $facturaCmp,
                'ESTADO' => (isset($estado[0])) ? $estado[0] : $estadoCmp, 
                'INCONSISTENCIAS' =>(isset($inconsistencia[0])) ? $inconsistencia : $inconsistenciaCmp, 
            ];

            
        } 

        return $recobros;
    }

  

    public function show($noFactura){

        $recobrosConsulta = MRS_MAESTRA_RECOBROS::select()           
           // ->whereIn('MRS_ESTADO_ID',['6','7','8'])           
            ->where('MRS_ID','like','%'.strval($noFactura).'%')
            ->get();

        return $this->consultarRecobrosDocumentos($recobrosConsulta);

    }

    public function busqAutorizacion (Request $request){
        //print_r($request);
        //dd($request->all());
        if(!$request->numauto){
            return response()->json([], 200);
        }
        $vIps = [];
        $vautorizacion = [];

        /* $vautorizacion = VW_AUTORIZACION::select()->limit(10)->get();  */
        //$vautorizacion = VW_AUTORIZACION::select()->where('rptIdAutorizacion',$request->numauto)->first();
        $vautorizacion = DB::table(DB::raw('[10.109.12.240\MEDIMAS].[MDMAS_INFORMES].[INF].[vwDetalleAutorizacionesFact]'))
                            ->select('rptNoEntrega','rptIdDireccionamiento','NitIPSRemite AS ipsNitIps','NombreIPSRemite AS ipsNombre',
                                        'FechaAutorizacion','rptCanToAEntregar','NumeroPrescripcion AS rptNoPrescripcion',
                                        'TipDocAfiliado AS rptTipoIDPaciente','NumDocAfiliado AS rptNoIDPaciente','rptCodSerTecAEntregar')
                            ->where('NumeroAutorizacion',$request->numauto)->first();
        return response()->json($vautorizacion,200);        
    }

    public function guardaRecobro (Request $request){
       
        $fechaAutorizacion = $request->AUTORIZACION['ARS_FECHA_AUTORIZACION'];
        if( strlen($request->AUTORIZACION['ARS_FECHA_AUTORIZACION']) > 10 ){
            $fechaAutorizacion = explode(" ",$request->AUTORIZACION['ARS_FECHA_AUTORIZACION']);    
            $fechaAutorizacion = trim($fechaAutorizacion[0]);
        }       
        //dd($request->all()); 
        //dd($request->AUTORIZACION);        
        $insFactura = array();
        $insServicio = array();
        $insAuto = array();
        
        //factura
        if($request->FACTURA['FRS_ID'] == '' && !empty($request->FACTURA['FRS_NRO_FACTURA']) ){
            //insert
            $insFactura = FRS_FACTURA_RECOBROS::create([
                'FRS_MAESTRA_RECOBROS_ID' => $request->ARU_MAESTRA_RECOBROS_ID,
                'FRS_NRO_FACTURA' => $request->FACTURA['FRS_NRO_FACTURA'],
                'FRS_FECHA_FACTURA' => $request->FACTURA['FRS_FECHA_FACTURA'],
                'FRS_NIT_IPS' => $request->MRS_NIT_IPS,
                'FRS_NOMBRE_IPS' => $request->FACTURA['FRS_NOMBRE_IPS'],
                'FRS_ESTADO_ID' => '1',            
                'FRS_USER_CREA' => Auth::user()->USU_USERNAME,            
            ]);
        }else{
            //update
           /*  $insFactura = DB::table('RECOBROS.FRS_FACTURA_RECOBROS')
            ->where('FRS_ID',$request->FACTURA['FRS_ID'])
            ->update([
                'FRS_MAESTRA_RECOBROS_ID' => $request->ARU_MAESTRA_RECOBROS_ID,
                'FRS_NRO_FACTURA' => $request->FACTURA['FRS_NRO_FACTURA'],
                'FRS_FECHA_FACTURA' => $request->FACTURA['FRS_FECHA_FACTURA'],
                'FRS_NIT_IPS' => $request->FACTURA['FRS_NIT_IPS'],
                'FRS_NOMBRE_IPS' => $request->FACTURA['FRS_NOMBRE_IPS'],
                'FRS_ESTADO_ID' => '1',            
                'FRS_USER_MODIFICA' => Auth::user()->USU_USERNAME,          
                'FRS_FECHA_MODIFICACION' => date('Y-m-d H:i:s'),          
            ]);*/
            $insFactura = FRS_FACTURA_RECOBROS::where('FRS_ID',$request->FACTURA['FRS_ID'])->first();
            if($insFactura){
                $insFactura->FRS_NRO_FACTURA = $request->FACTURA['FRS_NRO_FACTURA'];
                $insFactura->FRS_FECHA_FACTURA = $request->FACTURA['FRS_FECHA_FACTURA'];
                $insFactura->FRS_NIT_IPS = $request->FACTURA['FRS_NIT_IPS'];
                $insFactura->FRS_NOMBRE_IPS = $request->FACTURA['FRS_NOMBRE_IPS'];
                $insFactura->FRS_USER_MODIFICA = Auth::user()->USU_USERNAME;
                $insFactura->save();
            }    
        }
        //servicio
        if($request->SERVICIO['SRS_ID'] == '' && $request->SERVICIO['SRS_FECHA_PRESTACION'] != ''){ 
            //insert
            $insServicio = SRS_SERVICIO_RECOBROS::create([                
                'SRS_MAESTRA_RECOBROS_ID' => $request->ARU_MAESTRA_RECOBROS_ID,
                'SRS_CODIGO_TECNOLOGIA' => $request->TCU_CUMS_MINISTERIO,
                'SRS_NOMBRE_TECNOLOGIA' => $request->TCU_PRODUCTO,
                'SRS_FECHA_PRESTACION' => $request->SERVICIO['SRS_FECHA_PRESTACION'],
                'SRS_NRO_AUTORIZACION' => $request->SERVICIO['SRS_NRO_AUTORIZACION'],
                'SRS_CANTIDAD' => $request->SERVICIO['SRS_CANTIDAD'],
                'SRS_VALOR_UNITARIO' => $request->SERVICIO['SRS_VALOR_UNITARIO'],
                'SRS_VALOR_TOTAL' => $request->SERVICIO['SRS_VALOR_TOTAL'],
                'SRS_CUOTA_MODERADORA_COPAGO' => $request->SERVICIO['SRS_CUOTA_MODERADORA_COPAGO'],
                'SRS_TIPO_ID_USUARIO' => $request->SERVICIO['SRS_TIPO_ID_USUARIO'],
                'SRS_NRO_ID_USUARIO' => $request->SERVICIO['SRS_NRO_ID_USUARIO'], 
                'SRS_ESTADO_ID' => '1',                
                'SRS_USER_CREA' => Auth::user()->USU_USERNAME,
                
            ]);
        }else{
            //update
            $insServicio = SRS_SERVICIO_RECOBROS::where('SRS_ID',$request->SERVICIO['SRS_ID'])->first(); 
            if($insServicio){           
                $insServicio->SRS_CODIGO_TECNOLOGIA =  $request->TCU_CUMS_MINISTERIO;
                $insServicio->SRS_NOMBRE_TECNOLOGIA = $request->TCU_PRODUCTO;
                $insServicio->SRS_FECHA_PRESTACION = $request->SERVICIO['SRS_FECHA_PRESTACION'];
                $insServicio->SRS_NRO_AUTORIZACION = $request->SERVICIO['SRS_NRO_AUTORIZACION'];
                $insServicio->SRS_CANTIDAD = $request->SERVICIO['SRS_CANTIDAD'];
                $insServicio->SRS_VALOR_UNITARIO = $request->SERVICIO['SRS_VALOR_UNITARIO'];
                $insServicio->SRS_VALOR_TOTAL = $request->SERVICIO['SRS_VALOR_TOTAL'];
                $insServicio->SRS_CUOTA_MODERADORA_COPAGO = $request->SERVICIO['SRS_CUOTA_MODERADORA_COPAGO'];
                $insServicio->SRS_TIPO_ID_USUARIO = $request->SERVICIO['SRS_TIPO_ID_USUARIO'];
                $insServicio->SRS_NRO_ID_USUARIO = $request->SERVICIO['SRS_NRO_ID_USUARIO'];
                $insServicio->SRS_USER_MODIFICA = Auth::user()->USU_USERNAME;
                $insServicio->save();
            }    
        }



        //autorizacion
        if($request->AUTORIZACION['ARS_ID'] == '' && $request->SERVICIO['SRS_NRO_AUTORIZACION'] != ''){
            //insert 
            $insAuto = ARS_AUTORIZACIONES_RECOBROS::create([
                'ARS_MAESTRA_RECOBROS_ID' => $request->ARU_MAESTRA_RECOBROS_ID,
                'ARS_NRO_AUTORIZACION'  => $request->SERVICIO['SRS_NRO_AUTORIZACION'],
                'ARS_NUMERO_ENTREGA'  => $request->AUTORIZACION['ARS_NUMERO_ENTREGA'],
                'ARS_ID_DIRECCIONAMIENTO'  => $request->AUTORIZACION['ARS_ID_DIRECCIONAMIENTO'],
              /*   'ARS_POSTFECHADA'  => $request->AUTORIZACION['ARS_POSTFECHADA'], */
                'ARS_FECHA_AUTORIZACION'  => $fechaAutorizacion,
                'ARS_NOMBRE_IPS_REMITE'  => $request->AUTORIZACION['ARS_NOMBRE_IPS_REMITE'],
                'ARS_NIT_IPS_REMITE'  => $request->AUTORIZACION['ARS_NIT_IPS_REMITE'],
                'ARS_CANTIDAD'  => $request->AUTORIZACION['ARS_CANTIDAD'],
                'ARS_CODIGO_TECONOLOGIA'  => $request->AUTORIZACION['ARS_CODIGO_TECONOLOGIA'],
                'ARS_NUM_DOC_AFILIADO'  => $request->AUTORIZACION['ARS_NUM_DOC_AFILIADO'],
                'ARS_TIPO_DOC_AFILIADO'  => $request->AUTORIZACION['ARS_TIPO_DOC_AFILIADO'],
                'ARS_NUMERO_PRESCRIPCION'  => $request->AUTORIZACION['ARS_NUMERO_PRESCRIPCION'],
             /*    'ARS_FECHA_POSIBLE_ENTREGA'  => $request->AUTORIZACION['ARS_FECHA_POSIBLE_ENTREGA'], */
                'ARS_ESTADO_ID'  => '1',                
                'ARS_USER_CREA'  => Auth::user()->USU_USERNAME,                
            ]);
        }else{
            //update
            $insAuto = ARS_AUTORIZACIONES_RECOBROS::where('ARS_ID',$request->AUTORIZACION['ARS_ID'])->first();
            if($insAuto){                        
                $insAuto->ARS_NUMERO_ENTREGA = $request->AUTORIZACION['ARS_NUMERO_ENTREGA'];
                $insAuto->ARS_ID_DIRECCIONAMIENTO = $request->AUTORIZACION['ARS_ID_DIRECCIONAMIENTO'];
            /*   $insAuto->ARS_POSTFECHADA = $request->AUTORIZACION['ARS_POSTFECHADA'];*/
                $insAuto->ARS_FECHA_AUTORIZACION = $fechaAutorizacion;
                $insAuto->ARS_NOMBRE_IPS_REMITE = $request->AUTORIZACION['ARS_NOMBRE_IPS_REMITE'];
                $insAuto->ARS_NIT_IPS_REMITE = $request->AUTORIZACION['ARS_NIT_IPS_REMITE'];
                $insAuto->ARS_CANTIDAD = $request->AUTORIZACION['ARS_CANTIDAD'];
                $insAuto->ARS_CODIGO_TECONOLOGIA = $request->AUTORIZACION['ARS_CODIGO_TECONOLOGIA'];
                $insAuto->ARS_NUM_DOC_AFILIADO = $request->AUTORIZACION['ARS_NUM_DOC_AFILIADO'];
                $insAuto->ARS_TIPO_DOC_AFILIADO = $request->AUTORIZACION['ARS_TIPO_DOC_AFILIADO'];
                $insAuto->ARS_NUMERO_PRESCRIPCION = $request->AUTORIZACION['ARS_NUMERO_PRESCRIPCION'];
                /* $insAuto->ARS_FECHA_POSIBLE_ENTREGA = $request->AUTORIZACION['ARS_FECHA_POSIBLE_ENTREGA'];  */
                $insAuto->ARS_USER_MODIFICA = Auth::user()->USU_USERNAME;
                $insAuto->save();
            }    
        }

        /// inconsistencias
        foreach($request->INCONSISTENCIAS as $value){
            $inconsistencia = IRS_INCONSISTENCIA_RECOBROS::where('IRS_MAESTRA_RECOBROS_ID',$request->ARU_MAESTRA_RECOBROS_ID)
            ->where('IRS_TIPO_DOCUMENTAL_ID',$value['TDR_ID'])
            ->first();

            if($inconsistencia){
                $inconsistencia->IRS_ESTADO_ID = $value['IRS_ESTADO_ID'];
                $inconsistencia->IRS_USER_MODIFICA = Auth::user()->USU_USERNAME;
                $inconsistencia->save();
            }else if($value['IRS_ESTADO_ID'] != ""){
                IRS_INCONSISTENCIA_RECOBROS::create([
                    'IRS_MAESTRA_RECOBROS_ID' => $request->ARU_MAESTRA_RECOBROS_ID,
                    'IRS_TIPO_DOCUMENTAL_ID' => $value['TDR_ID'],
                    'IRS_ESTADO_ID' => 1,
                    'IRS_USER_CREA' => Auth::user()->USU_USERNAME               
                ]);
            }
        }
        // actualiza observacion
        $maestra = MRS_MAESTRA_RECOBROS::where('MRS_ID',$request['MRS_ID'])->first();
        $maestra->MRS_OBSERVACION_INCONSISTENCIA = $request['MRS_OBSERVACION_INCONSISTENCIA'];
        $maestra->MRS_USER_MODIFICA = Auth::user()->USU_USERNAME;
        $maestra->save();
        
        ///funcion de estados
        $validaEstado = self::estadoRecobro($request['MRS_ID'], $request['MRS_ESTADO_ID']);

        $respuesta = array(
            'FACTURAID' => $insFactura,
            'SERVICIOID' => $insServicio,
            'AUTORIZACIONID' => $insAuto,
            'REMOVER' => $validaEstado,
        );             
        //dd($respuesta)->all();
        return response()->json($respuesta,200);   
    }

    public function consultaTecnologias (Request $request){                   
        /*$tecnologias = DB::table(DB::raw('[10.99.1.37\REP_MEDIMAS].[REDcon].[dbo].[redProcedimiento]'))
        ->select('proIDProcedimiento','proDesProcedimiento')
        ->where('proIDProcedimiento','like','%'.strval($request->nombreTecnologia).'%')
        ->orWhere('proDesProcedimiento','like','%'.strval($request->nombreTecnologia).'%')
        ->get(); */
        $tecnologias = TCU_TECNOLOGIA_CUMS::select('TCU_CUMS_MINISTERIO','TCU_PRODUCTO')
        ->where('TCU_CUMS_MINISTERIO','like','%'.strval($request->nombreTecnologia).'%')
        ->orWhere('TCU_PRODUCTO','like','%'.strval($request->nombreTecnologia).'%')
        ->get();
        return response()->json($tecnologias, 200);
    }

    function estadoRecobro ($conseRecobro = NULL, $estado = NULL){     
        ////estados del recobro
        $cumple = 0; 
        $estadosRecobro = EMR_ESTADO_MAESTRA_RECOBROS::select('EMR_ESTADO_ID')
        ->where('EMR_MAESTRA_RECOBROS_ID',$conseRecobro)->get(); 
        $estadosRecobro = json_decode($estadosRecobro, true);           
        $flagSix = 1;
        
        // rule 6   data complet 
        $ruleSix = MRS_MAESTRA_RECOBROS::select(
            'FRS_FACTURA_RECOBROS.FRS_NRO_FACTURA',
            'FRS_FACTURA_RECOBROS.FRS_FECHA_FACTURA',                
            'SRS_SERVICIO_RECOBROS.SRS_CODIGO_TECNOLOGIA',
            'SRS_SERVICIO_RECOBROS.SRS_NOMBRE_TECNOLOGIA',
            'SRS_SERVICIO_RECOBROS.SRS_FECHA_PRESTACION',
            'SRS_SERVICIO_RECOBROS.SRS_NRO_AUTORIZACION',            
            'SRS_SERVICIO_RECOBROS.SRS_CANTIDAD',
            'SRS_SERVICIO_RECOBROS.SRS_VALOR_UNITARIO',
            'SRS_SERVICIO_RECOBROS.SRS_VALOR_TOTAL',
            'SRS_SERVICIO_RECOBROS.SRS_CUOTA_MODERADORA_COPAGO',
            'SRS_SERVICIO_RECOBROS.SRS_TIPO_ID_USUARIO',
            'SRS_SERVICIO_RECOBROS.SRS_NRO_ID_USUARIO',
            'ARS_AUTORIZACIONES_RECOBROS.ARS_NRO_AUTORIZACION',
          /*   'ARS_AUTORIZACIONES_RECOBROS.ARS_NUMERO_ENTREGA',
            'ARS_AUTORIZACIONES_RECOBROS.ARS_ID_DIRECCIONAMIENTO',
            'ARS_AUTORIZACIONES_RECOBROS.ARS_NOMBRE_IPS_REMITE',
            'ARS_AUTORIZACIONES_RECOBROS.ARS_NIT_IPS_REMITE',
            'ARS_AUTORIZACIONES_RECOBROS.ARS_CANTIDAD',
            'ARS_AUTORIZACIONES_RECOBROS.ARS_CODIGO_TECONOLOGIA',
            'ARS_AUTORIZACIONES_RECOBROS.ARS_NUM_DOC_AFILIADO',
            'ARS_AUTORIZACIONES_RECOBROS.ARS_TIPO_DOC_AFILIADO',                        
            'ARS_AUTORIZACIONES_RECOBROS.ARS_NUMERO_PRESCRIPCION' */
        )    
        ->leftJoin('RECOBROS.FRS_FACTURA_RECOBROS','FRS_MAESTRA_RECOBROS_ID','=','MRS_ID')           
        ->leftJoin('RECOBROS.SRS_SERVICIO_RECOBROS','SRS_MAESTRA_RECOBROS_ID','=','MRS_ID')
        ->leftJoin('RECOBROS.ARS_AUTORIZACIONES_RECOBROS','ARS_MAESTRA_RECOBROS_ID','=','MRS_ID')
        ->where('MRS_ID',$conseRecobro)
        ->get();
        $ruleSix = json_decode($ruleSix, true);
        $ruleSix = $ruleSix[0];                
        foreach ($ruleSix as $key => $value) {
            if($ruleSix[$key] == ""){
                $flagSix = 0;
            }
        }
        if($flagSix == 1){
            $newEstado = 6;
            if( array_search($newEstado, array_column($estadosRecobro, 'EMR_ESTADO_ID')) == "" ){ //validamos que no tenga el estado
                $updateCabezera = MRS_MAESTRA_RECOBROS::where('MRS_ID',$conseRecobro)->first();
                $updateCabezera->MRS_ESTADO_ID = $newEstado;            
                $updateCabezera->MRS_USER_MODIFICA = Auth::user()->USU_USERNAME;
                $updateCabezera->save();
                /////////                        
                $insertEstado = EMR_ESTADO_MAESTRA_RECOBROS::create([
                    'EMR_MAESTRA_RECOBROS_ID' => $conseRecobro,
                    'EMR_ESTADO_ID' => $newEstado,
                    'EMR_ESTADO_ID_ANTERIOR' => $estado,
                    'EMR_USER_CREAR' =>Auth::user()->USU_USERNAME,      
                    'EMR_FECHA_VENCIMIENTO' =>  date('Y-m-d h:i:s'),             
                ]);  
                $estado = $newEstado;
            }
        }
        
        // consultamos de nuevo los estados
        $estadosRecobro = EMR_ESTADO_MAESTRA_RECOBROS::select('EMR_ESTADO_ID')
        ->where('EMR_MAESTRA_RECOBROS_ID',$conseRecobro)->get(); 
        $estadosRecobro = json_decode($estadosRecobro, true);   

        ///rule 8 Document complet
        if(array_search(6, array_column($estadosRecobro, 'EMR_ESTADO_ID')) != ""){
            $ruleEight = TDR_TIPO_DOCUMENTAL::select()
            ->where('TDR_MAESTRA_RECOBROS_ID',$conseRecobro)
            ->whereIn('TDR_TIPO_DOC',['Factura','Autorizacion','Entrega'])
            ->count();
            $ruleEight = json_decode($ruleEight, true);                   
            if($ruleEight >= 3){// cumple con la regla
                //a 9
                $newEstado = 9;
                if( array_search($newEstado, array_column($estadosRecobro, 'EMR_ESTADO_ID')) == "" ){ //validamos que no tenga el estado
                    $updateCabezera = MRS_MAESTRA_RECOBROS::where('MRS_ID',$conseRecobro)->first();
                    $updateCabezera->MRS_ESTADO_ID = $newEstado;            
                    $updateCabezera->MRS_USER_MODIFICA = Auth::user()->USU_USERNAME;
                    $updateCabezera->save();
                    /////////                        
                    $insertEstado = EMR_ESTADO_MAESTRA_RECOBROS::create([
                        'EMR_MAESTRA_RECOBROS_ID' => $conseRecobro,
                        'EMR_ESTADO_ID' => $newEstado,
                        'EMR_ESTADO_ID_ANTERIOR' => $estado,
                        'EMR_USER_CREAR' =>Auth::user()->USU_USERNAME,  
                        'EMR_FECHA_VENCIMIENTO' =>  date('Y-m-d h:i:s'),       
                    ]);  
                }            
            }else{            
                $newEstado = 8;
                if( array_search($newEstado, array_column($estadosRecobro, 'EMR_ESTADO_ID')) == "" ){ //validamos que no tenga el estado
                    $updateCabezera = MRS_MAESTRA_RECOBROS::where('MRS_ID',$conseRecobro)->first();
                    $updateCabezera->MRS_ESTADO_ID = $newEstado;            
                    $updateCabezera->MRS_USER_MODIFICA = Auth::user()->USU_USERNAME;
                    $updateCabezera->save();
                    /////////                        
                    $insertEstado = EMR_ESTADO_MAESTRA_RECOBROS::create([
                        'EMR_MAESTRA_RECOBROS_ID' => $conseRecobro,
                        'EMR_ESTADO_ID' => $newEstado,
                        'EMR_ESTADO_ID_ANTERIOR' => $estado,
                        'EMR_USER_CREAR' =>Auth::user()->USU_USERNAME,            
                        'EMR_FECHA_VENCIMIENTO' =>  date('Y-m-d h:i:s'),       
                    ]);  
                    $cumple = 1; 
                }           
            }       
        }

        // consultamos los estados
        $estadosRecobro = EMR_ESTADO_MAESTRA_RECOBROS::select('EMR_ESTADO_ID')
        ->where('EMR_MAESTRA_RECOBROS_ID',$conseRecobro)->get(); 
        $estadosRecobro = json_decode($estadosRecobro, true); 

        /////// Rule 15 inconsistencias
        $rulefiftena = MRS_MAESTRA_RECOBROS::select('MRS_OBSERVACION_INCONSISTENCIA')
        ->where('MRS_ID',$conseRecobro)
        ->get();

        $rulefiftenb = IRS_INCONSISTENCIA_RECOBROS::select('')
        ->where('IRS_MAESTRA_RECOBROS_ID',$conseRecobro)
        ->count();

        if($rulefiftena[0]['MRS_OBSERVACION_INCONSISTENCIA'] != '' && $rulefiftenb > 0){/// si cumple regla
            $newEstado = 15;
            if( array_search($newEstado, array_column($estadosRecobro, 'EMR_ESTADO_ID')) == "" ){ //validamos que no tenga el estado

                $updateCabezera = MRS_MAESTRA_RECOBROS::where('MRS_ID',$conseRecobro)->first();
                $updateCabezera->MRS_ESTADO_ID = $newEstado;            
                $updateCabezera->MRS_USER_MODIFICA = Auth::user()->USU_USERNAME;
                $updateCabezera->save();
                /////////               
                $insertEstado = EMR_ESTADO_MAESTRA_RECOBROS::create([
                    'EMR_MAESTRA_RECOBROS_ID' => $conseRecobro,
                    'EMR_ESTADO_ID' => $newEstado,
                    'EMR_ESTADO_ID_ANTERIOR' => $estado,
                    'EMR_USER_CREAR' =>Auth::user()->USU_USERNAME,            
                    'EMR_FECHA_VENCIMIENTO' =>  date('Y-m-d h:i:s'),       
                ]);  
            }           
        }
        return $cumple;
    }

    public function verDocs($idRuta){

        $documentos = TDR_TIPO_DOCUMENTAL::select('TDR_RUTA_COMPLETA')
        ->where('TDR_ID',$idRuta)
        ->first();

                
        $file = \File::get($documentos->TDR_RUTA_COMPLETA);
        $response = \Response::make($file, 200);
        $response->header('Content-Type', 'application/pdf');
        return $response;   
    }

    private function orderMultiDimensionalArray ($toOrderArray, $field, $inverse = false) {  
        $position = array();  
        $newRow = array();  
        foreach ($toOrderArray as $key => $row) {  
                $position[$key]  = $row[$field];  
                $newRow[$key] = $row;  
        }  
        if ($inverse) {  
            arsort($position);  
        }  
        else {  
            asort($position);  
        }  
        $returnArray = array();  
        foreach ($position as $key => $pos) {       
            $returnArray[] = $newRow[$key];  
        }  
        return $returnArray;  
    }  

}
