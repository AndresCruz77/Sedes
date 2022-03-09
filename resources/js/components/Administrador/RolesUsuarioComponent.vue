<template>
    <div class="row">  
        
        <div class="col-md-12">
            <p>Seleccione el rol del usuario</p>           
        </div>

        <div class="col-md-12" v-if="parseInt(idusuario) > 0">
            <div class="form-group mb-4">
                <div class="col-md-12 form-check" v-for="(rol,index) of roles" :key="index">
                    <input class="form-check-input" type="radio" name="rolUsuario" :id="rol.LOR_ID" :value="rol.LOR_ID" v-model="computedRolUsuario">
                    <label class="form-check-label" :for="rol.LOR_ID">
                        {{rol.LOR_NOMBRE.toUpperCase()}}
                    </label>
                </div>             
            </div>

            <div class="col-md-12">
                <h3 class="text-center">{{ mensajeRequest }}</h3>
            </div>
            
            <button class="btn btn-primary btn-block" @click="actualizarRolUsuario()" :disabled="computedRolUsuario == idrolusuario || cargando">Actualizar rol  
                <i class="fas fa-sync-alt" v-show="!cargando"></i>
                <i class="fas fa-sync-alt fa-spinner fa-w-16 fa-spin" v-show="cargando"></i>
            </button>

        </div>
        <div v-else>
            <div class="container">
                <h3>No existe un ID activo para asignar un rol.</h3>
            </div>
        </div> 

    </div>
</template>
<script>
export default {
    props: ['roles','username','idusuario','idrolusuario'],  
    data(){
        return{
           nuevoRolUsuario: 0,
           cargando: false,
           mensajeRequest:  '',
        }
    },
    methods: {
        actualizarRolUsuario(){

            this.cargando = true;
            const informacionusuario = {
                USU_ID : this.idusuario,
                USU_LOR_ID : this.nuevoRolUsuario,
                USU_USERNAME: this.username,
                USU_FORMULARIO: 'rol'
            };

            axios.put(`usuarios/${this.username}/${this.idusuario}`,informacionusuario)
            .then(resp => {
                this.cargando = false;
                this.mensajeRequest = resp.data.mensaje;
                this.idrolusuario = this.nuevoRolUsuario;
            })
            .catch(error => {
                this.cargando = false;
                this.mensajeRequest = error;
            });
        }
    },
    computed: {
        computedRolUsuario:{
            set(value){
                this.nuevoRolUsuario = (!this.nuevoRolUsuario) ? this.idrolusuario : value;
            },
            get(){
                return (!this.nuevoRolUsuario) ? this.idrolusuario : this.nuevoRolUsuario;
            }
        }
    }

}
</script>