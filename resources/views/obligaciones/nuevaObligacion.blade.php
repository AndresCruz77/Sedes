<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
{{-- <script type="text/javascript" src="js/librerias/ValidacionArchivo.js"></script> --}}
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>



@extends('layouts.app')

@section('content')

    <div class="row">
        <nueva-obligacion :url='{{ json_encode(url("/")) }}' :listacategorias='{{ json_encode($listaCategorias) }}' :listaperiodos= '{{ json_encode($listaPeriodos) }}' :fechames = '{{$fechames}}'>
        
        
        </nueva-obligacion>
        {{-- <anticipos-nuevo :listadocumentos='{{ json_encode($listaDocs) }}' :listaestados='{{ json_encode($listaEstados) }}'  :url='{{ json_encode(url("/")) }}'  
        :listaobsestado='{{ json_encode($listaObsEstado) }}' :listaregimen='{{ json_encode($listaRegimen) }}' :listamotivo='{{ json_encode($listaMotivo) }}'
        :listatipoordenj= '{{ json_encode($listaTipoOrdenj) }}' :listaorigen= '{{ json_encode($listaOrigen) }}' :listaregional = '{{ json_encode($listRegional) }}'
        :listaseccional= '{{ json_encode($listaSeccional) }}' 
        ></anticipos-nuevo>  --}}

    </div>

    


@endsection

<script>
    $(document).ready(function(){
        $('#example').DataTable({
                "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                }
            });                 
    })

</script>