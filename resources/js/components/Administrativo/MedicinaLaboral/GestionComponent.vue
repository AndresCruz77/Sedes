<template>
    <div>

        <div class="row" v-if="!mostrarProceso">
            <div class="col-xl-3 col-md-6 mb-4" v-for="proceso of procesos" :key="proceso.UPRG_ID">
                <a class="link" href="#" @click="seleccionarProceso(proceso)">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ proceso.PRG_NOMBRE }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ proceso.PRG_ID }}</div>
                        </div>
                        <div class="col-auto">
                        <i :class="proceso.PRG_ICONO+' fa-2x text-gray-300'"></i>
                        </div>
                    </div>
                    </div>
                </div>
                </a>
            </div>
        </div>

        <div class="row" v-else>
            <div class="col-md-1 col-sm-11">
                <a class="nav-link" href="#" @click="desmarcarProceso()" title="Volver">
                    <i class="fas fa-arrow-circle-left fa-2x"></i>
                </a>
            </div>
            <div class="col-md-11 col-sm-11">
                <h3 class="text-center border-bottom-info p-1">{{ procesoSeleccionado.PRG_NOMBRE }} <i :class="procesoSeleccionado.PRG_ICONO+' text-gray-300'"></i></h3>
            </div>
            
            <div class="container-fluid mb-2"><br>
                <ml-tutela-component v-if="procesoSeleccionado.PRG_ID == 2"></ml-tutela-component>
                <ml-incapacidades-component v-if="procesoSeleccionado.PRG_ID == 10"></ml-incapacidades-component>
            </div>
        </div>


    </div>
</template>
<script>
export default {
    props: ['procesos'],
    data(){
        return {
            procesoSeleccionado: {},
            mostrarProceso: false
        }
    },
    methods: {
        seleccionarProceso(proceso){
            this.procesoSeleccionado = proceso;
            this.mostrarProceso = true;
        },
        desmarcarProceso(){
            this.procesoSeleccionado = {};
            this.mostrarProceso = false;
        }
    },
}
</script>