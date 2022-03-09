<?php

namespace App\Http\Controllers\Administrativo;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Administrativo\PER_PERIODOS;

use DB;
use Auth;

class periodosController extends Controller
{    
    public function index(){          
        return view('Administrativo.periodos' );
    }

    public function consultaPeriodos(){
        return $lista = PER_PERIODOS::select('PER_ID', 'PER_CODIGO', 'PER_FECHA_INICIAL','PER_FECHA_FINAL',
                        'PER_DESCRIPCION', 
                        'PER_CERRADO',
                        DB::raw(" CASE PER_CERRADO WHEN 1 THEN 'CERRADO' WHEN 0 THEN 'ABIERTO' END AS PER_ESTADO")                                
                )
                ->orderBy('PER_ID','DESC')  
                ->get();
    }

    public function actualizaEstado($id,$cierre){    
        $estado = 1;
        if($cierre == 1){
            $estado = 0;
        }

        //actualiza
        $updatePeriodo = PER_PERIODOS::where('PER_ID',$id)         
                            ->first();
        if($updatePeriodo){
            $updatePeriodo->PER_CERRADO = $estado;
            $updatePeriodo->PER_USUARIO_MODIFICA = Auth::user()->USU_USERNAME;   
            $updatePeriodo->save();                 
        }
        return response()->json($updatePeriodo->PER_ID,200); 
    }
























    private function listaTipoSede(){
        $lista = DB::table(DB::raw('[Sedes].[TIS_TIPO_SEDE]'))
                    ->select('TIS_ID',  DB::raw('RTRIM(TIS_NOMBRE) AS TIS_NOMBRE'))
                    ->get();                           
        return $lista;                            
    }

    private function listaRegional(){
        $lista = RNL_REGIONAL::where('RNL_ESTADO','1')                        
                        ->get();
        return $lista;
    }
    
    private function listaEstados(){
        $lista = DB::table(DB::raw('[Sedes].[ESS_ESTADO_SEDE]'))
                    ->select('ESS_ID',  DB::raw('RTRIM(ESS_NOMBRE) AS ESS_NOMBRE'))
                    ->get();  
        return $lista;
    }

    private function listaDepartamentos(){
        $lista = DEP_DANE_DEPARTAMENTO::select('DEP_ID','DEP_NOMBRE')
                   ->get(); 
        return $lista;
    }

    public function consultarMunicipios($parametro){
        //dd($parametro);
        $vDatos = explode('||',$parametro);
        $depa = DEP_DANE_DEPARTAMENTO::select('DEP_CODIGO')
                ->where('DEP_ID',$vDatos[0])
                ->get(); 
        $lista = MUN_DANE_MUNICIPIO::select('MUN_ID', 'MUN_DEP_CODIGO','MUN_NOMBRE_SIN_CARACTERES_ESPECIALES')
                ->where('MUN_DEP_CODIGO',$depa[0]->DEP_CODIGO)
                ->where('MUN_NOMBRE_SIN_CARACTERES_ESPECIALES','like','%'.strval($vDatos[1]).'%')
                ->limit(50)
                ->get();
        return response()->json($lista);         
    }

    private function listaSeccional(){
        $lista = DB::table(DB::raw('Sedes.SEC_SECCIONAL'))
            ->select('SEC_ID',  DB::raw('RTRIM(SEC_NOMBRE) AS SEC_NOMBRE'))
            ->get();  
        return $lista;
    }

    private function listaTipo(){
        $lista = DB::table(DB::raw('Sedes.TIP_TIPO'))
            ->select('TIP_ID',  DB::raw('RTRIM(TIP_DESCRIPCION) AS TIP_DESCRIPCION'))
            ->get();  
        return $lista;
    }

    private function listaRegimen(){
        $listaRegimen = REG_REGIMEN::get();
        return $listaRegimen;
    }  

