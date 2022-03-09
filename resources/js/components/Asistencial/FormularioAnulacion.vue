<template>
    <div>
        <form id="formAnulacion">

            <div class="form-row">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="TipoIDPaciente">Tipo de documento</label>
                    <select name="TipoIDPaciente" v-model="TipoIDPaciente" class="form-control">
                        <option value="" selected disabled>Seleccione</option>
                        <option v-for="tipodocumento of tipodocumentos" :key="tipodocumento.TDO_HOMOLOGACION2" :value="tipodocumento.TDO_HOMOLOGACION2">{{ tipodocumento.TDO_HOMOLOGACION2 }} {{ tipodocumento.TDO_DESCRIPCION }}</option>
                    </select> 
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="NoIDPaciente">Número de documento</label>
                    <input type="text" class="form-control" name="NoIDPaciente" v-model="NoIDPaciente" @change="listaAgendamientos = []">
                </div>               
            </div>                        
   
            <div class="form-row">
                <div class="form-group col-md-12 col-sm-12">
                    <button class="btn btn-primary btn-block" :disabled="!camposObligatorios" type="button" v-if="!cargando" @click="busquedaFormulario">
                        <span v-if="camposObligatorios">Consultar datos</span>
                        <span v-else>Complete todos los campos</span>
                    </button>
                    <button class="btn btn-primary btn-block" type="button" disabled v-else>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Procesado información, por favor espere...
                    </button>                     
                </div> 
            </div>                     
               
        </form>

        <div class="col-md-12">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action" v-for="agendamiento of listaAgendamientos" :key="agendamiento.RAG_ID">

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioAgendamiento" 
                            :id="agendamiento.RAG_RADICADO_ID" 
                            :value="agendamiento.RAG_RADICADO_ID" 
                            v-model="IDAgendamiento"
                            :disabled="agendamiento.RAG_ANULADO_ID > 0"
                        >
                        <label class="form-check-label" :for="agendamiento.RAG_RADICADO_ID">

                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    {{ agendamiento.RAG_PRIMER_NOMBRE }} {{ agendamiento.RAG_PRIMER_APELLIDO }}  
                                    <small v-if="agendamiento.RAG_ANULADO_ID > 0" class="text-danger">(Agendamiento anulado.) </small>
                                </h5>
                                <small>
                                    <a class="nav-link" href="#messageR" @click="envioAnulacion()" v-if="IDAgendamiento === agendamiento.RAG_RADICADO_ID && !cargandoAnulacion">
                                        <i class="far fa-paper-plane fa-2x"></i>
                                    </a>
                                    <a class="nav-link" href="#messageR" v-else-if="IDAgendamiento === agendamiento.RAG_RADICADO_ID && cargandoAnulacion">
                                        <i class="fa fa-circle-notch fa-w-16 fa-spin fa-2x"></i>
                                    </a>                                    
                                </small>
                            </div>  
                            <p class="mb-1">Fecha y hora agendamiento: {{ agendamiento.RAG_FECHA_AGENDAMIENTO }} {{ agendamiento.RAG_HORA_AGENDAMIENTO }}</p>
                            <small>ID Agendamiento: {{ agendamiento.RAG_RADICADO_ID }}</small>
                            
                        </label>    
                    </div>                       
                </a>              
            </div>           
        </div>

        <div class="col-md-12 mt-3" id="messageR">
            <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="respuestaError">
                <strong>!Error!,</strong> {{ respuestaError }}.
            </div> 
            <div class="alert alert-success alert-dismissible fade show" role="alert" v-else-if="respuesta">
                <strong>!Mensaje!,</strong> {{ respuesta }}.
            </div>              
        </div>

    </div>
</template>

<script>
export default {
    props: ['tipodocumentos'],
    data() {
        return {
            cargando: false,
            cargandoAnulacion: false,
            respuesta: '',
            respuestaError: '',            
            TipoIDPaciente: '',
            NoIDPaciente: '',  
            IDAgendamiento: '', 
            listaAgendamientos: []      
        }
    },
    methods: {
        busquedaFormulario(){
            this.cargando=true;
            this.respuesta='';
            this.respuestaError='';            
            const form = document.getElementById('formAnulacion');
            let formCargue = new FormData(form);
            formCargue.append('tipoFormulario','BusquedaAnulacionIndividual');

            axios.post('covid-vacunacion',formCargue)
            .then(response => {
                this.listaAgendamientos = response.data;
                this.cargando = false;
            })            
            .catch(error => {
                this.cargando = false;
            });            
        },        
        envioAnulacion(){
            
            this.respuesta='';
            this.respuestaError='';   
            
            var r = confirm("¿esta seguro de anular el agendamiento del paciente?");
            if (r == false) {
                return;
            }    
            
            this.cargandoAnulacion=true;

            const form = document.getElementById('formAnulacion');
            let formCargue = new FormData(form);
            formCargue.append('IDAgendamiento',this.IDAgendamiento);
            formCargue.append('tipoFormulario','anulacionIndividual');

            axios.post('covid-vacunacion',formCargue)
            .then(response => {
                this.respuesta = response.data;
                this.cargandoAnulacion = false;
            })            
            .catch(error => {
                this.respuestaError = error;
                this.cargandoAnulacion = false;
            });            
        }
    },
    computed: {
        camposObligatorios(){
           if(this.TipoIDPaciente && this.NoIDPaciente){
               return true
           }
        }
    }
}
</script>