<template>
    <div class="row py-5 text-white">
        <div class="d-none d-md-flex col-md-3 col-lg-5 bg-image mt-5"></div>
        <div class="offset-md-1 col-md-8 col-lg-6" v-show="agendamientos.length == 0">
            <form id="formConsultaAgendamiento">
                <div class="logo-image"></div>
                <h1 class="text-center mt-2">
                    Consultar mi vacunación.
                </h1>
                <hr class="bg-white mb-3">
                <div class="form-group">
                    <label for="TipoIDPaciente">Tipo Documento</label>
                    <select name="TipoIDPaciente" id="TipoIDPaciente" class="form-control form-control-lg" v-model="TipoIDPaciente">
                        <option value="" selected disabled>Seleccione</option>
                        <option v-for="tipodocumento of tiposdocumento" :key="tipodocumento.TDO_HOMOLOGACION2" :value="tipodocumento.TDO_HOMOLOGACION2">{{ tipodocumento.TDO_HOMOLOGACION2 }} {{ tipodocumento.TDO_DESCRIPCION }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="NoIDPaciente">Número de documento</label>
                    <input type="text" class="form-control form-control-lg" name="NoIDPaciente" id="NoIDPaciente" placeholder="Número de documento" v-model="NoIDPaciente">
                </div>     
                <div class="form-group">
                    <label for="g-recaptcha" class="text-warning" v-if="errorCaptcha">Debe confirmar que no es un robot.</label>
                    <div class="g-recaptcha" data-sitekey="6LcNsZAaAAAAADOw7uB3Dub7nHSm17bCIlQ2S30h"></div>
                </div> 
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-lg btn-block" v-if="TipoIDPaciente && NoIDPaciente" @click="consultarMiVacunacion()" :disabled="cargando">
                        <span v-if="!cargando">Consultar Agendamiento <i class="fas fa-search"></i></span>
                        <span v-else>Consultando agendamiento, por favor espere... <i class="spinner-border spinner-border-lg"></i></span>
                    </button>
                </div> 
            </form>         
        </div>
        <div class="offset-md-1 col-md-8 col-lg-6 mt-4" v-show="agendamientos.length > 0">
            <div class="logo-image"></div>
            <a class="text-white" href="#" @click="volverAConsultar()">
                <h4><i class="fas fa-arrow-circle-left text-white"></i> Volver a consultar</h4> 
            </a>
            <h1 class="text-center mt-3">
                {{ getNombres() }}
            </h1>
            <h2 class="text-center mt-3">
                {{ getIdentificacion() }}
            </h2>

            <hr class="bg-white mb-3">  

            <div class="card text-dark bg-light mb-3" v-for="(agenda,index) of agendamientos" :key="index">
                <div class="card-header">Aplicación # {{ index+1 }}</div>
                <div class="card-body">
                    <p>
                        <b>Estado agendamiento:</b> {{ (parseInt(agenda.EstadoAgendamiento)) ? 'Agendado' : 'No Agendado' }}
                    </p>
                    <p>
                        <b>Lugar vacunación:</b> {{ (agenda.nombrePrestador) ? agenda.nombrePrestador.IHA_NOMBRE_PRESTADOR : '' }}
                    </p>
                    <p>
                        <b>Fecha agendamiento:</b> {{ agenda.FechaAgendamiento }}
                    </p>
                    <p>
                        <b>Hora agendamiento:</b> {{ agenda.HoraAgendamiento }}
                    </p>
                    <p>
                        <b>Número de dosis:</b> {{ (!parseInt(agenda.NumeroDosis)) ? 0 : agenda.NumeroDosis }}
                    </p>
                    <p>
                        <b>Causa de no agendamiento:</b> {{ causaNoAgendamiento(agenda.CausaNoAgendamiento) }}
                    </p>
                    <p>
                        <b></b>
                    </p>   
                </div>
            </div>
                                    
        </div>
</div>
</template>
<script>
export default {
    props: ['tiposdocumento','url'],
    data() {
        return {
            cargando: false,
            TipoIDPaciente: '',
            NoIDPaciente: '',
            errorCaptcha: false,
            agendamientos: []
        }
    },
    methods:{
        consultarMiVacunacion(){

            this.errorCaptcha = false;

            const captchaResponse = grecaptcha.getResponse();
            if(captchaResponse.length == 0){
                this.errorCaptcha = true;
                return;
            }

            this.cargando = true;
            const formulario = new FormData(document.getElementById('formConsultaAgendamiento'));

            axios.post('covid-vacunacion',formulario)
            .then(resp => {
                grecaptcha.reset();
                (resp.data[0]) ? this.agendamientos = resp.data : alert('No existe información de agendamiento para el paciente');
                this.cargando = false;
            })
            .catch(error => {
                alert(error);
                grecaptcha.reset();
                this.agendamientos = {};
                this.cargando = false;
            });
        },
        volverAConsultar(){
            this.TipoIDPaciente = '';
            this.NoIDPaciente = '';
            this.agendamientos = [];
        },
        causaNoAgendamiento(causa){

            switch (parseInt(causa)) {
                case 0:
                    return 'Sin causa';

                case 1:
                    return 'Vacunado en el extranjero';

                case 2:
                    return 'No desea vacunarse';

                case 3:
                    return 'Gestante';
                                                           
                default:
                    return '';
            }
        },
        getNombres(){
            const agendamientos = this.agendamientos;
            if(agendamientos.length > 0){
                return `${agendamientos[0].PrimerNombre} ${agendamientos[0].SegundoNombre} ${agendamientos[0].PrimerApellido} ${agendamientos[0].SegundoApellido}`;
            }
        },
        getIdentificacion(){
            const agendamientos = this.agendamientos;
            if(agendamientos.length > 0){
                return `${agendamientos[0].TipoIDPaciente} ${agendamientos[0].NoIDPaciente}`;
            }
        }
    },
}
</script>