    public function guardaSede(Request $request){  
        //dd($request) 
        $respuesta = array();
        $munic = json_decode($request->municipio);   
        
        //consultamos Sede
        $ConsultaSede = COS_CODIGO_SEDES::where('COS_CODIGO',$request->codigo)        
                        ->first();

        if($request->COS_ID){


            //actualiza
            $updateSede = COS_CODIGO_SEDES::where('COS_ID',$request->COS_ID)
                        ->where('COS_CODIGO',$request->codigo)   
                        ->first();
            if($updateSede){                
                $updateSede->COS_CODIGO = $request->codigo ;
                $updateSede->COS_DIRECCION_SEDE = $request->direccion ;
                $updateSede->COS_NOMBRE_SEDE = $request->nombre ;
                $updateSede->COS_METROS_CUADRADO = $request->metroCua ;
                $updateSede->COS_TIS_ID = $request->tipSede ;
                $updateSede->COS_RNL_ID = $request->regional ;
                $updateSede->COS_ESS_ID = $request->estadoGen ;
                $updateSede->COS_MUN_ID = $munic->MUN_ID;
                $updateSede->COS_DEP_ID = $request->departamento;
                $updateSede->COS_SEC_ID = $request->seccional ;
                $updateSede->COS_TIP_ID = $request->Tipo ;
                $updateSede->COS_REG_ID = $request->regimen ;
                $updateSede->COS_OBSERVACION = $request->observacion ;
                $updateSede->COS_USUARIO_MODIFICA = Auth::user()->USU_USERNAME ;
                $updateSede->save();
                $respuesta["text"] = "Guardado con Exito, Se actualizo la Sede Numero: ".$request->COS_ID;
                $respuesta["icon"] = 'success';
                $respuesta["title"] = 'Ok';              
            }else{
                $respuesta["text"] = "No se puede Actualizar Datos de la Sede";
                $respuesta["icon"] = 'error';
                $respuesta["title"] = 'Error al guardar el Registro...';       
            }   
        }else{

            if(!$ConsultaSede){
                //inserta
                $insrtSede = COS_CODIGO_SEDES::create([  
                    'COS_CODIGO' => $request->codigo,
                    'COS_DIRECCION_SEDE' => $request->direccion,
                    'COS_NOMBRE_SEDE' => $request->nombre,
                    'COS_METROS_CUADRADO' => $request->metroCua,
                    'COS_TIS_ID' => $request->tipSede,
                    'COS_RNL_ID' => $request->regional,
                    'COS_ESS_ID' => $request->estadoGen,
                    'COS_MUN_ID' => $munic->MUN_ID,
                    'COS_DEP_ID' => $request->departamento,
                    'COS_SEC_ID' => $request->seccional,
                    'COS_TIP_ID' => $request->Tipo,
                    'COS_REG_ID' => $request->regimen,
                    'COS_OBSERVACION' => $request->observacion,
                    'COS_USUARIO_CREA' => Auth::user()->USU_USERNAME,
                ]);    
                
                $respuesta["text"] = "Guardado con Exito, Consecutivo Generado: ".$insrtSede->COS_ID;
                $respuesta["icon"] = 'success';
                $respuesta["title"] = 'Ok';
            }else{
                $respuesta["text"] = "El Codigo de Sede ya Existe, por Favor Validar!!";
                $respuesta["icon"] = 'error';
                $respuesta["title"] = 'Error al guardar el Registro...';    
            }    
        }
        return response()->json($respuesta,200);         
    }

    public function consultaSedes(){
        $sedes = array();
        $sedes = COS_CODIGO_SEDES::select('COS_ID', 'COS_CODIGO', 'COS_DIRECCION_SEDE', 'COS_NOMBRE_SEDE')      
                    ->orderBy('COS_ID','DESC')  
                    ->get();
        return response()->json($sedes);   
    }

    public function dataSede($parametro){
        //dd($parametro);
        $dataSedes = [];
        $sedes = COS_CODIGO_SEDES::where('COS_ID',$parametro)
                    ->get();

        $municipio = MUN_DANE_MUNICIPIO::select('MUN_ID', 'MUN_DEP_CODIGO','MUN_NOMBRE_SIN_CARACTERES_ESPECIALES')
                        ->where('MUN_ID',$sedes[0]->COS_MUN_ID)
                        ->get();         
        $dataSedes = [
            'SEDEDATA'  => $sedes[0],
            'MUNICIPIO' => $municipio[0],
        ];
        return response()->json($dataSedes);   
    }

}
