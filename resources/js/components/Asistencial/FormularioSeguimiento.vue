<template>
    <div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    NUEVO SEGUIMIENTO 
                    <strong> {{ informacionAfiliado.PrimerNombre }}
                    {{ informacionAfiliado.SegundoNombre }}
                    {{ informacionAfiliado.PrimerApellido }}
                    {{ informacionAfiliado.SegundoApellido }} </strong>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" class="form-group">
                    <div class="row">

                        <div class="col-lg-4 col-md-12 col-sm-12 form-group">
                          <label for="tipoContacto">Tipo de contacto <span class="text-danger">*</span></label>
                          <select class="form-control" name="tipoContacto" id="tipoContacto" v-model="seguimientoAfiliado.seguimientoTipoContato">
                            <option value="" selected disabled>Seleccione</option>
                            <option v-for="(contacto, index) in tipocontacto" :key="index" :value="contacto.STCO_ID">{{contacto.STCO_NOMBRE}}</option>
                          </select>
                        </div>

                        <div class="col-lg-4 col-md-12 form-group">
                          <label for="tipoGestion">Tipo de gestión <span class="text-danger">*</span></label>
                          <select class="form-control" name="tipoGestion" id="tipoGestion" v-model="seguimientoAfiliado.seguimientoTipoGestion" @change="seguimientoAfiliado.seguimientoEstadoSeguimiento = ''">
                            <option value="" selected disabled>Seleccione</option>
                            <option v-for="(gestion, index) in tipogestion" :key="index" :value="gestion.STGE_ID">{{gestion.STGE_NOMBRE}}</option>
                          </select>
                        </div>                        

                        <div class="col-lg-4 col-md-12 form-group">
                          <label for="estadoGestion">Estado de la gestión <span class="text-danger">*</span></label>
                          <select class="form-control" name="estadoGestion" id="estadoGestion" v-model="seguimientoAfiliado.seguimientoEstadoSeguimiento">
                            <option value="" selected disabled>Seleccione</option>
                            <option v-for="(seguimiento, index) in estadoseguimiento.filter((f) => f.TGEG_TIPO_GESTION_ID == seguimientoAfiliado.seguimientoTipoGestion)" :key="index" :value="seguimiento">{{seguimiento.SESE_NOMBRE}}</option>
                          </select>
                        </div>

                        <div class="col-md-12 form-group">
                          <label for="observacionSeguimiento">Observacion de la gestión <span class="text-danger" v-show="seguimientoAfiliado.seguimientoEstadoSeguimiento.TGEC_OBSERVACION_REQUERIDA == 'Y'">*</span></label>
                          <textarea class="form-control" name="observacionSeguimiento" id="observacionSeguimiento" rows="5" v-model="seguimientoAfiliado.seguimientoObservacionGestion"></textarea>
                        </div>

                        <div class="container">
                            <h3>{{ respuesta }}</h3>
                            <h3>{{ respuestaError }}</h3>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" @click="guardarSeguimiento()" v-if="camposObligatorios && !seguimientoAfiliado.idSeguimiento">Guardar</button>
                <button type="button" class="btn btn-primary" @click="actualizarSeguimiento()" v-if="camposObligatorios && seguimientoAfiliado.idSeguimiento">Actualizar</button>
                <button  disabled="disabled" type="button" class="btn btn-primary" v-if="!camposObligatorios">Complete todos los campos requeridos</button>
            </div>
            </div>
        </div>
        </div>     

        <div class="form-row" v-if="!afiliadoEncontrado">
            <div class="form-group col-md-6 col-sm-12">
                <label for="TipoIDPaciente">Tipo de documento</label>
                <select name="TipoIDPaciente" v-model="TipoIDPaciente" class="form-control">
                    <option value="" selected disabled>Seleccione</option>
                    <option v-for="tipodocumento of tipodocumentos" :key="tipodocumento.TDO_HOMOLOGACION2" :value="tipodocumento.TDO_HOMOLOGACION2">{{ tipodocumento.TDO_HOMOLOGACION2 }} {{ tipodocumento.TDO_DESCRIPCION }}</option>
                </select> 
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="NoIDPaciente">Número de documento</label>
                <input type="text" class="form-control" name="NoIDPaciente" v-model="NoIDPaciente" @blur="consultaAfiliado()">
            </div>
            <div class="form-group col-md-12" v-if="!afiliadoEncontrado && cargando">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Procesado información, por favor espere...
            </div>
        </div>         

        <div class="row" v-else>

            <div class="col-md-6">
                <a href="#" title="Consultar otro numero de identificacion" @click="nuevaConsulta()">
                    <i class="fas fa-arrow-circle-left fa-2x"></i> Nueva consulta
                </a>
            </div>

            <div class="col-md-6 text-right">
                <a href="#" title="Crea nuevo seguimiento" data-toggle="modal" data-target="#staticBackdrop">
                    <i class="fas fa-plus fa-2x"></i> Nuevo seguimiento
                </a>
            </div>

            <div class="container mt-2 mb-2"></div>

            <div class="col-md-6 col-sm-12 form-group">
                <label for="tipoIdentificacion">Tipo identificación</label>
                <input class="form-control" type="text" id="tipoIdentificacion" name="tipoIdentificacion" v-model="informacionAfiliado.TipDocAfiliado" readonly>
            </div>
            <div class="col-md-6 col-sm-12 form-group">
                <label for="numeroIdentificacion">Numero identificación</label>
                <input class="form-control" type="text" id="numeroIdentificacion" name="numeroIdentificacion" v-model="informacionAfiliado.NumDocAfiliado" readonly>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="primerNombre">Primer Nombre</label>
                <input class="form-control" type="text" id="primerNombre" name="primerNombre" v-model="informacionAfiliado.PrimerNombre" readonly>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="SegundoNombre">Segundo Nombre</label>
                <input class="form-control" type="text" id="SegundoNombre" name="SegundoNombre" v-model="informacionAfiliado.SegundoNombre" readonly>                
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="PrimerApellido">Primer Apellido</label>
                <input class="form-control" type="text" id="PrimerApellido" name="PrimerApellido" v-model="informacionAfiliado.PrimerApellido" readonly>                
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="SegundoApellido">Segundo Apellido</label>
                <input class="form-control" type="text" id="SegundoApellido" name="SegundoApellido" v-model="informacionAfiliado.SegundoApellido" readonly>                
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="fechaNacimiento">Fecha Nacimiento</label>
                <input class="form-control" type="text" id="fechaNacimiento" name="fechaNacimiento" v-model="informacionAfiliado.FechaNacimiento" readonly>                
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="Edad">Edad</label>
                <input class="form-control" type="text" id="Edad" name="Edad" v-model="informacionAfiliado.Edad" readonly>                
            </div>                        
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="Genero">Género</label>
                <input class="form-control" type="text" id="Genero" name="Genero" v-model="informacionAfiliado.Genero" readonly>                
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="nombreIPS">IPS</label>
                <input class="form-control" type="text" id="nombreIPS" name="nombreIPS" v-model="informacionAfiliado.nombreIPS" readonly>                
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="estadoActual">Estado Actual</label>
                <input class="form-control" type="text" id="estadoActual" name="estadoActual" v-model="informacionAfiliado.EstadoHabilitar" readonly>                
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="departamento">Departamento</label>
                <input class="form-control" type="text" id="departamento" name="departamento" v-model="informacionAfiliado.DepartamentoResidencia" readonly>                
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="municipio">Municipio</label>
                <input class="form-control" type="text" id="municipio" name="municipio" v-model="informacionAfiliado.MunicipioResidencia" readonly>                
            </div>                        
            <div class="col-lg-3 col-md-6 col-sm-12 form-group">
                <label for="estadoSeguimiento">Estado Seguimiento</label>
                <input class="form-control" type="text" id="estadoSeguimiento" name="estadoSeguimiento" v-model="informacionAfiliado.ESTADO_SEGUIMIENTO" readonly>                
            </div>


            <div class="accordion col-md-12" id="accordionExample">
                <div class="card">
                    <div class="card-header bg-primary" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left text-white" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           <i class="fas fa-caret-down"></i> HISTORICO SEGUIMIENTOS
                        </button>
                    </h2>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="container mt-2 mb-2">
                            <h3 class="text-center">Historico de seguimientos</h3>
                        </div>

                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Contacto</th>
                                    <th>Gestion</th>
                                    <th>Estado</th>
                                    <th>Usuario</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="seguimiento in historicoSeguimientos" :key="seguimiento.SRSE_ID">
                                    <td>
                                        <i v-show="seguimiento.CREADO_HOY === 'SI'" class="fas fa-asterisk text-primary"></i>
                                    </td>
                                    <td>{{ seguimiento.SRSE_ID }}</td>
                                    <td>{{ seguimiento.SRSE_FECHA_CREACION.substring(1,15) }}</td>
                                    <td>{{ seguimiento.TIPO_CONTACTO }}</td>
                                    <td>{{ seguimiento.TIPO_GESTION }}</td>
                                    <td>{{ seguimiento.ESTADO_SEGUIMIENTO }}</td>
                                    <td>{{ seguimiento.USU_USERNAME }}</td>
                                    <td>{{ seguimiento.SRSE_OBSERVACION }}</td>
                                </tr>
                            </tbody>
                        </table>                         
                    </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-warning" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed text-white" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                           <i class="fas fa-caret-down"></i> PAI WEB
                        </button>
                    </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="container mt-2 mb-2">
                            <h3 class="text-center">Historico de vacunación PAI WEB</h3>
                        </div>

                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Tipo Contacto</th>
                                    <th>Tipo Gestion</th>
                                    <th>Usuario Gestión</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                    </div>
                </div>
            </div>        
            
        </div>
        
    </div>
