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
                <h3 class="text-center border-bottom-primary p-1">Administrar Sedes <i class="fas fa-tools text-black-300"></i></h3>
            </div>
        </div> 
        <div class="card border-secondary">
            <div class="card-body"> 
                <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x"><br>                    
                    <div class="container col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type='button' v-on:click="limpiaCampos()" class="btn btn-success btn-sm-4" data-toggle="modal" data-target="#modal_nuevaSede">Agregar <!--  -->
                                       <i class="fas fa-plus text-white"></i> 
                                </button>                                                             
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-sm-12 form-group" v-if="!consultaSede">     
                                <table-sedes :listasedes="resultadoConsulta" @editarSede="editarSede">
                                    
                                </table-sedes>

                                                                  
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
        <div class="modal fade" id="modal_nuevaSede" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_nuevaSedeLabel" aria-hidden="true">        
            <div class="modal-dialog modal-xl" role="document">            
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>{{this.accion}} {{this.numSede}} </b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <form  @submit.prevent="guardaRegistro" id="formCrearsede">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Codigo</label>
                                        <input v-model="codigo" id="codigo" type="text" name="codigo" class="form-control" maxlength="6" required>                                    
                                    </div>       
                                    <div class="col-sm-3 form-group">
                                        <label  class="text-primary">Nombre</label>
                                        <input v-model="nombre" id="nombre" name="nombre" class="form-control" required>
                                    
                                    </div>       
                                    <div class="col-sm-3 form-group">
                                        <label  class="text-primary">Direccion</label>
                                        <input v-model="direccion" id="direccion" name="direccion" class="form-control" required>                                    
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Metro Cuadrado</label>
                                        <input v-model="metro" type="number" id="metroCua" name="metroCua" class="form-control" required>
                                    </div>        
                                </div>
                                <div class="row">                                          
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Tipo Sede</label>
                                        <select v-model="tipoSede" id="tipSede" name="tipSede" class="form-control" required> 
                                            <option >[SELECCIONE]</option>
                                            <option :value="tiposede.TIS_ID" v-for="tiposede of listatiposede" :key="tiposede.TIS_ID" >{{ tiposede.TIS_NOMBRE }}</option>
                                        </select>
                                    </div>       
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Regional</label>
                                        <select v-model="regional" id="regional" name="regional" class="form-control" required>
                                            <option >[SELECCIONE]</option>
                                            <option :value="regional.RNL_ID" v-for="regional of listaregional" :key="regional.RNL_ID" >{{ regional.RNL_NOMBRE }}</option>
                                        </select>
                                    </div> 
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Estado</label>
                                        <select v-model="estado" id="estadoGen" name="estadoGen" class="form-control" required>
                                            <option >[SELECCIONE]</option>
                                            <option :value="estados.ESS_ID" v-for="estados of listaestados" :key="estados.ESS_ID">{{ estados.ESS_NOMBRE }}</option>                                                                                        
                                        </select>
                                    </div>      
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Departamento</label>
                                        <select v-model="departamento" id="departamento" name="departamento" class="form-control" @change="limpiamunicipio()" required>
                                            <option value="" >[SELECCIONE]</option >
                                            <option :value="departamentos.DEP_ID" v-for="departamentos of listadepartamentos" :key="departamentos.DEP_ID">{{ departamentos.DEP_NOMBRE }}</option>
                                        </select>
                                    </div>                                       
                                </div>
                                <div class="row">                                                                                  
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Municipio</label>                                        
                                        <v-select v-model="municipiosel" :options="municipios" name="municipio" label="MUN_NOMBRE_SIN_CARACTERES_ESPECIALES" code="MUN_ID" :filterable="false" placeholder="Digite Municipio"   @search="onSearchMunicipio">                                        
                                            <template #search="{attributes, events}"><input  class="vs__search" :required="!municipiosel" v-bind="attributes"  v-on="events" /></template>
                                        </v-select>                                                
                                    </div>              
                                     <div class="col-sm-3 form-group">
                                        <label class="text-primary">Seccional</label>
                                        <select v-model="seccional" id="seccional" name="seccional" class="form-control" required>
                                            <option >[SELECCIONE]</option>
                                            <option :value="seccional.SEC_ID" v-for="seccional of listaseccional" :key="seccional.SEC_ID">{{ seccional.SEC_NOMBRE }}</option>                                            
                                        </select>
                                    </div>       
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Tipo</label>
                                        <select v-model="tipo" id="Tipo" name="Tipo" class="form-control" required>
                                            <option >[SELECCIONE]</option>
                                            <option :value="tipo.TIP_ID" v-for="tipo of listatipo" :key="tipo.TIP_ID">{{ tipo.TIP_DESCRIPCION }}</option>
                                        </select>
                                    </div>       
                                    <div class="col-sm-3 form-group">
                                        <label class="text-primary">Regimen</label>
                                        <select v-model="regimen" id="regimen" name="regimen" class="form-control" required>
                                            <option >[SELECCIONE]</option>
                                            <option :value="regimen.REG_ID" v-for="regimen of listaregimen" :key="regimen.REG_ID" >{{ regimen.REG_NOMBRE }}</option>
                                        </select>
                                    </div>     
                                </div>                               
                                <div class="row">                                          
                                    <div class="col-sm-12 form-group">
                                        <label class="text-primary">Observación</label>
                                        <textarea v-model="observacion" id="observacion" name="observacion" rows="2" class="form-control"></textarea>
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
    props: [ 'url', 'listatiposede', 'listaregional', 'listaestados', 'listadepartamentos', 'listaseccional', 'listatipo', 'listaregimen'],
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
        limpiamunicipio(){
            this.municipiosel = '';
            this.municipios = [];
        },
        onSearchMunicipio(search, loading) {                     
            if($('#departamento').val() != ''){             
                if(search.length) {
                    search = $('#departamento').val()+'||'+search;  
                    loading(true);
                    this.searchMuni(loading, search, this);
                }
            }else{
                $('#departamento').focus();
                return Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Para Buscar el Municipio debe Seleccionar el Departamento'
                });
            }              
            },
            searchMuni: _.debounce((loading, search, vm) => {
            fetch(
                `consultarMunicipios/${escape(search)}`
            ).then(res => {
                res.json().then(json => (vm.municipios = json));
                loading(false);
            });
        }, 350),          
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

        async listaSedes(){
            this.consultaSede  = true;
            await axios.post('listaSedes')
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
        limpiaCampos(){            
            this.limpiamunicipio();
            this.codigo = '';
            this.nombre = '';
            this.direccion = '';
            this.metro = '';
            this.tipoSede = '';
            this.regional = '';
            this.estado = '';
            this.departamento = '';
            this.seccional = '';
            this.tipo = '';
            this.regimen = '';
            this.observacion = '';
            this.accion = 'Crear Sede';
            this.numSede = '';
        }



        

     
        
    },
    computed:{
      
    },
    mounted() {        
        this.listaSedes();
    },
}  
</script>