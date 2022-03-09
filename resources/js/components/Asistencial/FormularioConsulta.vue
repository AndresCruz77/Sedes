<template>
    <div>
        <form :id="'form'+tipoformulario">

            <div class="form-group">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="First group">
                        <button type="button" class="btn btn-outline-primary btn-group" @click="setTipoBusqueda('fecha')">Busqueda Por fecha</button>
                        <button type="button" class="btn btn-outline-primary btn-group" @click="setTipoBusqueda('identificacion')">Busqueda Por Identificación</button>
                    </div>  
                </div>                                
            </div>

            <div class="form-row" v-if="tipoBusqueda=='fecha'">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="FechaInicial">Fecha Inicial</label>
                    <input type="date" class="form-control" name="FechaInicial" v-model="FechaInicial">
                </div>            

                <div class="form-group col-md-6 col-sm-12">
                    <label for="FechaFinal">Fecha Final</label>
                    <input type="date" class="form-control" name="FechaFinal" v-model="FechaFinal">
                </div>                               
            </div> 

            <div class="form-row" v-if="tipoBusqueda=='identificacion'">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="TipoIDPaciente">Tipo de documento</label>
                    <select name="TipoIDPaciente" v-model="TipoIDPaciente" class="form-control">
                        <option value="" selected disabled>Seleccione</option>
                        <option v-for="tipodocumento of tipodocumentos" :key="tipodocumento.TDO_HOMOLOGACION2" :value="tipodocumento.TDO_HOMOLOGACION2">{{ tipodocumento.TDO_HOMOLOGACION2 }} {{ tipodocumento.TDO_DESCRIPCION }}</option>
                    </select> 
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="NoIDPaciente">Número de documento</label>
                    <input type="text" class="form-control" name="NoIDPaciente" v-model="NoIDPaciente">
                </div>
            </div>                                                
   
            <div class="form-row">
                <div class="form-group col-md-12 col-sm-12">
                    <button class="btn btn-primary btn-block" :disabled="!camposObligatorios" type="button" v-if="!cargando" @click="ConsultaFormulario">
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
            <div class="table-responsive"> 
                <table class="table table-hover" :id="'table'+this.tipoformulario">
                    <thead>
                        <tr>
                            <th scope="col">Tip Iden</th>
                            <th scope="col">Identificación</th>
                            <th scope="col">Nombres</th>
                            <th scope="col">Fecha Reg</th>
                            <th scope="col">ID {{ (tipoformulario == 'Busquedasignacion') ? 'Asignación' : 'Agendamiento' }}</th>
                            <th scope="col">ID Anulado</th>
                            <th scope="col">Mensaje radicado</th>
                            <th scope="col">Mensaje anulado</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        <tr v-for="agendamiento of listaAgendamientos" :key="agendamiento.ID">
                        <td>{{ agendamiento.ID }}</td>
                        <td>{{ agendamiento.TIPO_DOCUMENTO_PACIENTE }}</td>
                        <td>{{ agendamiento.NUMERO_DOCUMENTO_PACIENTE }}</td>
                        <td>{{ agendamiento.PRIMER_NOMBRE }} {{ agendamiento.SEGUNDO_NOMBRE }} {{ agendamiento.PRIMER_APELLIDO }} {{ agendamiento.SEGUNDO_APELLIDO }}</td>
                        <td>{{ agendamiento.FECHA }} {{ agendamiento.HORA }}</td>
                        <td>{{ agendamiento.RADICADO_ID }}</td>
                        <td>{{ agendamiento.ANULADO_ID }}</td>
                        </tr> 
                    </tbody> -->
                </table>               
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
    props: ['tipodocumentos','tipoformulario'],
    data() {
        return {
            cargando: false,
            respuesta: '',
            respuestaError: '',            
            FechaInicial: '',
            FechaFinal: '',  
            listaAgendamientos: [],
            tipoBusqueda: '',
            TipoIDPaciente: '',
            NoIDPaciente: '',
        }
    },
    methods: {
        ConsultaFormulario(){
            this.cargando=true;
            this.respuesta='';
            this.respuestaError='';            
            const form = document.getElementById('form'+this.tipoformulario);
            let formCargue = new FormData(form);
            formCargue.append('tipoFormulario',this.tipoformulario);
            formCargue.append('tipoBusqueda',this.tipoBusqueda);

            axios.post('covid-vacunacion',formCargue)
            .then(response => {
                this.listaAgendamientos = response.data;
                this.cargando = false;
                $('#table'+this.tipoformulario).DataTable({
                    destroy: true,
                    dom: 'Bfrtip',
                    data: response.data,
                    buttons: [
                        {
                            extend: 'excel',
                            title: this.tipoformulario+' '+new Date(),
                            className: 'btn btn-warning',
                            text: 'Exportar a Excel'
                        },
                        {
                            extend: 'csv',
                            title: this.tipoformulario+' '+new Date(),
                            className: 'btn btn-warning',
                            text: 'Exportar a CSV'
                        },                        
                    ],
                    columns: [
                        { data: 'TIPO_DOCUMENTO_PACIENTE' },
                        { data: 'NUMERO_DOCUMENTO_PACIENTE' },
                        { data: null, render: function ( data, type, row ) {
                            // Combine the first and last names into a single table field
                            return data.PRIMER_NOMBRE+' '+data.SEGUNDO_NOMBRE+' '+data.PRIMER_APELLIDO+' '+data.SEGUNDO_APELLIDO;
                        } },                        
                        { data: 'FECHA' },
                        { data: 'RADICADO_ID' },
                        { data: 'ANULADO_ID' },
                        { data: 'RADICADO_MENSAJE' },
                        { data: 'ANULADO_MENSAJE' },
                    ]
                });
            })            
            .catch(error => {
                this.cargando = false;
                this.respuestaError = error;
            });            
        },
        limpiarCampos(){
            this.FechaInicial = '';
            this.FechaFinal = '';
            this.TipoIDPaciente = '';
            this.NoIDPaciente = '';
        },
        setTipoBusqueda(tipoBusqueda){
            this.limpiarCampos();
            this.tipoBusqueda = tipoBusqueda;
        }
    },
    computed: {
        camposObligatorios(){
           if(this.tipoBusqueda == 'fecha' && this.FechaInicial && this.FechaFinal){
                return true;
           }else if(this.tipoBusqueda == 'identificacion' && this.TipoIDPaciente && this.NoIDPaciente){
                return true;
           }else{
               return false;
           }
        }
    }
}
</script>