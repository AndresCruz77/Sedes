<style>
 .modal-body{
  /* max-height:150px; */
  overflow:auto;
 }
.size{
  width: 330px;
}
 
 input[type=checkbox]
{
  /* Doble-tamaño Checkboxes */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari y Chrome */
  -o-transform: scale(2); /* Opera */
  padding: 10px; 
 
}

/* Tal vez desee envolver un espacio alrededor de su texto de casilla de verificación */
.checkboxtexto
{
  /* Checkbox texto */
  font-size: 110%;
  display: inline;
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
                <h3 class="text-center border-bottom-primary p-1">Crear Obligaciones Sede <i class="fas fa-plus text-primary"></i></h3>
            </div>
        </div> 
        <form class="card mb-3" @submit.prevent="guardaRegistro"  id="formCrearObligaciones" >
            <div class="card border-secondary">
                <div class="card-body"> 
                    <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x"><br>                    
                        <div class="container col-sm-12">                         
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label class="text-primary">Sede</label>                                
                                    <v-select v-model="sedesel"  :options="sedes" name="sede" label="SEDE" code="COS_ID" :filterable="false" required placeholder="Digite Codigo o Nombre"   @search="onSearchSede">
                                        <template #search="{attributes, events}"><input  class="vs__search" :required="!sedesel"  v-bind="attributes"  v-on="events" /></template>
                                    </v-select>                                  
                                </div>    
                                <div class="col-sm-6 form-group">
                                    <label class="text-primary">Periodo</label>
                                    <select class="form-control" name="periodo" id="periodo" v-model="periodoSel" @change="limpiaDatos" required>                                    
                                        <option :value="periodos.PER_ID" v-for="periodos of listaperiodos" :key="periodos.PER_ID"  >{{periodos.PER_DESCRIPCION}}</option>                                    
                                    </select>
                                    
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label class="text-primary">Categoria Obligación</label>
                                    <select class="form-control" id="categoria" name="categoria" v-model="categoriaSelc" @change='agregarObligacion()' required>
                                        <option value="">[SELECCIONE]</option>
                                        <option :value="categorias.CAO_ID" v-for="categorias of listacategorias" :key="categorias.CAO_ID" >{{ categorias.CAO_DESCRIPCION }}</option>
                                    </select>
                                </div>                               
                            </div>   
                            <div class="col-md-12 table-responsive">
                                <table class="table table-striped table-hover table-sm"> 
                                    <thead>
                                        <tr>
                                            <th class="col-sm-1">Seleccionar</th>
                                            <th class="col-sm-1">Obligación</th>
                                            <th class="col-sm-3">Tercero</th>
                                            <th class="col-sm-2">Fecha Pago</th>
                                            <th class="col-sm-2"># Referencia</th>
                                            <th class="col-sm-3">Valor</th>                                                                               
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(obliga,index) of listaObliciones" :key="index">
                                            <td>                                            
                                                <div class="form-check text-center">  
                                                    <input class="form-check-input" type="checkbox"  :id="'obliga_'+obliga.OBL_ID" :name="'obliga_'+obliga.OBL_ID" v-model="obliga.OBL_CHECK"    /> <!-- :checked="obliga.OSP_ID" -->
                                                </div>   
                                            </td>
                                            <th scope="row">
                                                <div class="size">
                                                    <div class="alert alert-primary" role="alert">
                                                        {{ obliga.OBL_NOMBRE }}
                                                    </div>
                                                </div>
                                            </th>
                                            <td>
                                                <div class="form-group" v-if="obliga.OBL_RELACIONAR_TERCERO == '1'">                                                
                                                    <v-select   :options="terceros" :id="'tercero_'+obliga.OBL_ID" :name="'tercero_'+obliga.OBL_ID" label="TERCERO" code="TER_ID" :filterable="false"   placeholder="Digite Identificacion o Nombre"   @search="onSearchTercero" v-model="obliga.TERCEROCARG" @input="terceros=[]" >
                                                        <template #search="{attributes, events}"><input  class="vs__search"  v-bind="attributes"  v-on="events" /></template>
                                                    </v-select>  
                                                </div>
                                                <div class="alert alert-primary" role="alert" v-else>
                                                    No Aplica Tercero
                                                </div>                                           
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" :id="'fecha_'+obliga.OBL_ID" :name="'fecha_'+obliga.OBL_ID" v-model="obliga.OSP_FECHA_PAGO"  />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" :id="'ref_'+obliga.OBL_ID" :name="'ref_'+obliga.OBL_ID" v-model="obliga.OSP_REFERENCIA"  />
                                            </td>
                                            <td>
                                                <input class="form-control" :id="'valor_'+obliga.OBL_ID" :name="'valor_'+obliga.OBL_ID" v-model="obliga.VALOR" :title="obliga.OBL_TIPO_DATO"  />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-12">                        
                                    <button type="submit"   class="btn btn-success btn-block"  v-if="!save" > <!-- v-on:click="guardaRegistro()" -->
                                        <span>Guardar Obligaciones</span>
                                    </button>
                                    <button type="button"  class="btn btn-success btn-block" disabled v-else  >
                                        <span>Procesando Información</span>
                                    </button>
                                </div>                                
                            </div>                 
                        </div>
                    </div>
                </div> 
            </div> 
        </form>
    </div>

    


  
    
</template>
<script>
    $('input[type="checkbox"]').on('change', function(e){
        if (this.checked) {
            console.log('Checkbox ' + $(e.currentTarget).val() + ' checked');
        } else {
            console.log('Checkbox ' + $(e.currentTarget).val() + ' unchecked');
        }
    });
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
    props: [ 'url', 'listacategorias', 'listaperiodos', 'fechames'],
    data(){
        return {
            
            sedesel : '',
            sedes : [],          
            periodoSel : '',
            listaObliciones : [],
            terceros : [],
            terceroSelec : [],
            categoriaSelc : '',  
            checksel : [],
            save : false     

        }
    },
    methods: {          
      
       

        onSearchSede(search, loading) {
            this.limpiaDatos();
            if(search.length) {
                loading(true);
                this.searchSede(loading, search, this);
            }
        },
        searchSede: _.debounce((loading, search, vm) => {
            fetch(
                `searchSedes/${escape(search)}`
            ).then(res => {
                res.json().then(json => (vm.sedes = json));
                loading(false);
            });
        }, 350),   

        onSearchTercero(search, loading){
            if(search.length){
                loading(true);
                this.searchTercero(loading, search, this);                
            }
        },
        searchTercero: _.debounce((loading, search, vm) =>{
            fetch(
                `searchTercero/${escape(search)}`
            ).then(res => {
                res.json().then(json => (vm.terceros = json));
                loading(false);        
            });
        }, 350),

        agregarObligacion(){
            if(this.sedesel == ''){
                this.categoriaSelc = '';
                return Swal.fire({
                    icon: 'error',
                    title: 'Error al Buscar las Obligaciones...',
                    text: 'Debe Seleccionar la Sede'
                });
            }
            if(this.periodoSel == ''){
                this.categoriaSelc = '';
                return Swal.fire({
                    icon: 'error',
                    title: 'Error al Buscar las Obligaciones...',
                    text: 'Debe Seleccionar el Periodo'
                });
            }      
            if(this.categoriaSelc == ''){
                this.listaObliciones = [];
                return;
            }                  
            axios.get(`searchObligaciones/${this.sedesel.COS_ID}/${this.periodoSel}/${this.categoriaSelc}`)           
            .then(resp => {
                this.listaObliciones = resp.data
                /* var resultado = resp.data;             
                for (let i = 0; i < resultado.length; i++) {                    
                    this.listaObliciones.push(resultado[i]);                    
                }   */   
            })
            .catch(error => alert(error)) 
       },      

        guardaRegistro(e){  
            this.save = true;
            const datosFormulario = new FormData(e.target);                           
            //datosFormulario.append('municipio',JSON.stringify(this.municipiosel));
            datosFormulario.append('sede',JSON.stringify(this.sedesel));
            datosFormulario.append('obligaciones',JSON.stringify(this.listaObliciones));
            axios.post('guardaObligaciones', datosFormulario)
            .then(resp => {          
                this.save = false; 
                if(resp.data == 0){    
                    return Swal.fire({
                        icon: 'error',
                        title: 'Ups..',
                        text: 'Debe Seleccionar alguna Obligación para continuar.'
                    }); 
                }else{                    
                    return Swal.fire({
                        icon: 'success',
                        title: 'Ok...',
                        text: 'Guardado con Exito!'
                    });                      
                }    
            })            
            .catch(error => {    
                console.log(error);
                this.save = false;
                return Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar el Registro...',
                        text:  'Valide los Datos Ingresados, el separador de decimales es el punto.'
                    })
            });  
        },

        limpiaDatos(){
            this.categoriaSelc = '';
            this.listaObliciones = [];
        }
       

       
     
        
        
    },
    computed:{
        
    },
    mounted() {          
       this.periodoSel = this.listaperiodos[this.listaperiodos.length-1].PER_ID;
    },
}  
</script>