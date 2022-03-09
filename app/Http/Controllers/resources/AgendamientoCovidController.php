<?php

namespace App\Http\Controllers\resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Asistencial\Covid19\TraitApiVacunacion;

use App\Models\Asistencial\covid19\VacunacionRegistro;
use App\Models\asistencial\covid19\vacunacionRegistroAsignacion;
use App\Models\Asistencial\covid19\VacunacionRegistoLogAgendamiento;

class agendamientoCovidController extends Controller
{

    use TraitApiVacunacion;

    public function registrarAgendamientoMipres(){
        
        $camposAgendamiento = [];
        $camposAnulacion = [];
        $metodoApi = [];
        $cont = 0;

        $agendamientosPendientes = VacunacionRegistro::
        select(
            'RAG_ID',	
            'RAG_TIPO_DOCUMENTO_PACIENTE as TipoIDPaciente',	
            'RAG_NUMERO_DOCUMENTO_PACIENTE as NoIDPaciente',	
            'RAG_PRIMER_NOMBRE as PrimerNombre',	
            'RAG_SEGUNDO_NOMBRE as SegundoNombre',	
            'RAG_PRIMER_APELLIDO as PrimerApellido',	
            'RAG_SEGUNDO_APELLIDO as SegundoApellido',	
            'RAG_CODIGO_PRESTADOR as CodPrestador',	
            'RAG_FECHA_AGENDAMIENTO as FechaAgendamiento',	
            'RAG_HORA_AGENDAMIENTO as HoraAgendamiento',	
            'RAG_NUMERO_DOSIS as NumeroDosis',	
            'RAG_CAUSA_NO_AGENDAMIENTO as CausaNoAgendamiento',	
            'RAG_OPERACION_AGENDAMIENTO',
            'RAG_RADICADO_ID',
            'RAG_CARGUE_ID'
        )
        ->limit(2000)
        ->whereNull('RAG_PROCESADO')
        ->get();

        $token = $this->getToken();

        foreach ($agendamientosPendientes as $key => $value) {
            if ($value->RAG_OPERACION_AGENDAMIENTO == 1) {

                $camposAgendamiento = [
                    'TipoIDPaciente' => $value->TipoIDPaciente,
                    'NoIDPaciente' => $value->NoIDPaciente,
                    'PrimerNombre' => $value->PrimerNombre,
                    'SegundoNombre' => $value->SegundoNombre,
                    'PrimerApellido' => $value->PrimerApellido,
                    'SegundoApellido' => $value->SegundoApellido,
                    'CodPrestador' => $value->CodPrestador,
                    'FechaAgendamiento' => $value->FechaAgendamiento,
                    'HoraAgendamiento' => substr($value->HoraAgendamiento,0,5),
                    'NumeroDosis' => $value->NumeroDosis,
                    'CausaNoAgendamiento' => $value->CausaNoAgendamiento,
                ];

                $metodoApi = $this->putAgendamiento($camposAgendamiento,$token);

                // Se verifica si la respuesta contiene un campo ID para comprobar que fue radicado y almacenar la respuesta
                if($respuestaAgendamiento = json_decode($metodoApi,true)){
                        VacunacionRegistro::where('RAG_ID',$value->RAG_ID)
                        ->update([
                            'RAG_RADICADO_ID' => (isset($respuestaAgendamiento['ID'])) ? $respuestaAgendamiento['ID'] : 0,
                            'RAG_RADICADO_MENSAJE' => json_encode($respuestaAgendamiento),
                            'RAG_FECHA_RADICADO' => date('Y-m-d H:i:s'),
                            'RAG_PROCESADO' => 1,
                        ]);
                }                

            }elseif ($value->RAG_OPERACION_AGENDAMIENTO == 2) {
                
                $camposAnulacion = [
                    'TipoIDPaciente' => $value->TipoIDPaciente,
                    'NoIDPaciente' => $value->NoIDPaciente,
                    'IDAgendamiento' => $value->RAG_RADICADO_ID
                ];

                $metodoApi = $this->putAnularAgendamiento($camposAnulacion,$token);

                // Se verifica si la respuesta contiene un campo ID para comprobar que fue radicado y almacenar la respuesta
                if($respuestaAgendamiento = json_decode($metodoApi,true)){
                        VacunacionRegistro::where('RAG_ID',$value->RAG_ID)
                        ->update([
                            'RAG_ANULADO_ID' => (isset($respuestaAgendamiento['ID'])) ? $respuestaAgendamiento['ID'] : 0,
                            'RAG_ANULADO_MENSAJE' => json_encode($respuestaAgendamiento),
                            'RAG_FECHA_ANULADO' => date('Y-m-d H:i:s'),
                            'RAG_PROCESADO' => 1,
                        ]);
                }                
            }

            $this->setRegistrarLogApi(
                $value->RAG_ID,
                $value->RAG_CARGUE_ID,
                $putAgendamiento = $metodoApi
            );

            $cont++;

        }

        $mensaje = [
            'mensaje' => 'Se validaron en total '.$cont.' registros de agendamiento',
        ];

        return response()->json($mensaje);

    }

