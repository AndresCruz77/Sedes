<template>
    <div class="row">
        
        <div class="col-md-5" v-show="!responsableSeleccionado.USP_USU_ID">
            <div class="card text-dark bg-light mb-3">
                <div class="card-header">USUARIOS SISTEMA FUERA DEL EQUIPO ML</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" placeholder="Ingrese el nombre responsable" v-model="buscarSinProceso">
                        </div>
                        <div class="col-md-12">
                            <div class="list-group">
                                <label class="list-group-item d-flex justify-content-between" v-for="usuario of getListaUsuarios(false).filter((buscar) => getNombres(buscar).includes(buscarSinProceso) )" :key="usuario.USU_ID">
                                    <input class="form-check-input me-1" type="checkbox" :value="usuario" v-model="responsablesNuevos">{{ usuario.USU_PRIMER_NOMBRE }} {{usuario.USU_SEGUNDO_NOMBRE}} {{usuario.USU_PRIMER_APELLIDO}} {{usuario.USU_SEGUNDO_APELLIDO}}
                                </label>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-1 form-group" v-show="!responsableSeleccionado.USP_USU_ID">
            <div class="btn-group-vertical">
                <button class="btn btn-success" type="button" :disabled="isResponsablesNuevos" @click="setAgregarResponsables" v-if="!cargando">
                    <i class="fas fa-arrow-circle-right fa-2x"></i>
                </button>

                <button class="btn btn-warning" type="button" :disabled="isResponsablesActivos" @click="setQuitarResponsables" v-if="!cargando">
                    <i class="fas fa-arrow-circle-left fa-2x"></i>
                </button>  

                <button class="btn btn-primary" type="button" disabled v-if="cargando">
                    <i class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></i>
                </button>                              
            </div>            
        </div>

        <div class="col-md-6">
            <div class="card text-dark bg-light mb-3">
                <div class="card-header">USUARIOS SISTEMA DENTRO DEL EQUIPO ML</div>
                <div class="card-body">                
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" placeholder="Ingrese el nombre del responsable" v-model="buscarConProceso">
                        </div>
                        <div class="col-md-12">
                            <div class="list-group">
                                <label class="list-group-item d-flex justify-content-between" :class="{ 'active' : usuario.USU_ID == responsableSeleccionado.USP_USU_ID}" v-for="usuario of getListaUsuarios(true).filter((buscar) => getNombres(buscar).includes(buscarConProceso))" :key="usuario.USU_ID">
                                    <input v-show="!responsableSeleccionado.USP_USU_ID" class="form-check-input me-1" type="checkbox" :id="usuario.USU_ID" :value="usuario" v-model="responsablesActivos">{{ usuario.USU_PRIMER_NOMBRE }} {{usuario.USU_SEGUNDO_NOMBRE}} {{usuario.USU_PRIMER_APELLIDO}} {{usuario.USU_SEGUNDO_APELLIDO}}
                                    <a href="#" @click="getGestionarProcesos(usuario)">
                                        <i class="fas fa-cog nav-link"></i>
                                    </a>
                                </label>
                            </div>                            
                        </div>                        
                    </div>
                </div>
            </div> 
        </div>   

        <div class="col-md-6" v-show="responsableSeleccionado.USP_USU_ID">
            <div class="card text-dark bg-light mb-3">
                <div class="card-header d-flex justify-content-between">
                    PROCESOS - {{ getNombres(responsableSeleccionado).toUpperCase() }}
                    <a href="#" @click="responsableSeleccionado={}">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <div class="card-body">  
                    <label class="list-group-item d-flex justify-content-between" v-for="proceso of responsableSeleccionado.procesosGestion" :key="proceso.PRG_ID">
                        <input class="form-check-input me-1" type="checkbox" :id="proceso.PRG_ID" :value="proceso.PRG_ID" :checked="parseInt(proceso.UPG_ESTADO)" @change="setAregarProcesoGestionResponsable(proceso)">{{ proceso.PRG_NOMBRE }}
                    </label>                    
                </div>
            </div>            
        </div>     

    </div>
</template>
<script>
export default {
    props: ['usuarios'],
    data(){
        return {
            responsablesNuevos: [],
            responsablesActivos: [],
            buscarSinProceso: '',
            buscarConProceso: '',
            cargando: false,
            responsableSeleccionado: {}
        }
    },
    methods:{
        getListaUsuarios(proceso){
            const conProceso = (filtro) => {
                return filtro.USP_ESTADO == 1;
            }

            const sinProceso = (filtro) => {
                return filtro.USP_ESTADO <= 0;
            }

            const filtroProceso = (proceso) ? conProceso : sinProceso;

            return this.usuarios.filter(filtroProceso);
        },
        getNombres(datos){
            const { 
                USU_PRIMER_NOMBRE: primerNombre, 
                USU_SEGUNDO_NOMBRE: segundoNombre, 
                USU_PRIMER_APELLIDO: primerApellido, 
                USU_SEGUNDO_APELLIDO: segundoApellido } = datos;
            return `${primerNombre} ${segundoNombre} ${primerApellido} ${segundoApellido}`.toLowerCase();
        },
        setAgregarResponsables(){
            const lista = this.responsablesNuevos;
            this.accionListasUsuarios(lista);
            this.responsablesNuevos = [];
        },
        setQuitarResponsables(){
            const lista = this.responsablesActivos;
            this.accionListasUsuarios(lista);
            this.responsablesActivos = [];
        },
        setAregarProcesoGestionResponsable(proceso){

                axios.post('proceso-gestion-usuario',{
                    USP_ID: this.responsableSeleccionado.USP_ID,
                    PRG_ID: proceso.PRG_ID
                })
                .then(resp => {
                    proceso.UPG_ID = resp.data.UPG_ID;
                    proceso.UPG_ESTADO = resp.data.UPG_ESTADO;
                    this.cargando = false;
                })
                .catch(error => {
                    console.log(error);
                    this.cargando = false;
                });            
        },
        accionListasUsuarios(lista){
            this.cargando = true;
            for (const responsable of lista) {
                const usuario = this.usuarios.find((u) => u.USU_ID === responsable.USU_ID );

                axios.post('procesos-usuario',{
                    USU_ID : usuario.USU_ID
                })
                .then(resp => {
                    usuario.USP_ESTADO = resp.data.USP_ESTADO;
                    usuario.USP_USU_ID = resp.data.USP_USU_ID;
                    usuario.USP_ID = resp.data.USP_ID;
                    this.cargando = false;
                })
                .catch(error => {
                    console.log(error);
                    this.cargando = false;
                });
            }            
        },
        getGestionarProcesos(usuario){
            this.responsableSeleccionado = usuario;
        }
    },
    computed: {
        isResponsablesNuevos(){
            return (!this.responsablesNuevos.length) ? true: false;
        },
        isResponsablesActivos(){
            return (!this.responsablesActivos.length) ? true: false;
        }
    }
}
</script>