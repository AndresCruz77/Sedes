<template>
    <div>
        <form id="formCargueArchivoAsignacion" class="form-row" enctype="multipart/form-data">

            <div class="input-group mb-3 col-md-12">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="fileAsignacion">Cargar</span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="inputFileAgendamiento" id="inputFileAsignacion" aria-describedby="fileasignacion" @change="seleccionarArchivo">
                    <label class="custom-file-label" for="inputFileAsignacion">Seleccionar archivo de asignación</label>
                </div>
            </div>

            <div class="form-group col-md-12 col-sm-12">
                <button type="button" class="btn btn-primary btn-block" :disabled="!archivoCargado" @click="cargueArchivo" v-if="!cargando">
                    <span v-if="archivoCargado">Cargar datos</span>
                    <span v-else>Complete todos los campos</span>
                </button>
                <button class="btn btn-primary btn-block" type="button" disabled v-else>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Procesado información, por favor espere...
                </button>            
            </div>  

            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="respuestaError">
                    <strong>!Error!,</strong> {{ respuestaError }}.
                </div> 
                <div class="alert alert-success alert-dismissible fade show" role="alert" v-else-if="respuesta">
                    <strong>!Mensaje!,</strong> {{ respuesta.Mensaje }}.
                </div>              
            </div>                  

        </form>

        <div class="card border-primary mb-3" style="width: 100%;">
            <div class="card-header">Resultado del cargue</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <p class="card-text">{{ respuesta.Mensaje }}</p>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <p class="card-text">Filas validadas: {{ respuesta.FilasVerificadas }}</p>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <p class="card-text">Errores: {{ respuesta.FilasError }}</p>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <p class="card-text">Exitoso: {{ respuesta.FilasExitosas }}</p>
                    </div>                    
                    <div class="col-md-1 col-sm-12">
                        <a target="_blank" class="nav-link" :href="'covid-vacunacion/erroresAgendamiento/'+respuesta.DescargarErrores" v-if="respuesta.FilasError > 0">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>                    
                </div>
        
            </div>
        </div> 

        <historial-cargues :historialcargues="historialcargues" :tipocargue="'Asignacion'"></historial-cargues>      

    </div>
</template>
<script>

export default {
    props: ['historialcargues'],
    data() {
        return {
            archivoCargado: false,
            cargando: false,
            respuesta: '',
            respuestaError: '',
        }
    },
    methods: {
        seleccionarArchivo(event){
            this.respuesta='';
            this.respuestaError='';
            const allowExtensions = ['xlsx','xls',];
            this.respuestaError = '';
            const file = event.target.files[0];
            (file) ? this.archivoCargado = true : this.archivoCargado = false;
            if( this.archivoCargado ){
                const fileName = file.name;
                const fileExt = fileName.split('.')[1].toLowerCase();
                if( !allowExtensions.find((ext) => ext === fileExt) ){
                    this.archivoCargado = false;
                    this.respuestaError = 'Solo se permiten archivos Excel';
                }
            }
        },
        cargueArchivo(){

            // if(window.file && window.FileReader && window.FileList && window.Blob){

            // }else{
            //     alert('Navegador no soporta la carga de archivos');
            // }
            // const archivoCargado = document.getElementById('inputFileAsignacion');

            // const validarCargue = new ValidacionArchivo(archivoCargado);

            // validarCargue.camposRequeridosEstructura = [
            //     'RAA_ID',	'RAA_TIPO_DOCUMENTO_PACIENTE',	'RAA_NUMERO_DOCUMENTO_PACIENTE',	'RAA_CODIGO_PRESTADOR',	'RAA_CARGUE_ID',	'RAA_FILA_CARGUE',	'RAA_FECHA_REGISTRO',	'RAA_RADICADO_ID',

            // ];

            // return;

            this.respuesta = '';
            this.respuestaError = '';
            this.cargando = true;

            const file = document.getElementById('inputFileAsignacion');

            const form = document.getElementById('formCargueArchivoAsignacion');
            let formCargue = new FormData(form);
            formCargue.append('tipoFormulario','asignacionMasivo');

            axios.post('covid-vacunacion', formCargue)
           .then(response => {
                this.respuesta = response.data;
                this.cargando = false;
            })            
            .catch(error => {
                this.respuestaError = error;
                this.cargando = false;
            });
        }
    },
    mounted() {
        $('#inputFileAgendamiento').on('change',function(){
            //get the file name
            var fileName = $(this)[0].files[0].name;
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').text(fileName);
        });

    },
}

</script>