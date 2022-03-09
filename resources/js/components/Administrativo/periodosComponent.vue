<style>
 .modal-body{
  /* max-height:150px; */
  overflow:auto;
} 

</style>
<template>
    <div class="col-md-12">           
         <div class="row">
            <div class="col-md-1 col-sm-11">
                <a class="nav-link" :href="url+'/home'"  title="Volver"> 
                    <i class="fas fa-arrow-circle-left fa-2x"></i>
                </a>
            </div>
            <div class="col-md-11 col-sm-11">
                <h3 class="text-center border-bottom-primary p-1">Administrar Periodos <i class="fas fa-tools text-black-300"></i></h3>
            </div>
        </div> 
        <div class="card border-secondary">
            <div class="card-body"> 
                <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x"><br>                    
                    <div class="container col-sm-12">                         
                        <div class="row">
                            <div class="col-sm-12 form-group">     
                                <table class="table table-striped table-hover" id="tablaSedes"> 
                                    <thead> 
                                        <tr>
                                            <th>#</th>
                                            <th>Codigo</th>
                                            <th>Fecha Inicial</th>
                                            <th>Fecha Final</th>
                                            <th>Descripción</th>
                                            <th>Estado</th>
                                            <th>Accion</th>                                            
                                        </tr> 
                                    </thead> 
                                    <tbody>  
                                        <tr v-for="(item) in resultadoConsulta" :key="item.PER_ID">                                            
                                            <td>{{ item.PER_ID }}</td>
                                            <td>{{ item.PER_CODIGO }}</td>
                                            <td>{{ item.PER_FECHA_INICIAL }}</td>     
                                            <td>{{ item.PER_FECHA_FINAL }}</td>     
                                            <td>{{ item.PER_DESCRIPCION }}</td>                                                       
                                            <td>{{ item.PER_ESTADO }}</td>                                                                                                   
                                            <td>
                                                <div class="col-sm-12 form-group" v-if="item.PER_CERRADO == '1'">
                                                    <button type='button'  v-on:click="editarPeriodo(item)" class='btn btn-success btn-block' title ="Abrir"><i class="fas fa-lock text-white"></i></button>
                                                </div>
                                                <div class="col-sm-12 form-group" v-else>
                                                    <button type='button'  v-on:click="editarPeriodo(item)" class='btn btn-success btn-block' title ="Cerrar"><i class="fas fa-lock-open text-white"></i></button>
                                                </div>
                                            </td>                                            
                                        </tr>    
                                    </tbody>
                                    <tfoot>
                                        <tr>    
                                            <th>#</th>
                                            <th>Codigo</th>
                                            <th>Fecha Inicial</th>
                                            <th>Fecha Final</th>
                                            <th>Descripción</th>
                                            <th>Estado</th>
                                            <th>Accion</th>                                            
                                        </tr>
                                    </tfoot>
                                </table>           
                            </div>    
                        </div>    
                    </div>
                </div>
            </div> 
        </div> 
     
    </div>

    


  
    
</template>
<script>
    const debounce = (callback, wait) => {
        let timerId;
        return (...args) => {
            clearTimeout(timerId);
            timerId = setTimeout(() => {
                callback(...args);
            }, wait);
        };
    };
    import Swal from 'sweetalert2';
    export default {    
    props: [ 'url'],
    data(){
        return {
            
            municipiosel: null,
            municipios: [],
            resultadoConsulta: [],         
            save : false,
            accion : '',
            numSede : '',
            codigo : '',
            nombre : '',
            direccion : '',
            tipoSede : '',
            metro : '',
            regional : '',
            estado : '',
            departamento : '',
            seccional : '',
            tipo : '',
            regimen : '',
            observacion : '',
            consultaSede : true

            

              

        }
    },
    methods: {      
        guardaRegistro(e){  
            this.save = true;
            const datosFormulario = new FormData(e.target);                                                
            datosFormulario.append('municipio',JSON.stringify(this.municipiosel));            
            datosFormulario.append('COS_ID',this.numSede);            
            axios.post('guardaSede', datosFormulario)
            .then(resp => {                
                this.save = false;
                 if(resp.data.icon == 'success'){        
                    document.getElementById("formCrearsede").reset();  
                    $('#modal_nuevaSede').modal('hide');                 
                    this.listaSedes();                              
                }    
                return Swal.fire({
                    icon: resp.data.icon,
                    title: resp.data.title,
                    text: resp.data.text
                });                      
            })            
            .catch(error => {    
                console.log(error);
                this.save = false;
                return Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar el Registro...',
                        text: error
                    })
            }); 
        },   
        editarPeriodo(item){
            axios.get(`accionEstado/${item.PER_ID}/${item.PER_CERRADO} `)              
            .then(resp => {
                this.listaPeriodos();
                return Swal.fire({
                    icon: 'success',
                    title: 'Ok...',
                    text: 'Se Actualizado Con Exito el Consecutivo #'+resp.data
                });    
                           
            })
            .catch(error => alert(error))   
            
        },

        async listaPeriodos(){
            this.consultaSede  = true;
            await axios.post('listaPeriodos')
            .then(resp => {                                   
                if(resp.data.length > 0){
                    this.resultadoConsulta = resp.data;
                                                     
                }else{                    
                    return Swal.fire({
                        icon: 'error',
                        title: 'No se Encontraron Registros...',
                        text: 'Reportar al Administrador'
                    })
                }                        
            })            
            .catch(error => {                                
                return Swal.fire({
                        icon: 'error',
                        title: 'Error al Consultar...',
                        text: error
                    })
            }); 
            this.consultaSede  = false;
            //this.tabla();     
        },
        
        editarSede({item,index}){            
            this.accion = 'Editar Sede # ';
            this.numSede = item.COS_ID;
            axios.get(`detalleSede/${item.COS_ID}`)
            .then(resp => {
                this.municipiosel = resp.data['MUNICIPIO'];
                this.codigo = resp.data['SEDEDATA'].COS_CODIGO;
                this.nombre = resp.data['SEDEDATA'].COS_NOMBRE_SEDE;
                this.direccion = resp.data['SEDEDATA'].COS_DIRECCION_SEDE;
                this.metro = resp.data['SEDEDATA'].COS_METROS_CUADRADO;
                this.tipoSede = resp.data['SEDEDATA'].COS_TIS_ID;
                this.regional = resp.data['SEDEDATA'].COS_RNL_ID;
                this.estado = resp.data['SEDEDATA'].COS_ESS_ID;
                this.departamento = resp.data['SEDEDATA'].COS_DEP_ID;
                this.seccional = resp.data['SEDEDATA'].COS_SEC_ID;
                this.tipo = resp.data['SEDEDATA'].COS_TIP_ID;
                this.regimen = resp.data['SEDEDATA'].COS_REG_ID;
                this.observacion = resp.data['SEDEDATA'].COS_OBSERVACION;                     
            })
            .catch(error => alert(error))   
        },
        fechaInv(fecha){            
            const date = new Date(fecha);
            const day = date.getUTCDate();
            const month = date.getUTCMonth() + 1;
            const year = date.getUTCFullYear();
            const fechacm = year + "-" + month + "-" + day;           
            return fechacm;
        }
    
        
    },
    computed:{
      
    },
    mounted() {        
        this.listaPeriodos();
    },
}  
</script>