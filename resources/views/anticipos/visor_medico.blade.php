<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
{{-- <script type="text/javascript" src="js/librerias/ValidacionArchivo.js"></script> --}}
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>



@extends('layouts.app')

@section('content')

    <div class="row">
        <anticipos-visormedico :listaestados='{{ json_encode($listaEstados) }}'  :url='{{ json_encode(url("/")) }}' :listaorigen= '{{ json_encode($listaOrigen) }}'
        :listaregional = '{{ json_encode($listRegional) }}' :listaobsestado = '{{ json_encode($listaObsEstado) }}'  :listaseccional = '{{ json_encode($listaSeccional) }}' 
        :listaregimen = '{{ json_encode($listaRegimen) }}' :listaorigen = '{{ json_encode($listaOrigen) }}' :listatipoordenj = '{{ json_encode($listaTipoOrdenj) }}'
        :listamotivo = '{{ json_encode($listaMotivo) }}' :listadocs = '{{ json_encode($listaDocs) }}'  >
        </anticipos-visormedico> 

    </div>

    


@endsection

<script>
    $(document).ready(function(){
        /* $('#visor').DataTable({
                "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                }
            }); */                 
    })

</script>