<template>

        <div class="row">  
            
            <div class="col-md-4 form-group">
                <label for="tipoDocumentoUsuario">Tipo Documento</label>
                <select name="tipoDocumentoUsuario" id="tipoDocumentoUsuario" class="form-control" v-model="informacionusuario.USU_TDO_ID">
                    <option value="" disabled selected>Seleccione</option>
                    <option v-for="tipo in tiposdocumento" :key="tipo.TDO_ID" :value="tipo.TDO_ID">{{ tipo.TDO_DESCRIPCION }}</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="identificacionUsuario">Identificación</label>
                <input type="text" class="form-control" id="identificacionUsuario" name="identificacionUsuario" v-model="informacionusuario.USU_NUMERO_IDENTIFICACION">
            </div>

            <div class="col-md-4 form-group">
                <label for="primerNombreUsuario">Primer Nombre</label>
                <input type="text" class="form-control text-uppercase" id="primerNombreUsuario" name="primerNombreUsuario" v-model="informacionusuario.USU_PRIMER_NOMBRE">
            </div>

            <div class="col-md-4 form-group">
                <label for="segundoNombreUsuario">Segundo Nombre</label>
                <input type="text" class="form-control text-uppercase" id="segundoNombreUsuario" name="segundoNombreUsuario" v-model="informacionusuario.USU_SEGUNDO_NOMBRE">
            </div>

            <div class="col-md-4 form-group">
                <label for="primerApellidoUsuario">Primer Apellido</label>
                <input type="text" class="form-control text-uppercase" id="primerApellidoUsuario" name="primerApellidoUsuario" v-model="informacionusuario.USU_PRIMER_APELLIDO">
            </div>

            <div class="col-md-4 form-group">
                <label for="segundoApellidoUsuario">Segundo Apellido</label>
                <input type="text" class="form-control text-uppercase" id="segundoApellidoUsuario" name="segundoApellidoUsuario" v-model="informacionusuario.USU_SEGUNDO_APELLIDO">
            </div>

            <div class="col-md-4 form-group">
                <label for="empresaUsuario">Empresa</label>
                <input type="text" class="form-control" id="empresaUsuario" name="empresaUsuario" v-model="informacionusuario.USU_EMP_ID">
            </div>

            <div class="col-md-4 form-group">
                <label for="usernameUsuario">Usuario</label>
                <input type="text" class="form-control" id="usernameUsuario" name="usernameUsuario" v-model="informacionusuario.USU_USERNAME" disabled>
            </div>  

            <div class="col-md-4 form-group">
                <label for="telefonoUsuario">Telefono</label>
                <input type="text" class="form-control" id="telefonoUsuario" name="telefonoUsuario" v-model="informacionusuario.USU_TELEFONO_UNO">
            </div>

            <div class="col-md-4 form-group">
                <label for="telefono2Usuario">Telefono 2</label>
                <input type="text" class="form-control" id="telefono2Usuario" name="telefono2Usuario" v-model="informacionusuario.USU_TELEFONO_DOS">
            </div>   

            <div class="col-md-4 form-group">
                <label for="direccionUsuario">Dirección</label>
                <input type="text" class="form-control" id="direccionUsuario" name="direccionUsuario" v-model="informacionusuario.USU_DIRECCION">
            </div>                                                                                                                                 

            <div class="col-md-4 form-group">
                <label for="correoUsuario">Correo</label>
                <input type="email" class="form-control" id="correoUsuario" name="correoUsuario" v-model="informacionusuario.USU_CORREO">
            </div>

            <div class="col-md-4 form-group">
                <label for="activoUsuario">Estado usuario</label>
                <select name="activoUsuario" id="activoUsuario" class="form-control" v-model="informacionusuario.USU_ACTIVO">
                    <option value="" disabled selected>Seleccione</option>
                    <option value="0">Inactivo</option>
                    <option value="1">Activo</option>
                </select>                    
            </div> 

            <div class="col-md-12">
                <h3 class="text-center">{{ mensajeRequest }}</h3>
            </div>

            <div class="col-md-12">
                <button class="btn btn-primary btn-block" 
                    v-if="camposObligatorios && parseInt(informacionusuario.USU_ID) > 0" 
                    @click="ActualizarInformacionUsuario()" 
                    :disabled="cargando">Actualizar información usuario
                    <i class="fas fa-sync-alt" v-show="!cargando"></i>
                    <i class="fas fa-sync-alt fa-spinner fa-w-16 fa-spin" v-show="cargando"></i>
                </button>
                <button class="btn btn-primary btn-block" 
                    v-else-if="camposObligatorios && parseInt(informacionusuario.USU_ID) == 0" 
                    @click="GuardarInformacionUsuario()" 
                    :disabled="cargando">Crear nuevo usuario 
                    <i class="fas fa-save" v-show="!cargando"></i>
                    <i class="fas fa-sync-alt fa-spinner fa-w-16 fa-spin" v-show="cargando"></i>
                </button>                
            </div>

        </div>

</template>
<script>
export default {
    props: ['informacionusuario','tiposdocumento'],
    data(){
        return{
            cargando: false,
            mensajeRequest:  '',
        }
    },
    methods:{
        ActualizarInformacionUsuario(){

            this.mensajeRequest = '';
            this.cargando = true;
            const idusuario = this.informacionusuario.USU_ID;
            const username = this.informacionusuario.USU_USERNAME;
            this.informacionusuario.USU_FORMULARIO = 'informacion';

            this.mensaje = 'Actualizando información, por favor espere...';
            axios.put(`usuarios/${username}/${idusuario}`,this.informacionusuario)
            .then(resp => {
                this.cargando = false;
                this.mensajeRequest = resp.data.mensaje;
                this.actualizarInformacionPadre();
            })
            .catch(error => {
                this.cargando = false;
                this.mensajeRequest = error;
            });
        },
        GuardarInformacionUsuario(){

            this.mensajeRequest = '';
            this.cargando = true;

            this.mensaje = 'Actualizando información, por favor espere...';
            axios.post('usuarios',this.informacionusuario)
            .then(resp => {
                this.cargando = false;
                this.mensajeRequest = resp.data.mensaje;
                if(resp.data.informacionUsuario){
                    this.informacionusuario = resp.data.informacionUsuario;
                    this.actualizarInformacionPadre();
                }
                
            })
            .catch(error => {
                this.cargando = false;
                this.mensajeRequest = error;
            });

        },
        actualizarInformacionPadre(){
            this.$emit('actualizarInfoUsuario',this.informacionusuario);
        }
    },
    computed: {
        camposObligatorios(){
            if(
                this.informacionusuario.USU_TDO_ID &&
                this.informacionusuario.USU_NUMERO_IDENTIFICACION &&
                this.informacionusuario.USU_PRIMER_NOMBRE &&
                this.informacionusuario.USU_PRIMER_APELLIDO &&
                this.informacionusuario.USU_USERNAME &&
                this.informacionusuario.USU_TELEFONO_UNO &&
                this.informacionusuario.USU_TELEFONO_DOS &&
                this.informacionusuario.USU_DIRECCION &&
                this.informacionusuario.USU_CORREO &&
                this.informacionusuario.USU_ACTIVO
            ){
                return true;
            }else{
                return false;
            }
        }
    },
}
</script>