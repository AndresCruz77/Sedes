<template>
    <div>
        <form id="formAgendamiento" action="javascript:void(0)">

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
                    <input type="text" class="form-control" name="NoIDPaciente" v-model="NoIDPaciente" @blur="consultaAfiliado()">
                </div>
                <div class="form-group col-md-12" v-if="!afiliadoEncontrado && cargando">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Procesado información, por favor espere...
                </div>
            </div>                        

            <div class="form-row" v-if="afiliadoEncontrado">
                <div class="form-group col-md-3 col-sm-12">
                    <label for="PrimerNombre">Primer Nombre</label>
                    <input type="text" class="form-control" name="PrimerNombre" v-model="PrimerNombre" readonly>
                </div> 

                <div class="form-group col-md-3 col-sm-12">
                    <label for="SegundoNombre">Segundo Nombre</label>
                    <input type="text" class="form-control" name="SegundoNombre" v-model="SegundoNombre" readonly>
                </div>            
                
                <div class="form-group col-md-3 col-sm-12">
                    <label for="PrimerApellido">Primer Apellido</label>
                    <input type="text" class="form-control" name="PrimerApellido" v-model="PrimerApellido" readonly>
                </div>   
                    
                <div class="form-group col-md-3 col-sm-12">
                    <label for="SegundoApellido">Segundo Apellido</label>
                    <input type="text" class="form-control" name="SegundoApellido" v-model="SegundoApellido" readonly>
                </div>                              

                <div class="form-group col-md-6 col-sm-12">
                    <label for="CausaNoAgendamiento">Causa no Agendamiento</label>
                    <select name="CausaNoAgendamiento" v-model="CausaNoAgendamiento" class="form-control" @change="limpiarCamposNoAgendamiento()">
                        <option value="" selected disabled>Seleccione</option>
                        <option value="0">Sin Causa</option>
                        <option value="1">Vacunado en el extranjero</option>
                        <option value="2">No desea vacunarse</option>
                        <option value="3">Gestante</option>
                    </select>
                </div> 

                <div class="form-group col-md-3 col-sm-12" v-if="CausaNoAgendamiento==0">
                    <label for="FechaAgendamiento">Fecha Agendamiento</label>
                    <input type="date" class="form-control" name="FechaAgendamiento" v-model="FechaAgendamiento">
                </div>
                
                <div class="form-group col-md-3 col-sm-12" v-if="CausaNoAgendamiento==0">
                    <label for="HoraAgendamiento">Hora Agendamiento</label>
                    <input type="time" class="form-control" name="HoraAgendamiento" v-model="HoraAgendamiento">
                </div>                            

                <div class="form-group col-md-6 col-sm-12" v-if="CausaNoAgendamiento==0">
                    <label for="CodPrestador">Código Prestador</label>
                    <select name="CodPrestador" v-model="CodPrestador" class="form-control">
                        <option value="" selected disabled>Seleccione</option>
                        <option v-for="ips of ipsusuario" :key="ips.ICH_ID" :value="ips.IHA_CODIGO_HABILITACION">{{ ips.IHA_NOMBRE_PRESTADOR }} - {{ ips.IHA_CODIGO_HABILITACION }}</option>
                    </select>
                </div> 

                <div class="form-group col-md-6 col-sm-12" v-if="CausaNoAgendamiento==0">
                    <label for="NumeroDosis">Número de Dosis</label>
                    <select name="NumeroDosis" v-model="NumeroDosis" class="form-control">
                        <option value="" selected disabled>Seleccione</option>
                        <option value="1">Primera</option>
                        <option value="2">Segunda</option>
                    </select>
                </div>
    

                <div class="form-group col-md-12 col-sm-12">
                    <button class="btn btn-primary btn-block" :disabled="!camposObligatorios" type="button" v-if="!cargando" @click="cargueFormulario">
                        <span v-if="camposObligatorios">Enviar datos</span>
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
    props: ['tipodocumentos','ipsusuario'],
    data() {
        return {
            afiliadoEncontrado: false,
            cargando: false,
            respuesta: '',
            respuestaError: '',            
            TipoIDPaciente: '',
            NoIDPaciente: '',
            PrimerNombre: '',
            SegundoNombre: '',
            PrimerApellido: '',
            SegundoApellido: '',
            CodPrestador: '',
            FechaAgendamiento: '',
            HoraAgendamiento: '',
            NumeroDosis: '',
            CausaNoAgendamiento: '',            
        }
    },
    methods: {
        cargueFormulario(){
            this.cargando=true;
            this.respuesta='';
            this.respuestaError='';            
            const form = document.getElementById('formAgendamiento');
            let formCargue = new FormData(form);
            formCargue.append('tipoFormulario','individual');

            axios.post('covid-vacunacion',formCargue)
            .then(response => {
                this.respuesta = response.data;
                this.cargando = false;
            })            
            .catch(error => {
                this.respuestaError = error;
                this.cargando = false;
            });            
        },
        limpiarCamposNoAgendamiento(){
            if(this.CausaNoAgendamiento !== "0"){
                this.CodPrestador = '';
                this.FechaAgendamiento = '';
                this.HoraAgendamiento = '';
                this.NumeroDosis = '';                
            }
        },
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

            axios.post('api/Afiliados/busquedaBasicaXTipoNumero',formCargue)
            .then(response => {
                const data = response.data;

                if(data.PrimerNombre && data.PrimerApellido){
                    this.PrimerNombre = data.PrimerNombre;
                    this.SegundoNombre = data.SegundoNombre;
                    this.PrimerApellido = data.PrimerApellido;
                    this.SegundoApellido = data.SegundoApellido;
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
        }
    },
    computed: {
        camposObligatorios(){

           if(this.CausaNoAgendamiento == 0 && this.PrimerNombre && 
            this.PrimerApellido &&
            this.CodPrestador &&
            this.FechaAgendamiento &&
            this.HoraAgendamiento &&
            this.NumeroDosis &&
            this.CausaNoAgendamiento){
               return true;
           }else if(this.CausaNoAgendamiento != 0 && this.PrimerNombre && 
            this.PrimerApellido){
               return true;
           }else{
               return false;
           }
        }
    }
}
</script>