</template>
<script>
export default {
    props: ['tipodocumentos','tipocontacto','tipogestion','estadoseguimiento','url'],
    data(){
        return {
            TipoIDPaciente: '',
            NoIDPaciente: '',
            afiliadoEncontrado: false,
            cargando: false,   
            respuesta: '',
            respuestaError: '',  
            informacionAfiliado: {},
            seguimientoAfiliado: {
                idSeguimiento: 0,
                seguimientoTipoContato: '',
                seguimientoTipoGestion: '',
                seguimientoEstadoSeguimiento: '',
                seguimientoObservacionGestion: '',
            },
            historicoSeguimientos: [],
            historicoPaiWeb: []
        }
    },
    methods:{
        consultaAfiliado(){

            this.afiliadoEncontrado = false;
            this.respuesta='';
            this.respuestaError='';

            if(!this.TipoIDPaciente.length || !this.NoIDPaciente.length){
                return this.respuestaError = 'Los campos tipo y número de documento se deben completar';
            }  

            this.cargando=true;

            let formCargue = new FormData();
            formCargue.append('TipDocAfiliado',this.TipoIDPaciente);
            formCargue.append('NumDocAfiliado',this.NoIDPaciente);

            axios.post('api/Afiliados/busquedaSeguimientoCovidXTipoNumero',formCargue)
            .then(response => {
                
                const data = response.data;

                if(data.afiliado){
                    this.informacionAfiliado = data.afiliado;
                    this.historicoSeguimientos = data.seguimientos;
                    this.afiliadoEncontrado = true;
                }else{
                    this.afiliadoEncontrado = false;
                }

                this.cargando = false;
            })            
            .catch(error => {
                this.respuestaError = error;
                this.cargando = false;
            });            
        },
        nuevaConsulta(){
            this.afiliadoEncontrado = false;
            this.informacionAfiliado = {};
        },
        limpiarCampos(){
            this.seguimientoAfiliado = {
                idSeguimiento: 0,
                seguimientoTipoContato: '',
                seguimientoTipoGestion: '',
                seguimientoEstadoSeguimiento: '',
                seguimientoObservacionGestion: '',                
            }

            setTimeout(() => {
                this.respuesta = '';
                this.respuestaError = '';
            },10000);
        },
        guardarSeguimiento(){

            this.respuesta = '';
            this.respuestaError = '';
            this.cargando = true;
            const datos = { ...this.seguimientoAfiliado, ...this.informacionAfiliado };

            axios.post(this.url+'/covid-vacunacion-seguimiento',datos)
            .then(resp => {
                this.cargando = false;
                this.respuesta = resp.data.mensaje;
                if(resp.data.seguimientos){
                    this.historicoSeguimientos = resp.data.seguimientos;
                }
                this.limpiarCampos();
            })
            .catch(error => {
                this.respuestaError = error;
                this.respuesta = resp.data.mensaje;
                this.cargando = false;
            });

        },
        actualizarSeguimiento(){

            this.respuesta = '';
            this.respuestaError = '';
            this.cargando = true;
            const datos = { ...this.seguimientoAfiliado, ...this.informacionAfiliado };

            axios.put(this.url+'/covid-vacunacion-seguimiento/'+this.seguimientoAfiliado.idSeguimiento,datos)
            .then(resp => {
                this.cargando = false;
                this.respuesta = resp.data.mensaje;
                if(resp.data.seguimientos){
                    this.historicoSeguimientos = resp.data.seguimientos;
                }     
                this.limpiarCampos();           
            })
            .catch(error => {
                this.respuestaError = error;
                this.respuesta = resp.data.mensaje;
                this.cargando = false;
            });

        },        
    },
    computed: {
        camposObligatorios(){
            if(this.seguimientoAfiliado.seguimientoTipoContato && 
                this.seguimientoAfiliado.seguimientoTipoGestion &&
                this.seguimientoAfiliado.seguimientoEstadoSeguimiento)
            {
                
                if(this.seguimientoAfiliado.seguimientoEstadoSeguimiento.TGEC_OBSERVACION_REQUERIDA == 'Y' && !this.seguimientoAfiliado.seguimientoObservacionGestion){
                    return false;
                }

                return true;
            }

            return false;
        }
    }
}
</script>