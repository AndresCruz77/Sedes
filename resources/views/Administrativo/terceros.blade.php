<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
{{-- <script type="text/javascript" src="js/librerias/ValidacionArchivo.js"></script> --}}
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>



@extends('layouts.app')

@section('content')

    <div class="row">
        <admin-terceros :url='{{ json_encode(url("/")) }}' :listatipodocs='{{ $listatipoDocs }}'></admin-terceros>        
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