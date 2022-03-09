<template>
        <div class="card border-dark mb-3" style="width: 100%;">
            <div class="card-header">Cargues realizados</div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Fecha Cargue</th>
                                <th scope="col">Nombre Archivo</th>
                                <th scope="col">Estado Cargue</th>
                                <th scope="col">Descagar Errores</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cargue,index) of historialcargues.filter((cargues) => cargues.RCP_TIPO_CARGUE == tipocargue )" :key="cargue.RCP_ID">
                                <th scope="row">{{ index+1 }}</th>
                                <td>{{ cargue.RCP_FECHA_CREACION }} </td>
                                <td>{{ cargue.RCP_NOMBRE_ARCHIVO }}</td>
                                <td>{{ cargue.REC_NOMBRE }}</td>
                                <td>
                                    <a target="_blank" class="nav-link" :href="'covid-vacunacion/erroresAsignacion/'+cargue.RCP_ID" v-if="cargue.RCP_ID_ESTADO == 2">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td> 
                            </tr> 
                        </tbody>
                    </table>                             
                </div>
            </div>
        </div>     
</template>
<script>
export default {
    props: ['historialcargues','tipocargue']
}
</script>