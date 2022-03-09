<template>
        <div id="modal_aside_right" class="modal fixed-right fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-aside modal-dialog-scrollable" role="document">
            <div class="modal-content rounded">
                <div class="modal-header" style="background-color: #0081c9">
                    <!-- Sidebar - Brand -->
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" :href="url+'/home'">
                        <div class="sidebar-brand-icon">
                            <img width="50" :src="url+'/img/logos/logo_mini_blanco.png'" alt="logo mini">
                            <!-- <i class="fas fa-laugh-wink"></i> -->
                        </div>
                        <!-- <div class="sidebar-brand-text mx-3">Medimas <sup>App</sup></div> -->
                    </a>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="accordion" id="accordionMenuOne">
                        <div class="container">

                            <div class="border-bottom" v-for="menuUsuario of menusUsuario" :key="menuUsuario.idMenuPadre">
                                <a class="mt-1 mb-1 nav-link text-left collapse_menu" href="#" data-toggle="collapse" :data-target="'#collapseMenu'+menuUsuario.idMenuPadre" :aria-expanded="verificaRuta(menuUsuario.menusHijos) ? true : false" :aria-controls="'collapseMenu'+menuUsuario.idMenuPadre">
                                    <i :class="menuUsuario.iconoMenuPadre"></i>
                                    <span>{{ menuUsuario.nombreMenuPadre }}</span>
                                </a>
                            
                                <div :id="'collapseMenu'+menuUsuario.idMenuPadre" class="collapse mb-2" :class="{ 'show' : verificaRuta(menuUsuario.menusHijos) }" aria-labelledby="menuOne" data-parent="#accordionMenuOne">
                                    <div class="list-group ml-5">
                                        <a v-for="menuHijoUsuario of menuUsuario.menusHijos" 
                                            :key="menuHijoUsuario.LMR_ID" 
                                            class="list-group-item list-group-item-action linkacceso" 
                                            :class="{ 'active' : ruta === menuHijoUsuario.LMH_RUTA }" 
                                            :href="url+'/'+menuHijoUsuario.LMH_RUTA+'?menuId='+menuHijoUsuario.LMR_LMH_ID+'&nameMenu='+menuHijoUsuario.LMH_NOMBRE">
                                            {{ menuHijoUsuario.LMH_NOMBRE }}
                                        </a>
                                    </div>                            
                                </div>
                            </div>

                        </div>
                    </div>                    
                </div>
            </div>
            </div> <!-- modal-bialog .// -->
        </div> <!-- modal.// -->
</template>
<script>
export default {
    props: ['ruta','user','url'],
    data(){
        return {
            menusUsuario: []
        }
    },
    methods: {
        consultarMenu(){
             axios.post(this.url+'/api/Administrador/busquedaMenuUsuarioXIdRol',{
                 'rolUsuario': this.user.USU_LOR_ID,
                 'usernameUsuario': this.user.USU_USERNAME
             })
             .then(response => {
                 this.menusUsuario = response.data
             }) 
             .catch(error => {
                 this.menusUsuario = [];
             })
        },
        // Compara la (URL) actual contra la lista completa de las rutas actuales, en caso que se encuentre devuleve el dato.
        verificaRuta(rutasMenu){

            return rutasMenu.find((filter) => this.ruta == filter.LMH_RUTA );
            
        }
    },
    mounted() {
        this.consultarMenu();
    }
}
</script>