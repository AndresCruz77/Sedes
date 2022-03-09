<?php

namespace App\Http\Controllers\Asistencial\Covid19;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VacunacionAgendamientoImport;
use App\Imports\VacunacionAsignacionImport;
use App\Exports\VacunacionAgendamientoExport;
use App\Exports\VacunacionAsignacionExport;
use App\Http\Controllers\Asistencial\Covid19\TraitApiVacunacion;
use App\Models\Asistencial\covid19\VacunacionRegistro;
use App\Models\Asistencial\covid19\VacunacionRegistoLogAgendamiento;
use App\Models\Asistencial\tdo_tipo_documento;
use App\Models\asistencial\covid19\iha_ips_habilitacion;
use App\Models\asistencial\covid19\vacunacionRegistroAsignacion;

use App\Models\asistencial\covid19\SESE_SEG_ESTADO_SEGUIMIENTO;
use App\Models\asistencial\covid19\STCO_SEG_TIPO_CONTACTO;
use App\Models\asistencial\covid19\STGE_SEG_TIPO_GESTION;

use DB;
use Auth;

class VacunacionController extends Controller
{

    use TraitApiVacunacion;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tipoDocumentos = tdo_tipo_documento::all();

        $historialCargues = DB::table('MiVacuna.rcp_registrar_cargue_plano')
        ->leftJoin('MiVacuna.rec_registro_estado_cargue AS b','rcp_registrar_cargue_plano.RCP_ID_ESTADO','=','b.REC_ID')
        ->where('RCP_ID_USER',Auth::user()->USU_ID)
        ->where(DB::raw('cast(RCP_FECHA_CREACION as date)'),date('Y-m-d'))
        ->orderBy('RCP_FECHA_CREACION','desc')
        ->get();

        $ipsUsuario = iha_ips_habilitacion::select(
            'IHA_ID',
            'IHA_CODIGO_HABILITACION',
            'IHA_NOMBRE_PRESTADOR'
        )
        ->distinct()
        ->join('Mivacuna.UIH_USUARIO_IPS_HABILITACION as b','IHA_ID','=','UIH_IHA_ID')
        ->where('UIH_USU_ID',Auth::user()->USU_ID)
        ->get();

        $tipoContacto = STCO_SEG_TIPO_CONTACTO::orderBy('STCO_NOMBRE')->get();

        $tipoGestion = STGE_SEG_TIPO_GESTION::orderBy('STGE_NOMBRE')
        ->get();

        $estadoSeguimiento = SESE_SEG_ESTADO_SEGUIMIENTO::orderBy('SESE_NOMBRE')
        ->join('MiVacuna.TGEG_SEG_TIPO_GESTION_ESTADO_GESTION','SESE_ID','=','TGEG_ESTADO_GESTION_ID')
        ->get();

        return view('Asistencial.covid19.vacunacionHome',compact('historialCargues','tipoDocumentos','ipsUsuario','tipoContacto','tipoGestion','estadoSeguimiento'));
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
        
