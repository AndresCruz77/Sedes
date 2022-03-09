<style>
    h1 { font-size: 26px; text-align: center; margin-top: 40px; margin-bottom: 20px;}
        .panel-default>.panel-heading { /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#0081c9+1,0081c9+100 */
        background: #0081c9; /* Old browsers */
        background: -moz-linear-gradient(-45deg, #0081c9 1%, #0081c9 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(-45deg, #0081c9 1%,#0081c9 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(135deg, #0081c9 1%,#0081c9 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0081c9', endColorstr='#0081c9',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
        color: #ffffff; padding: 18px;}
        .panel { box-shadow: 0px 0px 60px 0px rgba( 0, 0, 0, .40 );}
        .panel-group .panel { margin-bottom: 20px; border: 1px solid #0081c9; 
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#0081c9+1,0081c9+100 */
        border-color: #0081c9; /* Old browsers */
        border-color: -moz-linear-gradient(-45deg, #0081c9 1%, #0081c9 100%); /* FF3.6-15 */
        border-color: -webkit-linear-gradient(-45deg, #0081c9 1%,#0081c9 100%); /* Chrome10-25,Safari5.1-6 */
        border-color: linear-gradient(135deg, #0081c9 1%,#0081c9 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0081c9', endColorstr='#0081c9',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */}
        .panel-title { font-size: 18px;}
        .panel-title a, .panel-title a:hover, .panel-title a:focus { text-decoration: none;}
        .panel-body p { font-size: 16px;
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
                <h3 class="text-center border-bottom-primary p-1">Gestionar Anticipo Medico <i class="fas fa-file-medical text-black-300"></i></h3>
            </div>
        </div> 

        <form class="card mb-3 was-validated" @submit.prevent="guardaRegistro"  id="formCrearAnticipo" enctype="multipart/form-data">
            <div class="card border-secondary">
                <div class="card-body"> 
                    <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x"><br>
                        <!-- blocket -->    
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label for="estaAnticipo" class="text-primary">Estado Anticipo</label>
                                    <select id="estaAnticipo" name="estaAnticipo" class="form-control" v-model="estadoAnticiposel"  required>
                                            <option value="" selected>[SELECCIONE]</option>
                                            <option :value="estdanticipo.EXA_ID" v-for="estdanticipo of listaestados" :key="estdanticipo.EXA_ID"  :selected="estdanticipo.EXA_ID === '1'"  >{{ estdanticipo.EXA_DESCRIPCION }}</option>                                            
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione el Estado del Anticipo
                                    </div> 
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="obsEstado" class="text-primary">Observación al Estado</label>
                                    <select id="obsEstado" name="obsEstado" class="form-control" required>
                                        <option value="" selected >[SELECCIONE]</option>
                                        <option :value="obsestado.LOE_ID" v-for="obsestado of listaobsestado.filter(  oestado => oestado.LOE_EXA_ID == estadoAnticiposel )" :key="obsestado.LOE_ID" selected >{{ obsestado.LOE_DESCRIPCION }}</option>                                        
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione la Observación del Estado
                                    </div>                        
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="regSolicita" class="text-primary">Regional Solicitante</label>
                                    <select id="regSolicita" name="regSolicita" class="form-control" required>
                                            <option value="">[SELECCIONE]</option>                                        
                                            <option :value="regional.RNL_ID" v-for="regional of listaregional" :key="regional.RNL_ID" >{{ regional.RNL_NOMBRE }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione la Regional Solicitante
                                    </div> 
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="seccional" class="text-primary">Seccional</label>
                                    <select id="seccional" name="seccional" class="form-control" required>
                                        <option value="" >[SELECCIONE]</option>                                                                                                                                            
                                        <option :value="seccional.GEO_ID" v-for="seccional of listaseccional" :key="seccional.GEO_ID" >{{ seccional.GEO_SECCIONAL }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione la Seccional
                                    </div>                         
                                </div>       
                                <div class="col-sm-12 form-group">                                    
                                    <label for="respSolicitud" class="text-primary col-6">Responsable de la Solicitud</label>                                   
                                    <v-select v-model="responselecc"  :options="responsables" name="responsable" label="EMD_NOMBRES" code="EMD_ID" :filterable="false" placeholder="Digite Nombre o # Identificacion"   @search="onSearchResponsable">
                                        <template #search="{attributes, events}"><input  class="vs__search" :required="!responselecc" v-bind="attributes"  v-on="events" /></template>
                                    </v-select>                                   
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label for="municipio" class="text-primary">Municipio</label>
                                    <v-select v-model="municipiosel"  :options="municipios" name="municipio"  label="GEO_MUNICIPIO" code="GEO_ID" :filterable="false" required placeholder="Digite Municipio"   @search="onSearchMunicipio">
                                        <template #search="{attributes, events}"><input  class="vs__search" :required="!municipiosel" v-bind="attributes"  v-on="events" /></template>
                                    </v-select>                                    
                                    
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label for="prestadorAcuerdo" class="text-primary">Prestador</label>
                                    <v-select v-model="prestadorsel"  :options="prestadores" name="prestador" label="PRESTADOR" code="ipsID" :filterable="false" required placeholder="Digite Nombres o # Nit"   @search="onSearchPrestador">
                                        <template #search="{attributes, events}"><input  class="vs__search" :required="!prestadorsel" v-bind="attributes"  v-on="events" /></template>
                                    </v-select>                                    
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="regimen" class="text-primary">Regimen</label>
                                    <select id="regimen" name="regimen" class="form-control" required>
                                            <option value="">[SELECCIONE]</option>     
                                            <option :value="regimen.REG_ID" v-for="regimen of listaregimen" :key="regimen.REG_ID" >{{ regimen.REG_NOMBRE }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione el Regimen
                                    </div> 
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="origAnticipo" class="text-primary">Origen Anticipo</label>
                                    <select id="origAnticipo" name="origAnticipo" class="form-control" required>
                                        <option value="" >[SELECCIONE]</option>
                                        <option :value="origen.OXA_ID" v-for="origen of listaorigen" :key="origen.OXA_ID" >{{ origen.OXA_DESCRIPCION }}</option>
                                                                                
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione el Origen del Anticipo
                                    </div>                         
                                </div>                                
                                <div class="col-sm-12 form-group">
                                    <label for="prestadorAcuerdo" class="text-primary">Afiliado EPS</label>
                                    <v-select v-model="afiliadosel"  :options="afiliados" name="afiliado" label="NombreCompleto" code="NumDocAfiliado" :filterable="false" required placeholder="# Identificación"   @search="onSearchAfiliado">
                                        <template #search="{attributes, events}"><input  class="vs__search" :required="!afiliadosel" v-bind="attributes"  v-on="events" /></template>
                                    </v-select>                                    
                                </div>                                                                                        
                                <div class="col-sm-6 form-group">
                                    <label for="tipOrdJud" class="text-primary">Tipo Orden Judicial</label>
                                    <select id="tipOrdJud" name="tipOrdJud" class="form-control" required>
                                            <option value="">[SELECCIONE]</option>    
                                            <option :value="judicial.TOJ_ID" v-for="judicial of listatipoordenj" :key="judicial.TOJ_ID" :selected="judicial.TOJ_ID == 1" >{{ judicial.TOJ_DESCRIPCION }}</option>                                            
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione el Tipo Orden Judicial
                                    </div> 
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="motAnticipo" class="text-primary">Motivo Anticipo</label>
                                    <select id="motAnticipo" name="motAnticipo" class="form-control" required>
                                        <option value="" >[SELECCIONE]</option>
                                        <option :value="motivo.MXA_ID" v-for="motivo of listamotivo" :key="motivo.MXA_ID" >{{ motivo.MXA_DESCRIPCION }}</option>                                                                                
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione el Motivo del Anticipo
                                    </div>                         
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="valorCruce" class="text-primary">Valor Cruce</label>
                                    <input class="form-control" id="valorCruce" name="valorCruce" type="number" required>                                    
                                    <div class="invalid-feedback">
                                        Digite el Valor Cruce
                                    </div>                         
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="valorBruto" class="text-primary">Valor Bruto a Pagar</label>
                                    <input class="form-control" id="valorBruto" name="valorBruto" type="number" required>                                    
                                    <div class="invalid-feedback">
                                        Digite el Valor Bruto a Pagar
                                    </div>                         
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label class="text-primary">Valor Total Cotizado</label>
                                    <input class="form-control" id="valorTotalCoti" name="valorTotalCoti" type="number" required>                                    
                                    
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label class="text-primary">Saldo por Legalizar del Prestador</label>
                                    <div class="input-group rounded">                                    
                                        <input type="search" id="saldoLegalizar" name="saldoLegalizar" class="form-control rounded" required>
                                        <span class="input-group-text border-0" id="search-addon" >                                                                                
                                            <i class="fas fa-spinner fa-w-16 fa-spin" v-if="cargando"></i>
                                            <i class="fas fa-search" @click="searchSaldo" v-else></i>                                            
                                        </span>
                                    </div>                                   
                                </div>                                    
                                <div class="col-sm-12 form-group">
                                    <label for="DiagnosticoPrincipal" class="text-primary">Diagnóstico Principal</label>
                                    <v-select v-model="diagPrincsel"  :options="diagnosticos" name="diagPrincsel" label="DIAGNOSTICO" code="DIA_ID" :filterable="false" id="DiagPrinc" required placeholder="Digite Codigo o Nombre"   @search="onSearchDiagnostico" @input="diagnosticos=[]">
                                        <template #search="{attributes, events}"><input  class="vs__search" :required="!diagPrincsel" v-bind="attributes"  v-on="events" /></template>
                                    </v-select>   
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label for="DiagnosticoSecundario" class="text-primary">Diagnóstico Secundario</label>
                                    <v-select v-model="diagSecucsel"  :options="diagnosticos" name="diagSecucsel" label="DIAGNOSTICO" code="DIA_ID" :filterable="false" id="DiagSecun" required placeholder="Digite Codigo o Nombre"   @search="onSearchDiagnostico" @input="diagnosticos=[]">
                                        <template #search="{attributes, events}"><input  class="vs__search" :required="!diagSecucsel" v-bind="attributes"  v-on="events" /></template>
                                    </v-select>                    
                                </div>
                            </div>                        
                        </div>
                    </div>   
                </div>
            </div>
            <div class="card border-secondary">
                <div class="card-body"> 
                    <div class="row">		
                        <div class="col-md-12">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                        <a class="collapsed text-light" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Consultar Autorización
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">   
                                            <div class="row p-2">                     
                                                <div class="col-sm-6 form-group">
                                                    <label for="numAutorizacion" class="text-primary">Número de Autorización</label>                                                    
                                                    <input type="search" id="numAutorizacion" name="numAutorizacion" class="form-control col-6" v-model="numautoriza" >                                                                                                                                                           
                                                </div>    
                                                <div class="col-sm-6 form-group">
                                                    <label for="numMipres" class="text-primary">Número Mipres</label>
                                                    <div class="input-group rounded">                                    
                                                        <input type="search" id="numMipres" name="numMipres" class="form-control col-6">
                                                        <!-- <span class="input-group-text border-0" id="search-addon">
                                                            <i class="fas fa-search"></i>
                                                        </span> -->
                                                        <span class="input-group-text border-0" v-if="numautoriza.length > 2" >                                                                                
                                                            <i class="fas fa-spinner fa-w-16 fa-spin" v-if="cargandoAuto"></i>
                                                            <i class="fas fa-search" @click="onSearchAutoriza" v-else></i>                                            
                                                        </span>
                                                    </div>                                   
                                                </div>    
                                            </div>    
                                            <br>
                                            <table class="table table-striped table-hover"> 
                                                <thead> 
                                                    <tr>                                                         
                                                        <th>Autorización</th> 
                                                        <th>Código Heon</th> 
                                                        <th>Código CUMS/CUPS</th>                                                         
                                                        <th>Código Insumo</th>
                                                        <th>Tipo Identificación</th>
                                                        <th>Num. Identificación</th> 
                                                        <th>Nombre</th> 
                                                        <th>Cantidad</th> 
                                                        <th>Valor</th> 
                                                    </tr> 
                                                </thead> 
                                                <tbody> 
                                                    <tr v-for="(item) in autorizaciones" :key="item.AUTIDDETALLEAUTORIZACION">                                                                                                            
                                                        <th scope="row">{{item.NUMAUTORIZACION}}</th> 
                                                        <td >{{item.CODIGOTECNOLOGIA}}</td> 
                                                        <td >{{item.CODIGO_CUMS_CUPS}}</td> 
                                                        <td >{{item.CIE10}}</td> 
                                                        <td >{{item.TIPODOCUMENTO}}</td> 
                                                        <td >{{item.NUMDOCUMENTO}}</td> 
                                                        <td >{{item.NOMBRE_COMPLETO}}</td> 
                                                        <td >{{item.CANTIDAD}}</td> 
                                                        <td >{{item.VLRAUTORIZACION}}</td> 
                                                    </tr> 
                                    
                                                </tbody> 
                                            </table>
                                        </div>
                                    </div>
                                </div>
			                </div>
                            <div class="panel-group" id="accordion_documentos" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                        <a class="collapsed text-light" role="button" data-toggle="collapse" data-parent="#accordion_documentos" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                        Adjuntar Documentos
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <div class="row p-2">                                            
                                                <div class="col-sm-3 form-group">
                                                    <label for="regSolicita" class="text-primary">Soporte de la Solicitud</label>
                                                    <select id="regSolicita" class="form-control" v-model="soporteSolicitud" @change='agregarAdjunto()'>                                                                                                                                             
                                                            <option v-for="(item,index) in listaAdjuntosFiltrada" :key="index"   :value="item">{{ item.TXA_DESCRIPCION }}</option>                                                                        
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Seleccione el Soporte de la Solicitud
                                                    </div> 
                                                </div>                                               
                                                <br>
                                                <div class="col-md-12 table-responsive">
                                                     <table class="table table-striped"> 
                                                        <thead>
                                                            <tr>
                                                                <th>Soporte</th>
                                                                <th>Adjunto</th>
                                                                <th>Conforme</th>
                                                                <th>Observación</th>
                                                                <th>Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(adjunto,index) of listaAdjuntos" :key="index">
                                                                <th scope="row">{{ adjunto.TXA_DESCRIPCION }}</th>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <!-- <label :for="adjunto.nombreTipoAdjunto">Tipo de archivo</label> -->
                                                                        <input type="file" required :name="adjunto.TXA_ID" :id="adjunto.TXA_DESCRIPCION" class="form-control" accept="application/pdf">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">                                                                     
                                                                        <select class="form-control" :id="'conforme_'+adjunto.TXA_ID" :name="'conforme_'+adjunto.TXA_ID" required>
                                                                            <option value="">[SELECCIONE]</option>
                                                                            <option value="SI">SI</option>
                                                                            <option value="NO">NO</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">                                                                     
                                                                        <textarea class="form-control" :id="'observ_'+adjunto.TXA_ID" :name="'observ_'+adjunto.TXA_ID"></textarea>
                                                                    </div>
                                                                </td>
                                                                <td>     
                                                                    <button class="btn btn-danger btn-sm rounded-0 p-2" @click="quitarAdjunto(index)" type="button" data-toggle="tooltip" v-if="adjunto.TXA_REQUIRED != '1'" data-placement="top" title="Delete">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>                                                           
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
			                </div>
                            <div class="col-sm-12 form-group">
                                <label for="municipio" class="text-primary">Observación</label>                                                                        
                                <textarea id="ObserGen" name="ObserGen" class="form-control" row="4" ></textarea>                                    
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">                        
                                    <button type="submit"   class="btn btn-success btn-block"  v-if="!save" > <!-- v-on:click="guardaRegistro()" -->
                                        <span>Guardar Anticipo</span>
                                    </button>
                                    <button type="button"  class="btn btn-success btn-block" disabled v-else  >
                                        <span>Procesando Información</span>
                                    </button>
                                </div>
                                <div class="form-group col-md-12 col-sm-12">
                                    <div id="resultado"></div>
                                </div>
                            </div>
		                </div>
	                </div>
                </div>
            </div>        
        </form> 
          <!-- modales  -->
        <div class="modal fade" id="modelResponsable" role="dialog">
            <div class="modal-dialog" role="document" style="max-width:95%;" > 
            <!-- Modal contenido-->
                <!-- <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Buscar Responsable</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="busqueda_prestador">Ingrese Nombre o Identificacion del Responsable</label>
                                    <input type="text" name="busqueda_prestador" id="busqueda_prestador" class="form-control" placeholder="Ingrese 4 caracteres para buscar" @keyup="busquedaResponsable">
                                </div>
                                <div class="col-md-12 mt-2" v-show="prestadores">
                                    <select class="form-control" name="lista_prestadores" id="lista_prestadores" size="10" v-model="responsableSeleccionado" data-dismiss="modal" @change="seleccionarResponsable">
                                        <option value="" selected disabled></option>
                                        <option :value="responsable" v-for="(responsable,index) of responsables" :key="index">{{ responsable.EMD_NOMBRES }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div> -->
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
    props: ['listadocumentos', 'listaestados', 'url', 'listaobsestado', 'listaregimen', 'listamotivo', 'listatipoordenj', 'listaorigen', 'listaregional', 'listaseccional'],
    data(){
        return {
            cargando: false,
            cargandoAuto: false,
            soporteSolicitud: null,   
            listaAdjuntos: [],
            estadoAnticiposel: 6,            
            responsables: [],
            prestadores: [],
            responselecc: null,
            municipiosel: null,
            municipios: [],
            prestadorsel: null,
            diagPrincsel: null,
            diagSecucsel: null,
            afiliadosel: null,
            diagnosticos: [],
            numautoriza: '',
            autorizaciones: [],
            afiliados : [],
            save : false

            

              

        }
    },
    methods: {
        agregarAdjunto(){
            //console.log(this.soporteSolicitud);
            // Agregar el nuevo procedimiento a la lista de remisiones
            if(this.soporteSolicitud){
                this.listaAdjuntos.push({...this.soporteSolicitud});
            }
            
            // Limpiar objeto de nuevo adjunto
            //this.soporteSolicitud = [];
        },        
        quitarAdjunto(index){// Permite quitar de lista de procedimientos
            Swal.fire({
                title: '¿Quiere eliminar el adjunto seleccionado?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                denyButtonText: `No, no eliminar`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // Eliminar objeto de la lista de remisiones
                    this.listaAdjuntos.splice(index,1);
                    Swal.fire('Eliminado', 'Adjunto eliminado', 'success')
                }
            });
        },
        activarSoportes(){
            var docRequeridos = this.listadocumentos.filter(documento => documento.TXA_REQUIRED == '1' );
            this.listaAdjuntos = docRequeridos;
            //console.log(docRequeridos);              
        },
        seleccionarResponsable(){
            this.acuerdo.prestadorNit = this.responsableSeleccionado.IPS_NIT;
            this.acuerdo.prestadorNombre = this.responsableSeleccionado.IPS_NOMBRE;
        },       
        onSearchResponsable(search, loading) {            
            if(search.length) {
                loading(true);
                this.search(loading, search, this);
            }
            },
            search: _.debounce((loading, search, vm) => {
            fetch(
                `consultarResponsable/${escape(search)}`
            ).then(res => {
                res.json().then(json => (vm.responsables = json));
                loading(false);
            });
        }, 350),
        onSearchMunicipio(search, loading) {
            if(search.length) {
                loading(true);
                this.searchMuni(loading, search, this);
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
        onSearchPrestador(search, loading) {
            if(search.length) {
                loading(true);
                this.searchPrest(loading, search, this);
            }
        },
        searchPrest: _.debounce((loading, search, vm) => {
            fetch(
                `consultarPrestadores/${escape(search)}`
            ).then(res => {
                res.json().then(json => (vm.prestadores = json));
                loading(false);
            });
        }, 350),
        searchSaldo(search){
            this.cargando = true;
            
        },
        onSearchDiagnostico(search, loading){
             if(search.length) {
                loading(true);            
                this.searchDiagnostico(loading, search, this);
            }
        },    
        searchDiagnostico: _.debounce((loading, search, vm) => {
            fetch(
                `consultarDiagnosticos/${escape(search)}`
            ).then(res => {
                res.json().then(json => (vm.diagnosticos = json));                
                loading(false);
            });

        }, 700),
        onSearchAfiliado(search, loading){
            if($('#regimen').val() != ''){             
                if(search.length) {
                    search = $('#regimen').val()+'||'+search;  
                    loading(true);
                    this.searchAfiliado(loading, search, this);
                }
            }else{
                $('#regimen').focus();
                return Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Para Buscar el Afiliado debe Seleccionar el Regimen'
                });
            }    
        },    
        searchAfiliado: _.debounce((loading, search, vm) => {
            fetch(
                `consultarAfiliado/${escape(search)}`
            ).then(res => {
                res.json().then(json => (vm.afiliados = json));                
                loading(false);
            });
        }, 700),
        onSearchAutoriza(){
            this.cargandoAuto = true;                        
            /*alert($("#numMipres").val()); */
             axios.post(`consultarAutorizacion`,{
                numauto: this.numautoriza
            })
           .then(resp => {
               this.cargandoAuto = false;
                //alert(resp.data.mensaje);
                //console.log( JSON.stringify(resp, null, " ") );               
               if(resp.data.valida == 0){
                   this.autorizaciones = [];
                    return Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: resp.data.mensaje
                    });
               }

               this.autorizaciones = resp.data.data;
               
     
           })
           .catch(error => {
               alert(error);
               this.cargandoAuto = false;
            })  
        },
        guardaRegistro(e){  
            this.save = true;
            const datosFormulario = new FormData(e.target);                                    
            datosFormulario.append('responsable',JSON.stringify(this.responselecc));
            datosFormulario.append('municipio',JSON.stringify(this.municipiosel));
            datosFormulario.append('prestador',JSON.stringify(this.prestadorsel));
            datosFormulario.append('diag_first',JSON.stringify(this.diagPrincsel));
            datosFormulario.append('diag_second',JSON.stringify(this.diagSecucsel));
            datosFormulario.append('autorizaciones',JSON.stringify(this.autorizaciones));
            datosFormulario.append('afiliados',JSON.stringify(this.afiliadosel)); 
            axios.post('guardaAnticipo', datosFormulario)
            .then(resp => {                
                this.save = false;
                //console.log(resp);
                return Swal.fire({
                    icon: 'success',
                    title: 'Ok...',
                    text: 'Guardado con Exito, Consecutivo Generado: '+resp.data
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
    },
    computed:{
        listaAdjuntosFiltrada(){
            const listaAdjuntos = [];           
                for (let i = 0; i < this.listadocumentos.length; i++) {
                    if(!this.listaAdjuntos.find(adjunto =>  adjunto.TXA_ID == this.listadocumentos[i].TXA_ID)){
                        listaAdjuntos.push(this.listadocumentos[i]);
                    }
                }                       
            return listaAdjuntos;
        }
    },
    mounted() {        
        this.activarSoportes();
    },
}  
</script>