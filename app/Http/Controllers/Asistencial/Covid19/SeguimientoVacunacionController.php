<?php

namespace App\Http\Controllers\Asistencial\Covid19;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\asistencial\covid19\SARE_SEG_AFILIADO_REGISTRO;
use App\Models\asistencial\covid19\SRSE_SEG_REGISTRO_SEGUIMIENTO;
use App\Models\asistencial\covid19\SESE_SEG_ESTADO_SEGUIMIENTO;
use App\Models\asistencial\covid19\STCO_SEG_TIPO_CONTACTO;
use App\Models\asistencial\covid19\STGE_SEG_TIPO_GESTION;



class SeguimientoVacunacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //
        return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $registroAfiliado = SARE_SEG_AFILIADO_REGISTRO::where('SARE_NUMERO_DOCUMENTO',$request->NumDocAfiliado)->first();

        if(!$registroAfiliado){
            $registroAfiliado = SARE_SEG_AFILIADO_REGISTRO::create([
                'SARE_NUMERO_DOCUMENTO' => $request->NumDocAfiliado,
                'SARE_ESTADO_SEGUIMIENTO_ID' => $request->seguimientoEstadoSeguimiento['SESE_ID'],
            ]);
        }else{
            SARE_SEG_AFILIADO_REGISTRO::where('SARE_NUMERO_DOCUMENTO',$request->NumDocAfiliado)
            ->update([
                'SARE_ESTADO_SEGUIMIENTO_ID' => $request->seguimientoEstadoSeguimiento['SESE_ID']
            ]);
        }

        $registrarSeguimiento = SRSE_SEG_REGISTRO_SEGUIMIENTO::create([
            'SRSE_AFILIADO_REGISTRO_ID' => $registroAfiliado->SARE_ID,	
            'SRSE_TIPO_CONTACTO_ID' => $request->seguimientoTipoContato,	
            'SRSE_TIPO_GESTION_ID' => $request->seguimientoTipoGestion,	
            'SRSE_ESTADO_SEGUIMIENTO_ID' => $request->seguimientoEstadoSeguimiento['SESE_ID'],	
            'SRSE_OBSERVACION' => $request->seguimientoObservacionGestion,	
            'SRSE_USUARIO_ID' => \Auth::user()->USU_ID
        ]);

        return response()->json([
            'mensaje' => 'Registro creado correctamente, ID registro  '.$registrarSeguimiento->SRSE_ID.' ',
            'seguimientos' => $this->consultarSeguimientosAfiliado($request->NumDocAfiliado)
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $registrarSeguimiento = SRSE_SEG_REGISTRO_SEGUIMIENTO::find($id);
        
        $registrarSeguimiento->SRSE_AFILIADO_REGISTRO_ID = $registroAfiliado->SARE_ID;	
        $registrarSeguimiento->SRSE_TIPO_CONTACTO_ID = $request->seguimientoTipoContato;	
        $registrarSeguimiento->SRSE_TIPO_GESTION_ID = $request->seguimientoTipoGestion;	
        $registrarSeguimiento->SRSE_ESTADO_SEGUIMIENTO_ID = $request->seguimientoEstadoSeguimiento;	
        $registrarSeguimiento->SRSE_OBSERVACION = $request->seguimientoObservacionGestion;	
        $registrarSeguimiento->SRSE_USUARIO_ID = \Auth::user()->USU_ID;        
        $registrarSeguimiento->save();
        
        if($registrarSeguimiento){
            return response()->json([
                'mensaje' => 'Informacion actualizada, ID registro '.$registrarSeguimiento->SRSE_ID.' ',
                'seguimientos' => $this->consultarSeguimientosAfiliado($request->NumDocAfiliado)
            ], 200);
        }else{
            return response()->json([
                'mensaje' => 'No se pudo actualizar la información',
                'seguimientos' => []
            ], 200);
        }

    }

    private function consultarSeguimientosAfiliado($identificacionAfiliado){
        
        $seguimientos = SRSE_SEG_REGISTRO_SEGUIMIENTO::select(
            'SRSE_ID',
            'SRSE_OBSERVACION',
            'SRSE_FECHA_CREACION',
            'STCO_NOMBRE AS TIPO_CONTACTO',
            'STGE_NOMBRE AS TIPO_GESTION',
            'SESE_NOMBRE AS ESTADO_SEGUIMIENTO',
            'USU_USERNAME',
            \DB::raw("CASE WHEN CAST(SRSE_FECHA_CREACION AS DATE) = CAST(GETDATE() AS DATE) THEN 'SI' ELSE 'NO' END AS CREADO_HOY")
        )
        ->join('MiVacuna.SARE_SEG_AFILIADO_REGISTRO','SRSE_AFILIADO_REGISTRO_ID','=','SARE_ID')
        ->join('Mivacuna.STCO_SEG_TIPO_CONTACTO','SRSE_TIPO_CONTACTO_ID','=','STCO_ID')
        ->join('Mivacuna.STGE_SEG_TIPO_GESTION','SRSE_TIPO_GESTION_ID','=','STGE_ID')
        ->join('Mivacuna.SESE_SEG_ESTADO_SEGUIMIENTO','SRSE_ESTADO_SEGUIMIENTO_ID','=','SESE_ID')
        ->join('Seguridad.USU_LOGIN','SRSE_USUARIO_ID','=','USU_ID')
        ->where('SARE_NUMERO_DOCUMENTO',$identificacionAfiliado)
        ->orderBy('SRSE_FECHA_CREACION','DESC')
        ->get();

        return $seguimientos;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