        switch ($request->tipoFormulario) {
            case 'individual':
                return $this->getFormularoIndividual($request);
                break;

            case 'agendamientoMasivo':
                return $this->getFormularioAgendamientoMasivo($request);
                break;

            case 'asignacionMasivo':
                return $this->getFormularioAsignacionMasivo($request);
                break;                

            case 'anulacionIndividual':
                return $this->getFormularioAnulacionIndividual($request);
                break;  
                
            case 'BusquedaAnulacionIndividual';
                return $this->getFormularioBusquedaAnulacionIndividual($request);
                break;                      
            
            case 'BusquedaAgendamientos':
                return $this->getFormularioConsultaAgendamiento($request);
                break;

            case 'BusquedaAsignacion':
                return $this->getFormularioConsultaAsignacion($request);
                break;                
            
            default:
                return [];
                break;
        }

    }

    private function getFormularoIndividual(Request $request){
        //$request->session()->forget('tokenMipres');
        $respuestaAgendamiento = $this->putAgendamiento($request->all());

        // Verifica el numero de documento y fecha de agendamiento en la base de datos.
        $idIngreso = $this->getVerificaRegistroByFecha($request);

        if(!$idIngreso){

            $idIngreso = VacunacionRegistro::create([
                'RAG_TIPO_DOCUMENTO_PACIENTE' => $request->TipoIDPaciente,	
                'RAG_NUMERO_DOCUMENTO_PACIENTE' => $request->NoIDPaciente,	
                'RAG_PRIMER_NOMBRE' => $request->PrimerNombre,	
                'RAG_SEGUNDO_NOMBRE' => $request->SegundoNombre,	
                'RAG_PRIMER_APELLIDO' => $request->PrimerApellido,	
                'RAG_SEGUNDO_APELLIDO' => $request->SegundoApellido,	
                'RAG_CODIGO_PRESTADOR' => $request->CodPrestador,	
                'RAG_FECHA_AGENDAMIENTO' => $request->FechaAgendamiento,	
                'RAG_HORA_AGENDAMIENTO' => $request->HoraAgendamiento,	
                'RAG_NUMERO_DOSIS' => $request->NumeroDosis,	
                'RAG_CAUSA_NO_AGENDAMIENTO' => $request->CausaNoAgendamiento,	
                'RAG_OPERACION_AGENDAMIENTO' => 1,	
                'RAG_CARGUE_ID' => 0,
                'RAG_FILA_CARGUE' => 0,
                'RAG_TRA_ID' => 2,
                'RAG_FECHA_REGISTRO' => date('Y-m-d H:i:s')
            ]);
        }

        // Registra la respuesta del Web Service, relacionando el ID del registro final
        $this->setRegistrarLogApi(
            $idIngreso->RAG_ID,
            $idIngreso->RAG_CARGUE_ID,
            $respuestaAgendamiento
        );
        
        // Se verifica si la respuesta contiene un campo ID para comprobar que fue radicado y almacenar la respuesta
        if($respuestaAgendamiento = json_decode($respuestaAgendamiento,true)){
            if(isset($respuestaAgendamiento['ID'])){
                VacunacionRegistro::where('RAG_ID',$idIngreso->RAG_ID)
                ->update([
                    'RAG_RADICADO_ID' => $respuestaAgendamiento['ID'],
                    'RAG_RADICADO_MENSAJE' => $respuestaAgendamiento['Message'],
                    'RAG_FECHA_RADICADO' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return $respuestaAgendamiento;
    }

    private function getFormularioAnulacionIndividual(Request $request){

        // Hace el consumo del servicio en el web service
        $respuestaAgendamiento = $this->putAnularAgendamiento($request->all());

        // Verifica el ID Agendamiento en la base de datos.
        $idIngreso = $this->getVerificaRegistroByIdAgendamiento($request->IDAgendamiento);   

        // Registra la respuesta del Web Service, relacionando el ID del registro final
        $this->setRegistrarLogApi(
            $idIngreso->RAG_ID,
            $idIngreso->RAG_CARGUE_ID,
            $respuestaAgendamiento
        );        

        // Se verifica si la respuesta contiene un campo ID para comprobar que fue radicado y almacenar la respuesta
        if($respuestaAgendamiento = json_decode($respuestaAgendamiento,true)){
            if(isset($respuestaAgendamiento['ID'])){
                VacunacionRegistro::where('RAG_ID',$idIngreso->RAG_ID)
                ->update([
                    'RAG_ANULADO_ID' => $respuestaAgendamiento['ID'],
                    'RAG_ANULADO_MENSAJE' => $respuestaAgendamiento['Message'],
                    'RAG_FECHA_ANULADO' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return $respuestaAgendamiento;
    }

    private function getFormularioBusquedaAnulacionIndividual(Request $request){

        $consulta = VacunacionRegistro::
        select(
            'RAG_PRIMER_NOMBRE',
            'RAG_PRIMER_APELLIDO',
            'RAG_ID',
            'RAG_RADICADO_ID',
            'RAG_ANULADO_ID',
            'RAG_FECHA_RADICADO',
            'RAG_FECHA_ANULADO',
            'RAG_FECHA_AGENDAMIENTO',
            'RAG_HORA_AGENDAMIENTO',
        )
        ->where('RAG_TIPO_DOCUMENTO_PACIENTE',$request->TipoIDPaciente)
        ->where('RAG_NUMERO_DOCUMENTO_PACIENTE',$request->NoIDPaciente)
        ->get();

        return $consulta;
    }

    private function getFormularioAgendamientoMasivo(Request $request){

        $rutaArchivo = $this->setAlmacenarArchivo($request);
        $archivoNombre = $this->getNombreArchivo($request);
        $archivoCargado = $this->setRegistrarCargueArchivo($rutaArchivo,$archivoNombre,'Agendamiento');

        try {
            $insertarExcel =  Excel::import(new VacunacionAgendamientoImport($archivoCargado), $rutaArchivo);

            $validarExcel = DB::update('EXEC MiVacuna.VEVC_VALIDACION_ESTRUCTURA_VACUNACION_COVID '.$archivoCargado.' ');

            $filasCargadas = DB::table('MiVacuna.trag_tmp_registrar_agendamiento')->select(DB::raw('count(TRAG_ID) as cargado'))->where('TRAG_CARGUE_ID',$archivoCargado)->get();

            $filasErradas =  DB::table('MiVacuna.tera_tmp_error_registrar_agendamiento')->select(DB::raw('count(TRAG_ID) as error'))->where('TRAG_CARGUE_ID',$archivoCargado)->get();

            $filasExitosas =  DB::table('MiVacuna.RAG_REGISTRAR_AGENDAMIENTO')->select(DB::raw('count(RAG_ID) as exitoso'))->where('RAG_CARGUE_ID',$archivoCargado)->get();

            return [
                'Mensaje' => ($filasErradas) ? 'Cargue con errores' : 'Cargue exitoso',
                'FilasVerificadas' => ($filasCargadas) ? $filasCargadas[0]->cargado : 0,
                'FilasError' => ($filasErradas) ? $filasErradas[0]->error : 0,
                'FilasExitosas' => ($filasExitosas) ? $filasExitosas[0]->exitoso : 0,
                'DescargarErrores' => ($filasErradas) ? $archivoCargado : '',

            ];

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            
            return $failures = $e->failures();
     
            foreach ($failures as $failure) {
                return $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }

    }

    private function getFormularioAsignacionMasivo(Request $request){

        $rutaArchivo = $this->setAlmacenarArchivo($request);
        $archivoNombre = $this->getNombreArchivo($request);
        $archivoCargado = $this->setRegistrarCargueArchivo($rutaArchivo,$archivoNombre,'Asignacion');

        try {
            $insertarExcel =  Excel::import(new VacunacionAsignacionImport($archivoCargado), $rutaArchivo);

            $validarExcel = DB::update('EXEC MiVacuna.VEVCA_VALIDACION_ESTRUCTURA_VACUNACION_COVID_ASIGNACION '.$archivoCargado.' ');

            $filasCargadas = DB::table('MiVacuna.traa_tmp_registrar_asignacion')->select(DB::raw('count(TRAA_ID) as cargado'))->where('TRAA_CARGUE_ID',$archivoCargado)->get();

            $filasErradas =  DB::table('MiVacuna.tera_tmp_error_registrar_agendamiento')->select(DB::raw('count(TRAG_ID) as error'))->where('TRAG_CARGUE_ID',$archivoCargado)->get();

            $filasExitosas =  DB::table('MiVacuna.RAA_REGISTRAR_ASIGNACION')->select(DB::raw('count(RAA_ID) as exitoso'))->where('RAA_CARGUE_ID',$archivoCargado)->get();

            return [
                'Mensaje' => ($filasErradas) ? 'Cargue con errores' : 'Cargue exitoso',
                'FilasVerificadas' => ($filasCargadas) ? $filasCargadas[0]->cargado : 0,
                'FilasError' => ($filasErradas) ? $filasErradas[0]->error : 0,
                'FilasExitosas' => ($filasExitosas) ? $filasExitosas[0]->exitoso : 0,
                'DescargarErrores' => ($filasErradas) ? $archivoCargado : ''
            ];

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            
            return $failures = $e->failures();
     
            foreach ($failures as $failure) {
                return $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }

    }    

    private function getNombreArchivo(Request $request){
        return $request->file('inputFileAgendamiento')->getClientOriginalName();
    }

    private function setAlmacenarArchivo(Request $request){
        $archivo = $request->file('inputFileAgendamiento');

        return $rutaArchivo = $archivo->storeAs(
            'covid19/vacunacion/user_'.Auth::user()->USU_ID.'',
            str_replace(' ','',$this->getNombreArchivo($request))
        );        
    }

    private function setRegistrarCargueArchivo($rutaArchivo,$archivoNombre,$tipoCargue){
        return $archivoCargado = DB::table('MiVacuna.rcp_registrar_cargue_plano')->insertGetId([
            'RCP_RUTA_ARCHIVO' => $rutaArchivo,	
            'RCP_NOMBRE_ARCHIVO' => $archivoNombre,	
            'RCP_FECHA_CREACION' => date('Y-m-d H:i:s'),	
            'RCP_ID_USER' => Auth::user()->USU_ID,      
            'RCP_TIPO_CARGUE' => $tipoCargue,
            'RCP_ID_ESTADO' => 1
        ]);        
    }

    private function getFormularioConsultaAgendamiento(Request $request){
        
        $consulta = VacunacionRegistro::
        select(
            'RAG_ID AS ID',
            'RAG_TIPO_DOCUMENTO_PACIENTE AS TIPO_DOCUMENTO_PACIENTE',	
            'RAG_NUMERO_DOCUMENTO_PACIENTE AS NUMERO_DOCUMENTO_PACIENTE',
            'RAG_PRIMER_NOMBRE AS PRIMER_NOMBRE',
            'RAG_SEGUNDO_NOMBRE AS SEGUNDO_NOMBRE',
            'RAG_PRIMER_APELLIDO AS PRIMER_APELLIDO',
            'RAG_SEGUNDO_APELLIDO AS SEGUNDO_APELLIDO',
            'RAG_RADICADO_ID AS RADICADO_ID',
            'RAG_ANULADO_ID AS ANULADO_ID',
            DB::raw('cast(RAG_FECHA_REGISTRO as date) AS FECHA'),
            DB::raw('cast(RAG_FECHA_REGISTRO as time(0)) AS HORA'),
            'RAG_CODIGO_PRESTADOR AS CODIGO_PRESTADOR',
            'RAG_RADICADO_MENSAJE AS RADICADO_MENSAJE',
            'RAG_ANULADO_MENSAJE AS ANULADO_MENSAJE'            
        );

        switch($request->tipoBusqueda){
            case 'fecha':
                
                return $consulta
                ->where(DB::RAW('CAST(RAG_FECHA_REGISTRO AS DATE)'),'>=',$request->FechaInicial)  
                ->where(DB::RAW('CAST(RAG_FECHA_REGISTRO AS DATE)'),'<=',$request->FechaFinal)  
                ->join('MiVacuna.RCP_REGISTRAR_CARGUE_PLANO as c',function($ijoin){
                    $ijoin->on('RAG_CARGUE_ID','=','c.RCP_ID');
                    $ijoin->on('c.RCP_ID_USER','=',DB::raw(Auth::user()->USU_ID));
                })                           
                ->get();

                break;
            case 'identificacion':
                return $consulta->where('RAG_TIPO_DOCUMENTO_PACIENTE',$request->TipoIDPaciente)
                ->where('RAG_NUMERO_DOCUMENTO_PACIENTE',$request->NoIDPaciente)
                ->get();                
                break; 
                
            default:
                return [];
                break;
        }

        return [];          
    }

    private function getFormularioConsultaAsignacion(Request $request){
        
        $consulta = vacunacionRegistroAsignacion::
        select(
            'RAA_ID AS ID',	
            'RAA_TIPO_DOCUMENTO_PACIENTE AS TIPO_DOCUMENTO_PACIENTE',	
            'RAA_NUMERO_DOCUMENTO_PACIENTE AS NUMERO_DOCUMENTO_PACIENTE',	
            'RAA_CODIGO_PRESTADOR AS CODIGO_PRESTADOR',	
            DB::raw('cast(RAA_FECHA_REGISTRO as date) AS FECHA'),
            DB::raw('cast(RAA_FECHA_REGISTRO as time(0)) AS HORA'),
            'b.PrimerNombre AS PRIMER_NOMBRE',
            'b.SegundoNombre AS SEGUNDO_NOMBRE',
            'b.PrimerApellido AS PRIMER_APELLIDO',
            'b.SegundoApellido AS SEGUNDO_APELLIDO',
            'RAA_RADICADO_ID AS RADICADO_ID',
            'RAA_ANULADO_ID AS ANULADO_ID',  
            'RAA_RADICADO_MENSAJE AS RADICADO_MENSAJE',
            'RAA_ANULADO_MENSAJE AS ANULADO_MENSAJE'
        )        
        ->leftJoin('Afiliados.AFD_DETALLE_AFILIADOS as b',function($join){
            $join->on('RAA_TIPO_DOCUMENTO_PACIENTE','=','TipDocAfiliado');
            $join->on('RAA_NUMERO_DOCUMENTO_PACIENTE','=','NumDocAfiliado');
        });

        switch($request->tipoBusqueda){
            case 'fecha':
                
                return $consulta
                ->where(DB::RAW('CAST(RAA_FECHA_REGISTRO AS DATE)'),'>=',$request->FechaInicial)  
                ->where(DB::RAW('CAST(RAA_FECHA_REGISTRO AS DATE)'),'<=',$request->FechaFinal)  
                ->join('MiVacuna.RCP_REGISTRAR_CARGUE_PLANO as c',function($ijoin){
                    $ijoin->on('RAA_CARGUE_ID','=','c.RCP_ID');
                    $ijoin->on('c.RCP_ID_USER','=',DB::raw(Auth::user()->USU_ID));
                })                           
                ->get();

                break;
            case 'identificacion':

                return $consulta->where('RAA_TIPO_DOCUMENTO_PACIENTE',$request->TipoIDPaciente)
                ->where('RAA_NUMERO_DOCUMENTO_PACIENTE',$request->NoIDPaciente)
                ->get();                
                break; 
                
            default:
                return [];
                break;
        }

        return [];        
    }    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($tipoOperacion,$id)
    {   
        switch ($tipoOperacion) {
            case 'erroresAsignacion':
                return Excel::download(new VacunacionAsignacionExport($id), 'erroresAsignacion_'.$id.'_'.date('Y_m_d_H_i').'.xlsx');
                break;

            case 'erroresAgendamiento':
                return Excel::download(new VacunacionAgendamientoExport($id), 'erroresAgendamiento_'.$id.'_'.date('Y_m_d_H_i').'.xlsx');
                break;                
            
            default:
                return;
                break;
        }
        
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
        //
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

    private function getVerificaRegistroByFecha(Request $request){
        return VacunacionRegistro::select('RAG_ID')
        ->where('RAG_NUMERO_DOCUMENTO_PACIENTE',$request->NoIDPaciente)
        ->where('RAG_FECHA_AGENDAMIENTO',$request->FechaAgendamiento)
        ->first();
    }

    private function getVerificaRegistroByIdAgendamiento($idAgendamiento=null){
        return VacunacionRegistro::select('RAG_ID','RAG_CARGUE_ID')
        ->where('RAG_RADICADO_ID',$idAgendamiento)
        ->first();
    }  
    
    private function setRegistrarLogApi($idRegistro=0, $idCargue=0, $respuestaAgendamiento=[]){
        return VacunacionRegistoLogAgendamiento::create([    
            'RLA_RAG_ID' => $idRegistro,	
            'RLA_RCP_ID' => $idCargue,
            'RLA_REQUEST' => $respuestaAgendamiento,	
            'RLA_ID_USER' => Auth::user()->USU_ID,	
            'RLA_CREATED_AT' => date('Y-m-d H:i:s'),
        ]);
    }

}
