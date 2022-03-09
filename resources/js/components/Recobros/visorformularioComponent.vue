<style>
    .select_container{
        width: 300px;
        height: 70px;
        overflow: scroll;
        border: 0px solid #000000;
    }
    .select_container table td{
            overflow: hidden;
    }
    select#selected_name{
        width: auto;
        height: 200px;
        border: none;
        outline: none;
        margin-right: -20px;
        -webkit-margin-end: 0;
        margin-bottom: -3px;
        padding-right: -20px;
        overflow:hidden;
    }
</style>
<template>
    <div class="row">

        <div class="col-md-6">  
            <div class="accordion" id="accordionExample">
                <div class="card" v-for="(documento,index) in datarecobro.DOCUMENTOS" :key="documento.TDR_ID" >
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" :data-target="'#collaps'+documento.TDR_ID" aria-expanded="true" aria-controls="collapseOne">
                        {{ documento.TDR_TIPO_DOC }}
                            </button>
                        </h5>
                    </div>

                    <div :id="'collaps'+documento.TDR_ID" class="collapse" :class="{'show':index==0} " aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <embed :src="'visor-verDocumentos/'+documento.TDR_ID+'#toolbar=0&navpanes=0&scrollbar=0'" width="100%" height="700px" />
                    </div>
                    </div>
                </div>     
            </div>
        </div>    
        <div class="col-md-6">
            <form id="form_recobro">  
                <span class="badge badge-info col-12"><h5>Consecutivo # {{ nuevoDataRecobro.MRS_ID }} </h5></span>
                <span class="badge badge-primary col-12"><h5>Datos Factura</h5></span>
                <br>
                <table class="table table-sm">
                    <tr>
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Nro. Factura</b></label><br>                                       
                                    <input type="text" class="form-control input-lg" id="numFactura" name="numFactura" v-model="nuevoDataRecobro.FACTURA.FRS_NRO_FACTURA">
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Fecha Factura</b></label><br>   
                                    <input type="date" class="form-control input-sm-4" id="fechFactura" name="fechFactura" v-model="nuevoDataRecobro.FACTURA.FRS_FECHA_FACTURA" >
                                </div>
                            </div>                           
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label  class="control-label text-primary" ><b>Nit IPS</b></label><br>                                       
                                    {{ datarecobro.MRS_NIT_IPS }}
                                </div>
                                <div class="col">
                                    <label  class="control-label text-primary"><b>Nombre IPS</b></label><br>   
                                    {{ datarecobro.MRS_NIT_IPS }}
                                </div>
                            </div>                           
                        </th>
                    </tr>       
                </table>
                <span class="badge badge-primary col-12"><h5>Datos Servicio</h5></span>
                <br>
                <table class="table table-sm">                    
                    <tr>    
                        <th colspan="4">
                            <div class="input-group col">
                                <div class="col-md-12">
                                    <label for="" class="control-label text-primary"><b>Tecnologia</b></label>
                                </div>                                                              
                                <input type="text" name="tecnologia" id="tecnologia" class="form-control sm-4" autocomplete="off" placeholder="Buscar Tecnologia por Nombre o Codigo" @keyup="buscarTecnologia()"  v-model="nombreTecnologia" v-if="!tecnologiaSeleccionada.TCU_PRODUCTO" style="font-size: 8pt; font-color:black; font-weight: bold;"  />                                                        
                                <input type="text" class="form-control sm-4" autocomplete="off" :value="tecnologiaSeleccionada.TCU_PRODUCTO" v-else v-on:keyup="tecnologiaSeleccionada={}" style="font-size: 8pt; font-color:black; font-weight: bold;" />
                                <div class="input-group-append" v-if="cargandoTecnologia">
                                    <span class="input-group-text">
                                        <i class="fas fa-spinner fa-w-16 fa-spin"></i>
                                    </span>
                                </div>

                                <div class="col-md-12 select_container" v-if="listaTecnologias.length && !tecnologiaSeleccionada.TCU_PRODUCTO"  >
                                    <select size="4" id="selected_name" class="form-control" v-if="listaTecnologias.length && !tecnologiaSeleccionada.TCU_PRODUCTO" v-model="tecnologiaSeleccionada" style="font-size: 8pt; font-color:black; font-weight: bold;">                                     
                                    <option :value="tecnologia" v-for="tecnologia of listaTecnologias" :key="tecnologia.TCU_CUMS_MINISTERIO">{{ tecnologia.TCU_CUMS_MINISTERIO}} -- {{ tecnologia.TCU_PRODUCTO}}</option></select>
                                </div>                                    
                            </div>
                        </th>
                    </tr> 
                    <tr>
                        <th colspan="4">
                            <div class="row">                                
                                <div class="input-group col">   
                                        <div class="col-md-12" v-if="tecnologiaSeleccionada.TCU_CUMS_MINISTERIO"> 
                                            <label for="" class="control-label text-primary" ><b>Codigo Tecnologia</b></label><br>   
                                            <input type="text" class="form-control sm-4" :value="tecnologiaSeleccionada.TCU_CUMS_MINISTERIO" style="font-size: 8pt; font-color:black; font-weight: bold;" disabled>
                                        </div>                                            
                                </div>
                            </div>    


                            
                        </th>    
                    </tr>   
                    <tr>
                        <th colspan="4">
                             <div class="row">                                
                                <div class="input-group col">
                                    <div class="col-md-12">
                                        <label for="" class="control-label text-primary" ><b>Nro. Autorización</b></label>
                                    </div>                          
                                    <input type="text" class="form-control input-lg" id="numAutori" name="numAutori" v-model="nuevoDataRecobro.SERVICIO.SRS_NRO_AUTORIZACION" @change="busqAutoriza()"    > <!-- v-on:click="busqAutoriza()" :disabled="nuevoDataRecobro.AUTORIZACION.ARS_ID != '' "  -->
                                    <div class="input-group-append" v-if="cargando">
                                        <span class="input-group-text">
                                            <i class="fas fa-spinner fa-w-16 fa-spin"></i>
                                        </span>
                                    </div>
                                </div>       
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Fecha Prestación</b></label><br>   
                                    <input type="date" class="form-control input-sm-4" id="fechPrestacion" name="fechPrestacion" v-model="nuevoDataRecobro.SERVICIO.SRS_FECHA_PRESTACION">
                                </div>                         
                            </div>                           
                        </th>
                    </tr>
                    <tr>    
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Cantidad</b></label><br>   
                                    <input type="number" class="form-control input-lg" id="cantServicio" name="cantServicio" v-model="nuevoDataRecobro.SERVICIO.SRS_CANTIDAD"  >
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Valor Unitario</b></label><br>   
                                    <input type="number" class="form-control input-lg" id="unitaServicio" name="unitaServicio" v-model="nuevoDataRecobro.SERVICIO.SRS_VALOR_UNITARIO">
                                </div>
                            </div>                           
                        </th>
                    </tr> 
                    <tr>    
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Valor Total</b></label><br>   
                                    <input type="number" class="form-control input-lg" id="totalServicio" name="totalServicio" v-model="nuevoDataRecobro.SERVICIO.SRS_VALOR_TOTAL">
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Cuota Moderadora Copago</b></label><br>   
                                    <input type="number" class="form-control input-lg" id="cuotaMode" name="cuotaMode" v-model="nuevoDataRecobro.SERVICIO.SRS_CUOTA_MODERADORA_COPAGO">
                                </div>
                            </div>                           
                        </th>
                    </tr>                 
                    <tr>    
                        <th colspan="4">
                            <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Tipo Identificación</b></label><br>                                                                        
                                      <select class="form-control" name="tipDocServicio" id="tipDocServicio" v-model="nuevoDataRecobro.SERVICIO.SRS_TIPO_ID_USUARIO">
                                        <option value="">SELECCIONE</option>                                        >                                        
                                        <option value="CC">CC</option>
                                        <option value="CD">CD</option>
                                        <option value="CE">CE</option>
                                        <option value="CN">CN</option>
                                        <option value="IN">IN</option>
                                        <option value="MG">MG</option>
                                        <option value="MS">MS</option>
                                        <option value="NU">NU</option>
                                        <option value="PA">PA</option>
                                        <option value="PE">PE</option>
                                        <option value="RC">RC</option>
                                        <option value="SC">SC</option>
                                        <option value="TI">TI</option>
                                      </select>                                    
                                </div>      
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Numero Identificación</b></label><br>   
                                    <input type="number" class="form-control input-lg" id="numIdentServicio" name="numIdentServicio" v-model="nuevoDataRecobro.SERVICIO.SRS_NRO_ID_USUARIO">
                                </div>
                            </div>                           
                        </th>
                    </tr>                 
                </table>
                <span class="badge badge-primary col-12"><h5>Datos Autorizacion</h5></span>
                 <br>
                <table class="table table-sm"> 
                    <tr>    
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Num, Entrega</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="numEntAuto" name="numEntAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_NUMERO_ENTREGA" disabled >
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Id Direccionameinto</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="idDireccAuto" name="idDireccAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_ID_DIRECCIONAMIENTO" disabled>
                                </div>
                            </div>                           
                        </th>
                    </tr>   
                    <tr>    
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Nit IPS Remite</b></label><br>
                                    <input type="text" class="form-control input-lg" id="nitIpsRemiAuto" name="nitIpsAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_NIT_IPS_REMITE" disabled>
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Nombre IPS Remite</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="nomIpsRemiAuto" name="nomIpsAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_NOMBRE_IPS_REMITE" disabled>
                                </div>
                            </div>                           
                        </th>
                    </tr>       
                    <tr>    
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Fecha Autorización</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="fechAuto" name="fechAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_FECHA_AUTORIZACION" disabled>
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Cantidad</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="cantAuto" name="cantAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_CANTIDAD" disabled>
                                </div>
                            </div>                           
                        </th>
                    </tr>       
                    <tr>    
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Codigo Tecnología</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="codTecnAuto" name="codTecnAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_CODIGO_TECONOLOGIA" disabled>
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Num. Prescripción</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="numPrescAuto" name="numPrescAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_NUMERO_PRESCRIPCION" disabled>
                                </div>
                            </div>                           
                        </th>
                    </tr>       
                    <tr>    
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Tipo Documento Afiliado</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="tipDocAfiAuto" name="tipDocAfiAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_TIPO_DOC_AFILIADO" disabled>
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Num. Documento Afiliado</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="numDocAfiAuto" name="numDocAfiAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_NUM_DOC_AFILIADO" disabled>
                                </div>
                            </div>                           
                        </th>
                    </tr> 
                    <tr>    
                        <th colspan="4">
                            <div class="accordion" id="accordionInconsistencias">
                                <div class="card"  >
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            
                                           <!--  <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsInco" aria-expanded="true" aria-controls="collapseOne">
                                                Diligenciar Inconsistencias
                                            </button> -->


                                             <button type="button"  class="btn btn-primary col-12" data-toggle="collapse" data-target="#collapsInco" aria-expanded="true" aria-controls="collapseOne">
                                            Diligenciar Inconsistencias
                                            </button>
                                            
                                        </h5>
                                    </div>
                                    <div id="collapsInco" class="collapse"  aria-labelledby="headingOne" data-parent="#accordionInconsistencias">
                                        <div class="card-body">                                            
                                            <div class="col-md-12">                                                        
                                                <div v-for="(documento) in datarecobro.INCONSISTENCIAS" :key="documento.TDR_ID" >
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1" :id="'doc_'+documento.TDR_ID" :checked="documento.IRS_ID == 1" v-model="documento.IRS_ESTADO_ID">
                                                        <label class="form-check-label" for="defaultCheck1">{{ documento.TDR_TIPO_DOC }}</label>
                                                    </div>                                                   
                                                </div>
                                                <br>
                                                <div>
                                                    <label for="" class="control-label text-primary"><b>Observaciones</b></label>
                                                </div>                               
                                                <div>                                                
                                                    <textarea id="obsInco" class="form-control" name="obsInco" v-model="nuevoDataRecobro.MRS_OBSERVACION_INCONSISTENCIA"></textarea>
                                                </div>                                    
                                            </div>

                                        </div>
                                    </div>
                                </div>    
                            </div>                                                                                           
                        </th>
                    </tr>        




                  <!--   <tr>    
                        <th colspan="4">
                             <div class="row">
                                <div class="col">
                                    <label for="" class="control-label text-primary" ><b>Postfechada</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="postfAuto" name="postfAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_POSTFECHADA" disabled>
                                </div>
                                <div class="col">
                                    <label for="" class="control-label text-primary"><b>Fecha Posible Entrega</b></label><br>   
                                    <input type="text" class="form-control input-lg" id="fechEntregAuto" name="fechEntregAuto" v-model="nuevoDataRecobro.AUTORIZACION.ARS_FECHA_POSIBLE_ENTREGA" disabled>
                                </div>
                            </div>                           
                        </th>
                    </tr>        -->                     
                </table>    
                <div class="form-row">
                    <div class="form-group col-md-12 col-sm-12">                        
                        <button type="button"  class="btn btn-success btn-block" v-on:click="guardaRegistro()" v-if="!cargando" >
                            <span>Guardar</span>
                        </button>
                        <button type="button"  class="btn btn-success btn-block" disabled v-else  >
                            <span>Procesando Información</span>
                        </button>
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                        <div id="resultado"></div>
                    </div>
                </div>
            </form>
        </div>            
    </div>
