<template>
    <div class="row">

        <div class="col-md-3 form-group">
            <input type="text" placeholder="Ingrese el usuario a buscar" id="busquedaUsuario" name="busquedaUsuario" class="form-control" v-model="busquedaUsuario" @change="datosUsuario={}, usuarioBuscado=false">
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary" @click="buscarUsuario()" :disabled="!busquedaUsuario.length">Buscar usuario <i class="fas fa-search"></i></button>
        </div> 

        <div class="col-md-5 p-3">
            <small class="lead">
                {{ mensaje }}
            </small>
        </div>  


        <div class="col-md-12 mt-3" v-show="usuarioBuscado">                 

                <ul class="nav nav-tabs" id="tabadmin" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="informacion-usuario-tab" data-toggle="tab" href="#informacion-usuario" role="tab" aria-controls="informacion-usuario" aria-selected="true">Información Usuario</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="rol-tab" data-toggle="tab" href="#rol" role="tab" aria-controls="rol" aria-selected="false">Asignación de rol</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="permisos-tab" data-toggle="tab" href="#permisos" role="tab" aria-controls="permisos" aria-selected="false">Asignación de Permisos</a>
                    </li>
                </ul>
                <div class="tab-content" id="tabadminContent">
                    <div class="tab-pane fade show active" id="informacion-usuario" role="tabpanel" aria-labelledby="informacion-usuario-tab"><br>
                        <admin-informacion-usuario 
                            :informacionusuario="datosUsuario" 
                            :tiposdocumento="tiposdocumento" 
                            @actualizarInfoUsuario="actualizarInfoUsuario">
                        </admin-informacion-usuario>
                    </div>
                    <div class="tab-pane fade" id="rol" role="tabpanel" aria-labelledby="rol-tab"><br>
                        <admin-roles-usuario
                            :roles="roles" 
                            :username="datosUsuario.USU_USERNAME" 
                            :idusuario="parseInt(datosUsuario.USU_ID)" 
                            :idrolusuario="parseInt(datosUsuario.USU_LOR_ID)">
                        </admin-roles-usuario>
                    </div>
                    <div class="tab-pane fade" id="permisos" role="tabpanel" aria-labelledby="permisos-tab"><br>
                        hola
                    </div>
                </div> 

        </div>             
        
    </div>
</template>
<script>
export default {
    props: ['tiposdocumento','roles'],
    data(){
        return{
            busquedaUsuario: '',
            usuarioBuscado: false,
            datosUsuario: {},
            creacionUsuario: false,
            mensaje: '',
        }
    },
    methods: {
        buscarUsuario(){
            this.usuarioBuscado = true;
            this.creacionUsuario = false;
            this.mensaje = 'Buscando por favor espere...';
            
            axios.get('usuarios/'+this.busquedaUsuario)
            .then(resp => {

                this.datosUsuario = resp.data;

                if(resp.data.USU_ID == 0){
                    this.creacionUsuario = true;
                }

                this.mensaje = '';
                
            })
            .catch(error => {
                this.mensaje = error;
            })
        }
    }    
}
</script>