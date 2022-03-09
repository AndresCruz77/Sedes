
<style>
 .modal-body{
  /* max-height:150px; */
  overflow:auto;
} 

</style>
<template>
    <div class="col-md-12">
        <div v-show="!AnticipoSelect && !cargandoAnticipo">
            <!-- inicia modal historico-->
            <div class="modal fade animated bounceIn" id="modelId"   role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <!-- <div class="modal fade animated bounceIn" id="modelId" role="dialog">    -->
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>Historico Anticipo Medico # {{this.numeroAnticipoSel}}</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <form id="formularioBusqueda" class="row">
                                <div class="col-md-8 form-group">
                                <label for="numeroFactura"></label>
                                <!--   <input type="text" class="form-control" name="numeroFactura" id="numeroFactura" aria-describedby="helpId" placeholder="Digite consecutivo de recobro" v-model="factura"> -->
                                </div>

                                <div class="col-md-4">
                                <!--  <button type="button" class="btn btn-primary btn-md btn-block" v-if="factura.length > 0" v-on:click="buscarFactura()">
                                        <i class="fas fa-search"></i> Buscar
                                    </button> -->
                                </div>
                            </form>

                            <div class="row mt-3 mb-3">
                                <table class="table table-striped table-hover table-xs" id="tblhistorico" style="width:100px;">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Estado</th>
                                            <th>Observación al Estado</th>
                                            <th>Regional</th>
                                            <th>Seccional</th>
                                            <th>Responsabl|e</th>
                                            <th>Municipio</th>
                                            <th>Prestador</th>
                                            <th>Afiliado</th>
                                            <th>Regimen</th>
                                            <th>Origen</th>
                                            <th>Tipo Orden Judicial</th>
                                            <th>Motivo</th>
                                            <th>Valor Cruce</th>
                                            <!-- <th>Valor Bruto a Pagar</th> -->
                                            <th>Valor Total Cotizado</th>
                                            <th>Saldo por Legalizar del Prestador</th>
                                            <th>Diagnóstico Principal</th>
                                            <!-- <th>Diagnóstico Secundario</th> -->
                                            <th>Número de Autorización</th>                                                                                
                                            <th>Número Mipres</th>                                                                                
                                            <th>Observación</th> 
                                            <th>Fecha</th> 
                                            <th>Usuario</th> 
                                            <th>Acción</th> 
                                        </tr>
                                        </thead>
                                        <tbody>
                                
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
            <!-- fin modal -->
            <!-- inicia modal documentos-->
            <div class="modal fade animated bounceIn" id="modalVisorDoc" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">            
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>Visor Documentos Anticipo Medico # {{this.numeroAnticipoSel}}</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            

                            <div class="card-body" v-for="(itema) in adjuntosCargados" :key="itema.AXA_ID">                                
                                <h5 class="card-title">{{ itema.AXA_NOMBRE }}</h5>                          
                                <section>
                                    <p>Soporte cargado correctamente</p>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                             <a :href="'verSoporte/'+itema.AXA_ID" target="_blank" class="stretched-link">Ver documento</a> 
                                        </li>
                                        <li class="list-group-item">
                                            <small class="text-muted">Documento: {{ itema.AXA_NOMBRE }}</small>
                                        </li>
                                        <li class="list-group-item">
                                            <small class="text-muted">Fecha Cargue: {{ itema.AXA_FECHA_CREACION }}</small>
                                        </li>
                                    </ul>
                                </section>

                            </div>
                            
                    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>    
            <!-- fin modal -->
            
            <div class="row" >
                <div class="col-md-1 col-sm-11">
                    <a class="nav-link" :href="url+'/home'"  title="Volver"> 
                        <i class="fas fa-arrow-circle-left fa-2x"></i>
                    </a>
                </div>
                <div class="col-md-11 col-sm-11">
                    <h3 class="text-center border-bottom-primary p-1">Anticipos Medicos - Bandeja Trabajo <i class="fas fa-folder-open text-black-300"></i></h3>
                </div>
            </div> 

            <form class="card mb-3" @submit.prevent="consultaAnticipos"  id="formConsultaAnticipos" >
                <div class="card border-secondary">
                    <div class="card-body"> 
                        <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x"><br>                    
                            <div class="container col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel-default border rounded">
                                                <div class="panel-heading " role="tab" id="headingOne">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed text-light" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" >Busqueda</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingOne" >
                                                    <div class="panel-body">   
                                                        <div class="row p-2">
                                                            <div class="col-sm-6 form-group">
                                                                <label for="fechInicial" class="text-primary">Fecha Radicación Inicial</label>
                                                                <input type="date" id="fechInicial" name="fechInicial" class="form-control">
                                                            </div>   
                                                            <div class="col-sm-6 form-group">
                                                                <label for="fechFinal" class="text-primary">Fecha Radicación Final</label>
                                                                <input type="date" id="fechFinal" name="fechFinal" class="form-control">
                                                            </div>
                                                            <div class="col-sm-12 form-group">
                                                                <label for="prestadorAcuerdo" class="text-primary">Prestador</label>
                                                                <v-select v-model="prestadorsel"  :options="prestadores" name="prestador" label="PRESTADOR" code="ipsID" :filterable="false" required placeholder="Digite Nombres o # Nit"   @search="onSearchPrestador">
                                                                    <template #search="{attributes, events}"><input  class="vs__search"  v-bind="attributes"  v-on="events" /></template>
                                                                </v-select>                                    
                                                            </div>   
                                                            <div class="col-sm-12 form-group">
                                                                <label for="prestadorAcuerdo" class="text-primary">Afiliado EPS</label>
                                                                <v-select v-model="afiliadosel"  :options="afiliados" name="afiliado" label="NombreCompleto" code="NumDocAfiliado" :filterable="false" required placeholder="# Identificación"   @search="onSearchAfiliado">
                                                                    <template #search="{attributes, events}"><input  class="vs__search" v-bind="attributes"  v-on="events" /></template>
                                                                </v-select>                                    
                                                            </div>
                                                            <div class="col-sm-4 form-group">
                                                                <label for="regSolicita" class="text-primary">Regional Solicitante</label>
                                                                <select id="regSolicita" name="regSolicita" class="form-control" >
                                                                        <option value="">[SELECCIONE]</option>                                        
                                                                        <option :value="regional.RNL_ID" v-for="regional of listaregional" :key="regional.RNL_ID" >{{ regional.RNL_NOMBRE }}</option>
                                                                </select>                                                            
                                                            </div>
                                                            <div class="col-sm-4 form-group">
                                                                <label for="estaAnticipo" class="text-primary">Número Anticipo</label>
                                                                <input type="number" id="anticipoMedi" name="anticipoMedi" class="form-control">
                                                            </div>
                                                            <div class="col-sm-4 form-group">
                                                                <label for="estaAnticipo" class="text-primary">Número Autorización</label>
                                                                <input type="number" id="autoriMedi" name="autoriMedi" class="form-control">
                                                            </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label for="origAnticipo" class="text-primary">Origen Anticipo</label>
                                                                <select id="origAnticipo" name="origAnticipo" class="form-control">
                                                                    <option value="" >[SELECCIONE]</option>
                                                                    <option :value="origen.OXA_ID" v-for="origen of listaorigen" :key="origen.OXA_ID" >{{ origen.OXA_DESCRIPCION }}</option>
                                                                                                            
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    Seleccione el Origen del Anticipo
                                                                </div>                         
                                                            </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label for="estaAnticipo" class="text-primary">Estado Anticipo</label>
                                                                <select id="estaAnticipo" name="estaAnticipo" class="form-control" >
                                                                    <option value="" selected>[SELECCIONE]</option>
                                                                    <option :value="estdanticipo.EXA_ID" v-for="estdanticipo of listaestados" :key="estdanticipo.EXA_ID"  >{{ estdanticipo.EXA_DESCRIPCION }}</option>                                            
                                                                </select>                                                            
                                                            </div>                                                        
                                                            <div class="form-group col-md-12 col-sm-12">                        
                                                                <button type="submit"   class="btn btn-primary btn-block"  v-if="!save" > <!-- v-on:click="guardaRegistro()" -->
                                                                    <span>Consultar</span>
                                                                </button>
                                                                <button type="button"  class="btn btn-primary btn-block" disabled v-else  >
                                                                    <span>Generando Busqueda</span>
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
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <table class="table table-striped table-hover" id="visor"> 
                                            <thead> 
                                                <tr>                                                         
                                                    <th>Seleccionar</th> 
                                                    <th>Historico</th> 
                                                     <th>Documentos</th> 
                                                    <th># Anticipo</th>                                                         
                                                    <th>Fecha Radicación</th>
                                                    <th>Estado Anticipo</th>
                                                    <th>Regional</th> 
                                                    <th>Tipo Documento</th> 
                                                    <th># Documento</th> 
                                                    <th>Nombre</th> 
                                                    <th>Valor Cruce</th> 
                                                </tr> 
                                            </thead> 
                                            <tbody>                                           
                                                <tr v-for="(item,index) in resultadoConsulta" :key="item.AHC_ID">                                            
                                                    <td><div class='row-sm-2 row-sm-2'><button type='button' v-on:click="seleccionarAnticipo(item,index)" class='btn btn-primary btn-block' title ="Editar"><i class="fas fa-edit text-white"></i></button></div></td>  
                                                    <td><div class='row-sm-2 row-sm-2'><button type='button' data-toggle="modal" data-target="#modelId" v-on:click="historicoAnticipo(item,index)" class='btn btn-primary btn-block' title="Ver Historial"><i class="fas fa-search-plus text-white"></i></button></div></td>
                                                    <td><div class='row-sm-2 row-sm-2'><button type='button' data-toggle="modal" data-target="#modalVisorDoc" v-on:click="DocumentosAnticipo(item,index)" class='btn btn-primary btn-block' title="Visor Documentos"><i class="fas fa-file-medical-alt text-white"></i></button></div></td>
                                                    <!-- <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId"> Consultar Recobro </button> -->
                                                    
                                                    <td>{{ item.AHC_ID }}</td>
                                                    <td>{{ item.AHC_FECHA_CREACION }}</td>
                                                    <td>{{ item.EXA_DESCRIPCION }}</td>
                                                    <td>{{ item.RNL_NOMBRE }}</td>
                                                    <td>{{ item.AHC_TIPO_IDENT_AFI }}</td>
                                                    <td>{{ item.AHC_NUM_IDENT_AFI }}</td>
                                                    <td>{{ item.AHC_NOMBRE_COMPLETO }}</td>
                                                    <td>{{ item.AHC_VALOR_CRUCE }}</td>                                             
                                                </tr>              
                                            </tbody>
                                            <tfoot>
                                                <tr>                                                         
                                                    <th>Seleccionar</th> 
                                                    <th>Historico</th> 
                                                    <th>Documentos</th> 
                                                    <th># Anticipo</th>                                                         
                                                    <th>Fecha Radicación</th>
                                                    <th>Estado Anticipo</th>
                                                    <th>Regional</th> 
                                                    <th>Tipo Documento</th> 
                                                    <th># Documento</th> 
                                                    <th>Nombre</th> 
                                                    <th>Valor Cruce</th> 
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>            
                            </div>    
                        </div>
                    </div>        
                </div>
            </form>
        </div>            
        <!-- llamado form editar -->
        <div class="col-md-12" v-if="AnticipoSelect && !cargandoAnticipo">
            <div class="row" >
                <div class="col-md-1 col-sm-11">
                    <a href="javascript:void(0)" v-on:click="AnticipoSelect=!AnticipoSelect">
                        <i class="fas fa-arrow-circle-left fa-2x" title="Volver"></i>
                    </a>       
                </div>
                <div class="col-md-11 col-sm-11">
                    <h3 class="text-center border-bottom-primary p-1">Modificar Anticipo Medico # {{this.numeroAnticipoSelMod.AHC_ID}} <i class="fas fa-edit text-white"></i></h3>
                </div>
            </div>             
             <!-- @cambiarEstadoRecobro="cambiarEstadoRecobro" -->
            <anticipomed-formulario-visor 
                :dataanticipo="AnticipoDataSelccionado"
                :listaestado="listaestados"
                :listaobsestado="listaobsestado"
                :listaregional="listaregional"
                :listaseccional="listaseccional"
                :listaregimen="listaregimen"
                :listaorigen="listaorigen"
                :listatipoordenj="listatipoordenj"
                :listamotivo="listamotivo"
                :listadocumentos="listadocs"
                @cambiarEstadoAnticipo="cambiarEstadoAnticipo">
            </anticipomed-formulario-visor>  

        </div> 

        <div class="col-md-12" v-if="!AnticipoSelect && cargandoAnticipo">
            <h2 class="text-center text-primary">Cargando <i class="fas fa-spinner fa-3x fa-spin"></i></h2>            
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
    props: [ 'listaestados', 'url', 'listaorigen', 'listaregional', 'listaobsestado', 'listaseccional', 'listaregimen', 'listaorigen', 'listatipoordenj', 'listamotivo', 'listadocs'],
    data(){
        return {
            resultadoConsulta : [],
            cargando: false,
            prestadores: [],
            prestadorsel: null,
            afiliados : [],
            afiliadosel: null,            
            save : false,
            anticiposMedi : [],
            numeroAnticipoSel : '',                       
            AnticipoDataSelccionado: {},        
            AnticipoSelect: false,
            cargandoAnticipo: false,
            numeroAnticipoSelMod : '',
            adjuntosCargados : []            
        }
    },
    methods: {

        seleccionarAnticipo(item,index){
            this.numeroAnticipoSelMod = item;            
            this.cargandoAnticipo=true;
            axios.get(`dataAnticipo/${item.AHC_ID}`)
           .then(resp => {
                this.AnticipoDataSelccionado = resp.data
                this.AnticipoSelect=true;
                this.cargandoAnticipo=false;                 
           })
           .catch(error => alert(error))             
        }, 
        cambiarEstadoAnticipo(index){            
            
            this.AnticipoSelect=false; 
        },
        DocumentosAnticipo(item,index){
            this.numeroAnticipoSel = item.AHC_ID;
            axios.get(`getDocuments/${item.AHC_ID}`)
            .then(resp => {
                this.adjuntosCargados = resp.data;
            })
           .catch(error => alert(error)) 
        }, 
        historicoAnticipo(item,index){
            this.numeroAnticipoSel = item.AHC_ID;
          /*   console.log(item.AHC_ID);
            console.log(index);
            alert(item);
            alert(index); */
            axios.get(`ConsultarHistorial/${item.AHC_ID}`)
            .then(resp => {
                 $('#tblhistorico').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                        },
                        destroy: true,
                        dom: 'Bfrtip',
                        data: resp.data,
                        buttons: [                           
                            /*{
                                extend: 'excel',
                                title: 'Reporte_Anticipos_Medicos_'+new Date(),
                                className: 'btn btn-success',
                                text: 'Exportar a Excel'
                            },
                             {
                                extend: 'csv',
                                title: 'hola-'+new Date(),
                                className: 'btn btn-warning',
                                text: 'Exportar a CSV'
                            },  */
                        ],
                       columns: [
                            { data: "EXA_DESCRIPCION" },
                            { data: "LOE_DESCRIPCION" },
                            { data: "RNL_NOMBRE" },
                            { data: "GEO_SECCIONAL" },
                            { data: "RESPONSABLE" },
                            { data: "GEO_MUNICIPIO" },
                            { data: "PRESTADOR" },
                            { data: "AFILIADO" },
                            { data: "REG_NOMBRE" },
                            { data: "OXA_DESCRIPCION" },
                            { data: "TOJ_DESCRIPCION" },
                            { data: "MXA_DESCRIPCION" },
                            { data: "AMH_VALOR_CRUCE" },
                          //  { data: "AMH_VALOR_BRUTO_PAG" },
                            { data: "AMH_VALOR_TOTAL_COTIZ" },
                            { data: "AMH_SALDO_LEGALIZAR_PREST" },
                            { data: "DIAG_PRINC" },
                           /// { data: "DIAG_SECON" },
                            { data: "AMH_NUMERO_AUTORIZA" },
                            { data: "AMH_NUMERO_MIPRES" },
                            { data: "AMH_OBSERV_GENER" },                                                                                  
                            { data: "AMH_FECHA_CREACION" },
                            { data: "AMH_USER_CREA" },
                            { data: "AMH_ACCION" }                          
                        ] 
                    });             
            })
           .catch(error => alert(error)) 

        },   
                                
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
        guardaRegistro(e){  
            this.save = true;
            const datosFormulario = new FormData(e.target);                                    
            datosFormulario.append('responsable',JSON.stringify(this.responselecc));
            datosFormulario.append('municipio',JSON.stringify(this.municipiosel));
            datosFormulario.append('prestador',JSON.stringify(this.prestadorsel));
            datosFormulario.append('diag_first',JSON.stringify(this.diagPrincsel));
            datosFormulario.append('diag_second',JSON.stringify(this.diagSecucsel));
            datosFormulario.append('autorizaciones',JSON.stringify(this.autorizaciones));
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
        onSearchAfiliado(search, loading){
             if(search.length) {
                loading(true);
                this.searchAfiliado(loading, search, this);
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
        consultaAnticipos(e){
            this.save = true;
            //alert('entra');
            //alert(document.getElementById("fechInicial").value);
            if(document.getElementById("fechInicial").value != ''){
                if(document.getElementById("fechFinal").value == ''){
                    document.getElementById("fechFinal").focus();
                    this.save = false;
                    return Swal.fire({
                        icon: 'error',
                        title: 'Error al Generar Consulta',
                        text: 'Debe Seleccionar La Fecha Radicación Final'
                    });
                    
                }else{
                    if(document.getElementById("fechInicial").value > document.getElementById("fechFinal").value){
                        this.save = false;
                        return Swal.fire({
                            icon: 'error',
                            title: 'Error al Generar Consulta',
                            text: 'Validar Fechas de Consulta'
                        });
                    }
                }    
                
            }
            if(document.getElementById("fechFinal").value != ''){
                if(document.getElementById("fechInicial").value == ''){
                    document.getElementById("fechInicial").focus();
                    this.save = false;
                    return Swal.fire({
                        icon: 'error',
                        title: 'Error al Generar Consulta',
                        text: 'Debe Seleccionar La Fecha Radicación Inicial'
                    });
                    
                }
            }
            const datosFormulario = new FormData(e.target);       
            datosFormulario.append('afiliados',JSON.stringify(this.afiliadosel)); 
            datosFormulario.append('prestador',JSON.stringify(this.prestadorsel));
            axios.post('consultaVisorAnticipo', datosFormulario)
            .then(resp => {                
                this.save = false;
                //console.log(resp.data);
               // console.log(resp.data.length);
                //console.log(resp.data[0].AHC_ID);                
                if(resp.data.length > 0){
                    this.resultadoConsulta = resp.data;
                    this.tabla();                   
                    return Swal.fire({
                        icon: 'success',
                        title: 'Ok...',
                        text: 'Registros Encontrados..'
                    });
                }else{
                    //this.resultadoConsulta = '';
                    //$('#visor').DataTable().clear();
                    //$('table').dataTable({bFilter: false, bInfo: false});

                    return Swal.fire({
                        icon: 'error',
                        title: 'No se Encontraron Registros...',
                        text: 'Validar Parametros de Consulta'
                    })
                }                        
            })            
            .catch(error => {                
                this.save = false;
                return Swal.fire({
                        icon: 'error',
                        title: 'Error al Consultar...',
                        text: error
                    })
            }); 

        },
        tabla(){
            this.$nextTick(() =>{                
                $('#visor').DataTable({
                     "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                        },
                        destroy: true,
                        dom: 'Bfrtip',                        
                        buttons: [                           
                           /*  {
                                extend: 'excel',
                                title: 'Reporte_Anticipos_Medicos_'+new Date(),
                                className: 'btn btn-success',
                                text: 'Exportar a Excel'
                            },
                             {
                                extend: 'csv',
                                title: 'hola-'+new Date(),
                                className: 'btn btn-warning',
                                text: 'Exportar a CSV'
                            },                          */
                        ]
                });
            });
        }           
    },
    computed:{
      
    },
    mounted() {        
        
    },
}  
</script>