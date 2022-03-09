<style>
 .modal-body{
  /* max-height:150px; */
  overflow:auto;
} 

</style>
<template>
    <table class="table table-striped table-hover" id="tablaSedes"> 
        <thead> 
            <tr>
                <th>#</th>
                <th>Tipo Identificación</th>
                <th>Numero Identficación</th>
                <th>nombre</th>
                <th>Estado</th>
                <th>Modificar</th>                                            
            </tr> 
        </thead> 
        <tbody>  
            <tr v-for="(item,index) in listaterceros" :key="item.TER_ID">                                            
                <td>{{ item.TER_ID }}</td>
                <td>{{ item.TDO_HOMOLOGACION2 }}</td>
                <td>{{ item.TER_NUMERO_DOCUMENTO }}</td>     
                <td>{{ item.TER_NOMBRE }}</td>                                                       
                <td>{{ item.TER_ESTADO }}</td>                                                       
                <!--  <td><div class='row-sm-2 row-sm-2'><button type='button' data-toggle="modal" data-target="#modelId" v-on:click="historicoAnticipo(item,index)" class='btn btn-primary btn-block' title="Ver Historial"><i class="fas fa-search-plus text-white"></i></button></div></td> -->
                <td><div class='row-sm-2 row-sm-2'><button type='button' data-toggle="modal" data-target="#modal_nuevoTercero" v-on:click="$emit('editarTercero',{item,index})" class='btn btn-warning btn-block' title ="Editar"><i class="fas fa-edit text-white"></i></button></div></td>                                                                                          
            </tr>    
        </tbody>
        <tfoot>
            <tr>    
                <th>#</th>                                                     
                <th>Tipo Identificación</th>
                <th>Numero Identficación</th>
                <th>nombre</th>
                <th>Estado</th>
                <th>Modificar</th>                                          
            </tr>
        </tfoot>
    </table>                                      
</template>
<script>
   export default {
       props: ['listaterceros'],
       methods: {
           tabla(){                                        
                $('#tablaSedes').DataTable({
                    "bSort": false,   
                    "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                        },
                        destroy: true,
                        dom: 'Bfrtip',   
                                            
                        buttons: [                           
                        
                        ]
                });                
            }
        },
        mounted(){
            this.tabla()
          /*  setTimeout(() =>{
               this.tabla()
           },1000) */
           
        }
   }
   

</script>