</template>
<script>
import Swal from 'sweetalert2';
export default {
    props:['datarecobro','index'],
    data(){
        return {
            cargando: false,
            nuevoDataRecobro: {
                AUTORIZACION:{
                   
                },
                FACTURA:{

                },
                SERVICIO:{

                },
                INCONSISTENCIAS:{

                },
                ESTADO:{

                }
            },
            nombreTecnologia:'',
            CodigoTecnologia:'',
            listaTecnologias: [],
            cargandoTecnologia: false,
            tecnologiaSeleccionada: {},   
        }
    },
    methods:{     
        setDataRecobro(){
            this.nuevoDataRecobro = {...this.datarecobro},
            this.tecnologiaSeleccionada.TCU_CUMS_MINISTERIO = this.datarecobro.SERVICIO.SRS_CODIGO_TECNOLOGIA 
            this.tecnologiaSeleccionada.TCU_PRODUCTO = this.datarecobro.SERVICIO.SRS_NOMBRE_TECNOLOGIA           
        },
        busqAutoriza(){     
            this.cargando = true;      
            /* alert(event.target.value);*/


            axios.post(`visor-autorizacion`,{
                numauto: this.nuevoDataRecobro.SERVICIO.SRS_NRO_AUTORIZACION
            })
           .then(resp => {
               this.cargando = false;
               if(!resp.data.rptIdDireccionamiento){
                    return Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Número de autorizacion no encontrado!'
                    })
               }
               //console.log(resp.data.rptTipoIDPaciente);
               this.nuevoDataRecobro.AUTORIZACION.ARS_NUMERO_ENTREGA = resp.data.rptNoEntrega;
               this.nuevoDataRecobro.AUTORIZACION.ARS_ID_DIRECCIONAMIENTO = resp.data.rptIdDireccionamiento;               
               this.nuevoDataRecobro.AUTORIZACION.ARS_NIT_IPS_REMITE = resp.data.ipsNitIps;               
               this.nuevoDataRecobro.AUTORIZACION.ARS_NOMBRE_IPS_REMITE = resp.data.ipsNombre;
               this.nuevoDataRecobro.AUTORIZACION.ARS_FECHA_AUTORIZACION = resp.data.FechaAutorizacion;
               this.nuevoDataRecobro.AUTORIZACION.ARS_CANTIDAD = resp.data.rptCanToAEntregar;
               this.nuevoDataRecobro.AUTORIZACION.ARS_NUMERO_PRESCRIPCION = resp.data.rptNoPrescripcion;
               this.nuevoDataRecobro.AUTORIZACION.ARS_TIPO_DOC_AFILIADO = resp.data.rptTipoIDPaciente;
               this.nuevoDataRecobro.AUTORIZACION.ARS_NUM_DOC_AFILIADO = resp.data.rptNoIDPaciente;                               
               this.nuevoDataRecobro.AUTORIZACION.ARS_CODIGO_TECONOLOGIA = resp.data.rptCodSerTecAEntregar;      
               /* this.nuevoDataRecobro.AUTORIZACION.ARS_POSTFECHADA = resp.data.rptIdDireccionamiento;
               this.nuevoDataRecobro.AUTORIZACION.ARS_FECHA_POSIBLE_ENTREGA = resp.data.rptIdDireccionamiento;     */
           })
           .catch(error => {
               alert(error);
               this.cargando = false;
            })  
        },
        guardaRegistro(){   
            if(( this.nuevoDataRecobro.SERVICIO.SRS_CANTIDAD *  this.nuevoDataRecobro.SERVICIO.SRS_VALOR_UNITARIO) != this.nuevoDataRecobro.SERVICIO.SRS_VALOR_TOTAL){
                Swal.fire({
                    icon: 'info',
                    title: 'la Operacion de cantidad y Valor Unitario',
                    text: 'No es correcta de acuerdo al valor del Valor Total '
                })                  
            }
            setTimeout(function () {
                this.cargando = true; 
                /* alert('llega');
                const datos = new FormData(document.getElementById('form_recobro')); */
                axios.post(`visor-guardaRegRecobro`,{...this.nuevoDataRecobro,...this.tecnologiaSeleccionada})
                .then(resp => {
                    this.cargando = false;   
                    if(resp.data.FACTURAID && resp.data.FACTURAID.FRS_ID){
                        this.nuevoDataRecobro.FACTURA.FRS_ID = resp.data.FACTURAID.FRS_ID;
                    }
                    if(resp.data.SERVICIOID && resp.data.SERVICIOID.SRS_ID){
                        this.nuevoDataRecobro.SERVICIO.SRS_ID = resp.data.SERVICIOID.SRS_ID;
                    }
                    if(resp.data.AUTORIZACIONID && resp.data.AUTORIZACIONID.ARS_ID){
                        this.nuevoDataRecobro.AUTORIZACION.ARS_ID = resp.data.AUTORIZACIONID.ARS_ID;
                    }
                    if(resp.data.REMOVER == 1){
                        this.$emit('cambiarEstadoRecobro',this.index);
                    }
            
                    

                    return Swal.fire({
                        icon: 'success',
                        title: 'Ok...',
                        text: 'Guardado con Exito'
                    });
                    
                })
                .catch(error => {   
                    this.cargando = false;          
                    return Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar el Registro...',
                        text: error
                    })
                })
            }.bind(this), 1000)      
        },
        buscarTecnologia(){
            if(this.nombreTecnologia.length > 3){
                this.cargandoTecnologia = true;
                axios.post(`visor-consultaTecnologias`,{nombreTecnologia:this.nombreTecnologia})
                .then(resp =>{
                    this.cargandoTecnologia = false;
                    this.listaTecnologias = resp.data;                                    
                })
                .catch(error => {
                    this.cargandoTecnologia = false;
                    this.listaTecnologias = [];
                })
            }
            this.listaTecnologias = [];            
        }    
    },   
    mounted(){
        this.setDataRecobro();
        
    }
}
</script>