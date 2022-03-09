<template>
    <div class="col-md-12">

        <!-- Modal -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buscador de caso Contraloria</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <form id="formularioBusqueda" class="row">
                            <div class="col-md-8 form-group">
                              <label for="numeroFactura"></label>
                              <input type="text" class="form-control" name="numeroFactura" id="numeroFactura" aria-describedby="helpId" placeholder="Digite consecutivo de caso contraloria" v-model="factura">
                            </div>

                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary btn-md btn-block" v-if="factura.length > 0" v-on:click="buscarFactura()">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </form>

                        <div class="row mt-3 mb-3">
                            <table class="table table-striped table-inverse">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th># id </th>
                                        <th>Nro Factura</th>
                                        <th>Fecha Asignación</th>
                                        <th>Valor Suceptible</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in facturasEncontradas" :key="item.MRS_ID">
                                            <td>{{ item.MRS_ID }}</td>
                                            <td>{{ item.MRS_NRO_FACTURA_UNIFICADO }}</td>
                                            <td>{{ fechaInv(item.MRS_FECHA_CREACION) }}</td>
                                            <td>{{ item.MRS_VALOR_SUSCEPTIBLE }}</td>
                                            <td>{{ item.ESTADO.ERS_NOMBRE }}</td>
                                            <td><div class='row-sm-2 row-sm-2'><button type='button' v-on:click="seleccionarContraloria(item,index)" data-dismiss="modal" class='btn btn-primary btn-block'><span>Consultar</span></button></div></td>
                                        </tr>  
                                    </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>    

        <div class="row justify-content-md-center"  v-show="!RecobroSelect && !cargandoRecobro">
            <div class="col col-lg-2">
                    <div class="group bg-danger text-light rounded">
                    <span class="group-text">                        
                        &nbsp;&nbsp;<i class="fas fa-pen fa-w-20 fa-spin"></i>&nbsp;&nbsp;Pendientes: <b>{{ utilidad.PENDIENTES }} </b>
                    </span>
                </div>           
            </div>             
            <div class="col col-lg-2">
                <div class="group bg-success text-light rounded">
                    <span class="group-text">                        
                        &nbsp;&nbsp;<i class="fas fa-check-circle fa-w-20 "></i>&nbsp;&nbsp;tramitados: <b>{{ utilidad.TRAMITADOS }} </b>
                    </span>
                </div>    
            </div>
        </div>        
        <div class="row" v-show="!RecobroSelect && !cargandoRecobro">
            <div class="col-md-4">
            <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId">
                Consultar Caso
                </button>
            </div>

        <div class="col-md-12 mt-3 mb-3">
                <table id="example" class="table  ">
                    <thead>
                        <tr>
                            <th># id </th>
                            <th>Nro Factura</th>
                            <th>Fecha Asignación</th>
                            <th>Valor Suceptible</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>               
                        <tr v-for="(item,index) in listarecobros" :key="item.MRS_ID">
                            <td>{{ item.MRS_ID }}</td>
                            <td>{{ item.MRS_NRO_FACTURA_UNIFICADO }}</td>
                            <td>{{ fechaInv(item.MRS_FECHA_CREACION) }}</td>
                            <td>{{ item.MRS_VALOR_SUSCEPTIBLE }}</td>
                            <td>{{ item.ESTADO.ERS_NOMBRE }} {{ index }}</td>
                            <td><div class='row-sm-2 row-sm-2'><button type='button' v-on:click="seleccionarContraloria(item,index)" class='btn btn-primary btn-block'><span>Consultar</span></button></div></td>
                        </tr>                                                           
                    </tbody>
                    <tfoot>
                        <tr>
                            <th># id </th>
                            <th>Nro Factura</th>
                            <th>Fecha Asignación</th>
                            <th>Valor Suceptible</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
          
        </div>

        <div class="col-md-12" v-if="RecobroSelect && !cargandoRecobro">

            <div class="col-md-1">
                <a href="javascript:void(0)" v-on:click="RecobroSelect=!RecobroSelect">
                    <i class="fas fa-angle-double-left fa-3x" title="Volver"></i>
                </a>
            </div>

            <contraloria-formulario-visor 
                :datarecobro="RecobroDataSelccionado" 
                :index="indexRecobroSeleccionado" 
                @cambiarEstadoRecobro="cambiarEstadoRecobro">
            </contraloria-formulario-visor> 

            <div class="col-md-12">
                <div class="row justify-content-between">
                    <div class="col-0">
                            <a href="javascript:void(0)" v-on:click="anteriorRecobro" :class="{'d-none' : inicioListaRecobros }">
                            <i class="fas fa-angle-double-left fa-3x" title="Volver"></i>
                        </a>
                    </div>
                
                    <div class="col-0" >
                            <a href="javascript:void(0)" v-on:click="siguienteRecobro" :class="{'d-none' : finListaRecobros}">
                            <i class="fas fa-angle-double-right fa-3x" title="Siguiente"></i>
                        </a>
                    </div>
                </div>
            </div>             

        </div>

        <div class="col-md-12" v-if="!RecobroSelect && cargandoRecobro">

            <h3 class="text-center">Cargando...</h3> 

        </div>       

    </div>
    
</template>
<script>
export default {
    props: ['listarecobros', 'utilidad'],
    data(){
        return {
            facturasEncontradas: [],
            indexRecobroSeleccionado: 0,
            RecobroSelect:false,
            RecobroDataSelccionado:{},
            factura: '',
            cargandoRecobro: false,
        }
    },
    methods: {
       seleccionarContraloria(item,index){
           this.RecobroDataSelccionado=item;
           this.indexRecobroSeleccionado=index;
           this.RecobroSelect=true;
       },
       buscarFactura(){
           axios.get(`visor/${this.factura}`)
           .then(resp => {
              this.facturasEncontradas = resp.data
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
        },
        siguienteRecobro(){            
            this.RecobroSelect = false;
            this.cargandoRecobro = true;
            const nuevoIndex = this.indexRecobroSeleccionado+1;
            const item = this.listarecobros[nuevoIndex];
            setTimeout(() => {
                this.cargandoRecobro = false;
                this.seleccionarRecobro(item,nuevoIndex);
            },300);
            
        },
        anteriorRecobro(){
            this.RecobroSelect = false;
            this.cargandoRecobro = true;
            const nuevoIndex = (this.indexRecobroSeleccionado > 0) ? this.indexRecobroSeleccionado -1 : 0 ;
            const item = this.listarecobros[nuevoIndex];

            setTimeout(() => {
                this.cargandoRecobro = false;
                this.seleccionarRecobro(item,nuevoIndex)           
            }, 300);
            
        },
        cambiarEstadoRecobro(index){            
            this.listarecobros.splice(index,1);
            this.RecobroSelect=false; 
        }
    },
    computed:{
        inicioListaRecobros(){            
            if(this.indexRecobroSeleccionado == 0){
                return true;
            }

            return false;
        },
        finListaRecobros(){
            if((this.indexRecobroSeleccionado+1) >= this.listarecobros.length ){
                return true;
            }

            return false;
        }
    }
}
</script>