    public function registrarAsignacionMipres(){

        $camposAsignacion = [];
        $camposAnulacion = [];
        $metodoApi = [];
        $cont = 0;

        $asignacionesPendientes = vacunacionRegistroAsignacion::
        select(
            'RAA_ID',	
            'RAA_TIPO_DOCUMENTO_PACIENTE as TipoIDPaciente',	
            'RAA_NUMERO_DOCUMENTO_PACIENTE as NoIDPaciente',	
            'RAA_CODIGO_PRESTADOR as CodPrestador',	
            'RAA_OPERACION_ASIGNACION',
            'RAA_RADICADO_ID',
            'RAA_CARGUE_ID'
        )
        ->limit(2000)
        ->whereNull('RAA_PROCESADO')
        ->get();

        $token = $this->getToken();

        foreach ($asignacionesPendientes as $key => $value) {
            if ($value->RAA_OPERACION_ASIGNACION == 1) {

                $camposAsignacion = [
                    'TipoIDPaciente' => $value->TipoIDPaciente,
                    'NoIDPaciente' => $value->NoIDPaciente,
                    'CodPrestador' => $value->CodPrestador,
                ];

                $metodoApi = $this->putAsignacion($camposAsignacion,$token);

                // Se verifica si la respuesta contiene un campo ID para comprobar que fue radicado y almacenar la respuesta
                if($respuestaAgendamiento = json_decode($metodoApi,true)){

                        vacunacionRegistroAsignacion::where('RAA_ID',$value->RAA_ID)
                        ->update([
                            'RAA_RADICADO_ID' => (isset($respuestaAgendamiento['ID'])) ? $respuestaAgendamiento['ID'] : 0,
                            'RAA_RADICADO_MENSAJE' => json_encode($respuestaAgendamiento),
                            'RAA_FECHA_RADICADO' => date('Y-m-d H:i:s'),
                            'RAA_PROCESADO' => 1,
                        ]);
                }                

            }elseif ($value->RAA_OPERACION_ASIGNACION == 2) {
                
                $camposAnulacion = [
                    'TipoIDPaciente' => $value->TipoIDPaciente,
                    'NoIDPaciente' => $value->NoIDPaciente,
                    'IDAsignacion' => $value->RAA_RADICADO_ID
                ];

                $metodoApi = $this->putAnularAsignacion($camposAnulacion,$token);

                // Se verifica si la respuesta contiene un campo ID para comprobar que fue radicado y almacenar la respuesta
                if($respuestaAgendamiento = json_decode($metodoApi,true)){
                        vacunacionRegistroAsignacion::where('RAA_ID',$value->RAA_ID)
                        ->update([
                            'RAA_ANULADO_ID' => (isset($respuestaAgendamiento['ID'])) ? $respuestaAgendamiento['ID'] : 0,
                            'RAA_ANULADO_MENSAJE' => json_encode($respuestaAgendamiento),
                            'RAA_FECHA_ANULADO' => date('Y-m-d H:i:s'),
                            'RAA_PROCESADO' => 1,
                        ]);
                }                
            }

            $this->setRegistrarLogApi(
                $value->RAA_ID,
                $value->RAA_CARGUE_ID,
                $putAgendamiento = $metodoApi
            );

            $cont++;

        }

        $mensaje = [
            'mensaje' => 'Se validaron en total '.$cont.' registros de asignación',
        ];

        return response()->json($mensaje);

    }

    private function setRegistrarLogApi($idRegistro=0, $idCargue=0,$respuestaAgendamiento=[]){
        return VacunacionRegistoLogAgendamiento::create([    
            'RLA_RAG_ID' => $idRegistro,	
            'RLA_RCP_ID' => $idCargue,
            'RLA_REQUEST' => $respuestaAgendamiento,	
            'RLA_ID_USER' => 0,	
            'RLA_CREATED_AT' => date('Y-m-d H:i:s'),
        ]);
    }   
}
