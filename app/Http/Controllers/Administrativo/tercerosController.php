<?php

namespace App\Http\Controllers\Administrativo;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Administrativo\TDO_TIPO_DOCUMENTO;
use App\Models\Administrativo\TER_TERCERO;


use DB;
use Auth;

class tercerosController extends Controller
{    
    public function index(){  
        //lista tipo sede
        $listatipoDocs = self::listatipoDocs();
        return view('Administrativo.terceros', compact('listatipoDocs') );
    }

    private function listatipoDocs(){
        $lista = TDO_TIPO_DOCUMENTO::select('TDO_ID', 'TDO_DESCRIPCION', 'TDO_HOMOLOGACION2')                    
                    ->orderBy('TDO_HOMOLOGACION2','ASC')
                    ->get();
        return $lista;                    
    }

    public function saveTercero(Request $request){          
        $respuesta = array();            

        $consultaTerce = TER_TERCERO::where('TER_TDO_ID',$request->tipoDocumento)
                        ->where('TER_NUMERO_DOCUMENTO',$request->numIdentifica)
                        ->first();

        if($request->TER_ID){                                        
                $updateTercero = TER_TERCERO::where('TER_ID',$request->TER_ID)   
                            ->where('TER_TDO_ID',$request->tipoDocumento)
                            ->where('TER_NUMERO_DOCUMENTO',$request->numIdentifica)
                            ->first();
                //actualiza
                if($updateTercero){                           
                    $updateTercero->TER_TDO_ID = $request->tipoDocumento;
                    $updateTercero->TER_NUMERO_DOCUMENTO = $request->numIdentifica;
                    $updateTercero->TER_NOMBRE = $request->nombreTercero;
                    $updateTercero->TER_ACTIVO = $request->estadoTerc;
                    $updateTercero->TER_USUARIO_MODIFICA = Auth::user()->USU_USERNAME ;
                    $updateTercero->save();
                    
                    $respuesta["text"] = "Guardado con Exito, Se actualizo el Tercero Numero: ".$request->TER_ID;
                    $respuesta["icon"] = 'success';
                    $respuesta["title"] = 'Ok';   
                
                }else{
                    $respuesta["text"] = "No se puede Actualizar Datos del Tercero";
                    $respuesta["icon"] = 'error';
                    $respuesta["title"] = 'Error al guardar el Registro...';       
                }   
        }else{
            //inserta
            if(!$consultaTerce){
                $insrtTercero = TER_TERCERO::create([  
                    'TER_TDO_ID' => $request->tipoDocumento,
                    'TER_NUMERO_DOCUMENTO' => $request->numIdentifica,
                    'TER_NOMBRE' => $request->nombreTercero,
                    'TER_ACTIVO' => $request->estadoTerc,
                    'TER_USUARIO_CREA' => Auth::user()->USU_USERNAME,      
                ]);                   
                $respuesta["text"] = "Guardado con Exito, Consecutivo Generado: ".$insrtTercero->TER_ID;
                $respuesta["icon"] = 'success';
                $respuesta["title"] = 'Ok';
                
            }else{               
               $respuesta["text"] = "El Tercero ya Existe, por Favor Validar!!";
               $respuesta["icon"] = 'error';
               $respuesta["title"] = 'Error al guardar el Registro...';               
            }        
        }
        return response()->json($respuesta,200);         
    }

    public function consultaTerceros(){
        $sedes = array();
        $sedes = TER_TERCERO::select('TER_ID', 'TDO_HOMOLOGACION2', 'TER_NUMERO_DOCUMENTO', 'TER_NOMBRE',
                    DB::raw(" CASE TER_ACTIVO WHEN 1 THEN 'ACTIVO' WHEN 0 THEN 'INACTIVO' END AS TER_ESTADO "))    
                    ->join('CATALOGOS.TDO_TIPO_DOCUMENTO', 'TDO_ID', '=', 'TER_TDO_ID')
                    ->orderBy('TER_ID','DESC')  
                    ->get();
        return response()->json($sedes); 
    }

    public function dataTercero($parametro){
       return $consultaTerce = TER_TERCERO::where('TER_ID',$parametro)->first();                 
    }

}
