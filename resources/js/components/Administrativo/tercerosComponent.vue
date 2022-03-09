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
                <h3 class="text-center border-bottom-primary p-1">Administrar Terceros <i class="fas fa-tools text-black-300"></i></h3>
            </div>
        </div> 
        <div class="card border-secondary">
            <div class="card-body"> 
                <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x"><br>                    
                    <div class="container col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type='button' v-on:click="limpiaCampos()" class="btn btn-success btn-sm-4" data-toggle="modal" data-target="#modal_nuevoTercero">Agregar <!--  -->
                                       <i class="fas fa-plus text-white"></i> 
                                </button>                                                             
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-sm-12 form-group" v-if="!consultaTercero">     
                                <table-terceros :listaterceros="resultadoConsulta" @editarTercero="editarTercero">
                                    
                                </table-terceros>                                                                  
                            </div>        
                            <div v-else>
                              
                            </div>
                        </div>    
                    </div>
                </div>
            </div> 
        </div> 
        <!-- inicia modal nueva sede-->
        <!-- <div class="modal fade" id="modal_nuevaSede"  role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"> -->
        <div class="modal fade" id="modal_nuevoTercero" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_nuevoTerceroLabel" aria-hidden="true">        
            <div class="modal-dialog modal-xl" role="document">            
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>{{this.accion}} {{this.numTercero}} </b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <form  @submit.prevent="guardaRegistro" id="formCreartercero">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label class="text-primary">Tipo Identificación</label>
                                        <select v-model="tipDocu" id="tipoDocumento" name="tipoDocumento" class="form-control" required>
                                            <option value="">[SELECCIONE]</option>
                                            <option :value="tipodocs.TDO_ID" v-for="tipodocs of listatipodocs" :key="tipodocs.TDO_ID" >{{ tipodocs.TDO_DESCRIPCION}} ({{ tipodocs.TDO_HOMOLOGACION2 }})</option>
                                        </select>
                                    </div>   
                                    <div class="col-sm-6 form-group">
                                        <label class="text-primary">Número Identificación</label>
                                        <input v-model="numeroIdent" type="number" id="numIdentifica" name="numIdentifica" class="form-control" required>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label class="text-primary">Nombre</label>
                                        <input v-model="nombre" type="text" id="nombreTercero" name="nombreTercero" class="form-control" required>
                                    </div>   
                                    <div class="col-sm-6 form-group">
                                        <label class="text-primary">Estado</label>
                                        <select v-model="estadoTercero" id="estadoTerc" name="estadoTerc" class="form-control" required>
                                            <option value="" >[SELECCIONE]</option>
                                            <option value="0">INACTIVO</option>
                                            <option value="1">ACTIVO</option>                                            
                                        </select>
                                    </div>   
                                </div>
                            </div>    
                            <div class="modal-footer">                                
                                <button type="submit" class="btn btn-success" v-if="!save"><span>Guardar</span></button>
                                <button type="button" class="btn btn-success" disabled v-else><span>Procesando Información</span></button>
                                <button type="button" id="cerrar_nuevo" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>                        
                            </div>
                        </form>                                                
                    </div>                    
                </div>
            </div>
        </div>    
        <!-- fin modal -->
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
    props: [ 'url', 'listatipodocs'],
    data(){
        return {
            tipDocu : '',
            numeroIdent : '',
            nombre : '',
            estadoTercero : '',           
            resultadoConsulta: [],         
            save : false,
            accion : '',
            numTercero : '',            
            consultaTercero : true
        }
    },
    methods: {            
        guardaRegistro(e){  
            this.save = true;
            const datosFormulario = new FormData(e.target);     
            datosFormulario.append('TER_ID',this.numTercero);                                                         
            axios.post('guardaTercero', datosFormulario)
            .then(resp => {                
                this.save = false;
                if(resp.data.icon == 'success'){        
                    document.getElementById("formCreartercero").reset();  
                    $('#modal_nuevoTercero').modal('hide');                 
                    this.listaTerceros();                              
                }    
                return Swal.fire({
                    icon: resp.data.icon,
                    title: resp.data.title,
                    text: resp.data.text
                });                               
            })            
            .catch(error => {                
                this.save = false;
                return Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar el Registro...',
                        text: error
                    })
            }); 
        },    

        async listaTerceros(){
            this.consultaTercero  = true;
            await axios.post('listaTerceros')
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
            this.consultaTercero  = false;            
        },
        
        editarTercero({item,index}){            
            this.accion = 'Editar Tercero # ';
            this.numTercero = item.TER_ID;
            axios.get(`detalleTercero/${item.TER_ID}`)
            .then(resp => {                
                console.log(resp.data.TER_TDO_ID);
                this.tipDocu = resp.data.TER_TDO_ID;
                this.numeroIdent = resp.data.TER_NUMERO_DOCUMENTO;
                this.nombre = resp.data.TER_NOMBRE;
                this.estadoTercero = resp.data.TER_ACTIVO;                 
            })
            .catch(error => alert(error))   
        },
        limpiaCampos(){                        
            this.accion = 'Crear Tercero';
            this.numTercero = '';
            this.tipDocu = '';
            this.numeroIdent = '';
            this.nombre = '';
            this.estadoTercero = '';            
        }
    },
    computed:{
      
    },
    mounted() {        
        this.listaTerceros();
    },
}  
</script>