<template>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="">Grupo de días</label>
            <select name="" id="" class="form-control">
                <option value="" selected disabled>Seleccione</option>
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label for="">Filtro 2</label>
            <select name="" id="" class="form-control">
                <option value="" selected disabled>Seleccione</option>
            </select>                
        </div>
        <div class="col-md-3 form-group">
            <label for="">Filtro 3</label>
            <select name="" id="" class="form-control">
                <option value="" selected disabled>Seleccione</option>
            </select>                
        </div>

        <div class="col-md-3 form-group">
            <button class="btn btn-primary btn-block mt-4" @click="consultarIncapacidades()">Consultar <i class="fas fa-search"></i></button>
        </div>

        <div class="col-md-12">
            <h3 v-show="cargando">Procesando información, por favor espere...</h3>
        </div>

        <div class="col-md-12">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Tipo Doc</th>
                        <th>Num Doc</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Origen</th>
                        <th>CIE 10</th>
                        <th>D. Incapacidad</th>
                        <th>D. Acumulados</th>
                        <th>D. Max Acumulados</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(incapacidad, index) in consultaIncapacidades" :key="index">
                        <td>{{ incapacidad.ILC_PRIMER_NOMBRE }} {{ incapacidad.ILC_PRIMER_APELLIDO }}</td>
                        <td>{{ incapacidad.ILC_TIPO_DOCUMENTO }}</td>
                        <td>{{ incapacidad.ILC_NUMERO_DOCUMENTO }}</td>
                        <td>30</td>
                        <td>{{ incapacidad.ILC_SEXO }}</td>
                        <td>ORIGEN</td>
                        <td>{{ incapacidad.IBP_DCIE_CODIGO }}</td>
                        <td>{{ incapacidad.IBP_DIAS_INCAPACIDAD }}</td>
                        <td>{{ incapacidad.IBP_DIAS_ACUMULADOS }}</td>
                        <td>{{ incapacidad.IBP_DIAS_MAX_ACUMULADOS }}</td>
                        <td>
                            <button class="btn btn-primary">
                                <i class="fas fa-check"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>            
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            cargando: false,
            consultaIncapacidades: [],
            incapacidadSeleccionada: {}
        }
    },
    methods: {
        consultarIncapacidades(){
            this.cargando = true;
            axios.post('incapacidades/consultar',{})
            .then(resp => {
                this.cargando = false;
                this.consultaIncapacidades = resp.data;
            })
            .catch(error => {
                this.cargando = false;
                console.log(error);
            })

        },
        seleccionarIncapacidad(incapacidad){
            this.incapacidadSeleccionada = incapacidad;
        }
    }
}
</script>