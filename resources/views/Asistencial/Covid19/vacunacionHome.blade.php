<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
<script type="text/javascript" src="js/librerias/ValidacionArchivo.js"></script>

@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-lg-3 col-md-6 col-sm-12">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item btn-block" role="presentation">
                  <a class="nav-link active" id="agendamiento_-tab" data-toggle="tab" href="#agendamiento_" role="tab" aria-controls="agendamiento_" aria-selected="true">Agendamiento</a>
                </li>
                <li class="nav-item btn-block" role="presentation">
                  <a class="nav-link" id="asignacion_-tab" data-toggle="tab" href="#asignacion_" role="tab" aria-controls="asignacion_" aria-selected="false">Asignacion</a>
                </li>
                <li class="nav-item btn-block" role="presentation">
                  <a class="nav-link" id="seguimiento-tab" data-toggle="tab" href="#seguimiento" role="tab" aria-controls="seguimiento" aria-selected="false">Seguimiento</a>
                </li>
              </ul>
              <div class="tab-content mt-1 mb-3" id="myTabContent">
                <div class="tab-pane fade show active" id="agendamiento_" role="tabpanel" aria-labelledby="agendamiento_-tab">
                    <div class="list-group" id="list-tab1" role="tablist">
                        <a class="list-group-item list-group-item-action" id="list-CargarPlano-list" data-toggle="list" href="#list-CargarPlano" role="tab" aria-controls="CargarPlano"><i class="fas fa-upload"></i> Agendamiento - Masivo</a>
                        <a class="list-group-item list-group-item-action" id="list-RegistrarAgendamiento-list" data-toggle="list" href="#list-RegistrarAgendamiento" role="tab" aria-controls="RegistrarAgendamiento"><i class="far fa-paper-plane"></i> Agendamiento creación - Individual</a>
                        <a class="list-group-item list-group-item-action" id="list-RegistrarAgendamientoAnulacion-list" data-toggle="list" href="#list-RegistrarAgendamientoAnulacion" role="tab" aria-controls="RegistrarAgendamientoAnulacion"><i class="far fa-paper-plane"></i> Agendamiento anulación - Individual</a>
                        <a class="list-group-item list-group-item-action" id="list-ConsultaAgendamiento-list" data-toggle="list" href="#list-ConsultaAgendamiento" role="tab" aria-controls="ConsultaAgendamiento"><i class="fas fa-search"></i> Consulta Agendamiento</a>
                    </div>
                </div>
                <div class="tab-pane fade" id="asignacion_" role="tabpanel" aria-labelledby="asignacion_-tab">
                    <div class="list-group" id="list-tab2" role="tablist">
                        <a class="list-group-item list-group-item-action" id="list-CargarPlanoAsignacion-list" data-toggle="list" href="#list-CargarPlanoAsignacion" role="tab" aria-controls="CargarPlanoAsignacion"><i class="fas fa-upload"></i> Asignación - Masivo</a>
                        <a class="list-group-item list-group-item-action d-none" id="list-RegistrarAsignacion-list" data-toggle="list" href="#list-RegistrarAsignacion" role="tab" aria-controls="RegistrarAsignacion"><i class="far fa-paper-plane"></i> Asignación creación - Individual</a>
                        <a class="list-group-item list-group-item-action d-none" id="list-RegistrarAsignacionAnulacion-list" data-toggle="list" href="#list-RegistrarAsignacionAnulacion" role="tab" aria-controls="RegistrarAsignacionAnulacion"><i class="far fa-paper-plane"></i> Asignación anulación - Individual</a>                        
                        <a class="list-group-item list-group-item-action" id="list-ConsultaAsignacion-list" data-toggle="list" href="#list-ConsultaAsignacion" role="tab" aria-controls="ConsultaAgendamiento"><i class="fas fa-search"></i> Consulta Asignación</a>
                    </div>                    
                </div>
                <div class="tab-pane fade" id="seguimiento" role="tabpanel" aria-labelledby="seguimiento-tab">
                    <div class="list-group" id="list-tab3" role="tablist">
                        <a class="list-group-item list-group-item-action" id="list-GestionPostulantes-list" data-toggle="list" href="#list-GestionPostulantes" role="tab" aria-controls="GestionPostulantes">Postulantes</a>
                        <a class="list-group-item list-group-item-action" id="list-GestionSegVacunacion-list" data-toggle="list" href="#list-GestionSegVacunacion" role="tab" aria-controls="GestionSegVacunacion">Seguimiento vacunación</a>
                    </div>                    
                </div>
              </div>            

        </div>

        <div class="col-lg-9 col-md-9 col-sm-12">
            <div class="tab-content card p-4" id="nav-tabContent">

                {{-- Agendamiento --}}
                <div class="tab-pane fade" id="list-CargarPlano" role="tabpanel" aria-labelledby="list-CargarPlano-list">
                    <h3>
                        Agendamiento - Masivo  
                        <small>
                            <a href="documentos/covid19/Estructura_Cargue_Masivo_Agendamiento_covid.xlsx" download>
                                <i class="fas fa-download"></i> Descargar estructura
                            </a>
                        </small>
                    </h3>
                    <hr>
                    <formulario-vacunacioncovidcargue :historialcargues='{{ json_encode($historialCargues) }}'>
                    </formulario-vacunacioncovidcargue>
                </div>
                <div class="tab-pane fade" id="list-RegistrarAgendamiento" role="tabpanel" aria-labelledby="list-RegistrarAgendamiento-list">
                    <h3>Agendamiento creación - individual</h3>
                    <hr>
                    <formulario-vacunacioncovid :tipodocumentos='{{ json_encode($tipoDocumentos) }}' :ipsusuario='{{ json_encode($ipsUsuario) }}'></formulario-vacunacioncovid>
                </div>
                <div class="tab-pane fade" id="list-RegistrarAgendamientoAnulacion" role="tabpanel" aria-labelledby="list-RegistrarAgendamientoAnulacion-list">
                    <h3>Agendamiento anulación - individual</h3>
                    <hr>
                    <formulario-vacunacioncovidanulacion :tipodocumentos='{{ json_encode($tipoDocumentos) }}'></formulario-vacunacioncovidanulacion>
                </div>
                <div class="tab-pane fade" id="list-ConsultaAgendamiento" role="tabpanel" aria-labelledby="list-ConsultaAgendamiento-list">
                    <h3>Consulta agendamiento</h3>
                    <hr>
                    <formulario-vacunacioncovidconsulta :tipodocumentos='{{ json_encode($tipoDocumentos) }}' :tipoformulario="'BusquedaAgendamientos'"></formulario-vacunacioncovidconsulta>
                </div>
                {{-- Agendamiento --}}  


                {{-- Asignacion --}}
                <div class="tab-pane fade" id="list-CargarPlanoAsignacion" role="tabpanel" aria-labelledby="list-CargarPlanoAsignacion-list">
                    <h3>
                        Asignación - Masivo 
                        <small>
                            <a href="documentos/covid19/Estructura_Cargue_Masivo_Asignacion_covid.xlsx" download>
                                <i class="fas fa-download"></i> Descargar estructura
                            </a>
                        </small>
                    </h3>
                    <hr>
                    <formulario-vacunacioncovidcargueasignacion 
                        :historialcargues='{{ json_encode($historialCargues) }}'>
                    </formulario-vacunacioncovidcargueasignacion>
                </div>                
                <div class="tab-pane fade" id="list-RegistrarAsignacion" role="tabpanel" aria-labelledby="list-RegistrarAgendamiento-list">
                    <h3>Asignación creación - individual</h3>
                    <hr>
                    registrar asignacion
                </div>
                <div class="tab-pane fade" id="list-RegistrarAsignacionAnulacion" role="tabpanel" aria-labelledby="list-RegistrarAgendamientoAnulacion-list">
                    <h3>Asignación anulación - individual</h3>
                    <hr>
                    registrar anulación
                </div>   
                <div class="tab-pane fade" id="list-ConsultaAsignacion" role="tabpanel" aria-labelledby="list-ConsultaAsignacion-list">
                    <h3>Consulta asignación</h3>
                    <hr>
                    <formulario-vacunacioncovidconsulta 
                        :tipodocumentos='{{ json_encode($tipoDocumentos) }}' 
                        :tipoformulario="'BusquedaAsignacion'">
                    </formulario-vacunacioncovidconsulta>
                </div>                 
                {{-- Fin Asignacion --}}            
                          
                
                {{-- Postulantes --}}
                <div class="tab-pane fade" id="list-GestionPostulantes" role="tabpanel" aria-labelledby="list-GestionPostulantes-list">
                    <h3>Ingrese la información solicitada de los postulantes</h3>
                    <hr>
                    Postulantes
                </div>   
                {{-- Fin Postulantes --}} 
                
                {{-- Postulantes --}}
                <div class="tab-pane fade" id="list-GestionSegVacunacion" role="tabpanel" aria-labelledby="list-GestionSegVacunacion-list">
                    <h3>Seguimiento de vacunación</h3>
                    <hr>
                    <formulario-vacunacionseguimiento 
                        :url='{{ json_encode(Request::root()) }}'
                        :tipodocumentos='{{ json_encode($tipoDocumentos) }}' 
                        :tipocontacto='{{ json_encode($tipoContacto) }}'
                        :tipogestion='{{ json_encode($tipoGestion) }}'
                        :estadoseguimiento='{{ json_encode($estadoSeguimiento) }}'
                        >
                    </formulario-vacunacionseguimiento>
                </div>   
                {{-- Fin Postulantes --}}      

            </div>
        </div>
  </div>

@endsection