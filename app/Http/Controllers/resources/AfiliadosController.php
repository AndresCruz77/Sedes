<?php

namespace App\Http\Controllers\resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\asistencial\afd_detalle_afiliados;
use App\Models\asistencial\covid19\SRSE_SEG_REGISTRO_SEGUIMIENTO;

class AfiliadosController extends Controller
{
    public function busquedaBasicaXTipoNumero(Request $request){
        $consulta = afd_detalle_afiliados::select(
            "TipDocAfiliado",
            "NumDocAfiliado",
            "PrimerNombre",
            "SegundoNombre",
            "PrimerApellido",
            "SegundoApellido",
            "FechaNacimiento",
            "Genero",	
            "Edad",	
            "GrupoEtareo",
            "DepartamentoResidencia",
            "MunicipioResidencia",	
            "RegionalResidencia",            
            "nombreIPS",
            "EstadoHabilitar"
        )
        ->where('TipDocAfiliado',$request->TipDocAfiliado)
        ->where('NumDocAfiliado',$request->NumDocAfiliado)
        ->first();

        return response()->json($consulta,200);
    }

    public function busquedaSeguimientoCovidXTipoNumero(Request $request){

        $afiliado = afd_detalle_afiliados::select(
            "TipDocAfiliado",
            "NumDocAfiliado",
            "PrimerNombre",
            "SegundoNombre",
            "PrimerApellido",
            "SegundoApellido",
            "FechaNacimiento",
            "Genero",	
            "Edad",	
            "GrupoEtareo",
            "DepartamentoResidencia",
            "MunicipioResidencia",	
            "RegionalResidencia",            
            "nombreIPS",
            "EstadoHabilitar",
            "SARE_ESTADO_SEGUIMIENTO_ID",
            "SESE_NOMBRE AS ESTADO_SEGUIMIENTO"
        )
        ->leftJoin('MiVacuna.SARE_SEG_AFILIADO_REGISTRO','NumDocAfiliado','=','SARE_NUMERO_DOCUMENTO')
        ->leftJoin('MiVacuna.SESE_SEG_ESTADO_SEGUIMIENTO','SARE_ESTADO_SEGUIMIENTO_ID','=','SESE_ID')
        ->where('TipDocAfiliado',$request->TipDocAfiliado)
        ->where('NumDocAfiliado',$request->NumDocAfiliado)
        ->first();

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
        ->where('SARE_NUMERO_DOCUMENTO',$request->NumDocAfiliado)
        ->orderBy('SRSE_FECHA_CREACION','DESC')
        ->get();     


        $consulta = [
            'afiliado' => $afiliado,
            'seguimientos' => $seguimientos,
            'paiWeb' => []
        ];

        return response()->json($consulta,200);
    }    
}
