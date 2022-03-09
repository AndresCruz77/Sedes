<!DOCTYPE html>
<html lang="es-Es">
<head>
    <meta charset="utf-8">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Medimas - Mi Vacuna</title>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> 

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <style>
        .bg-image{
            background-image: url("{{ asset('img/background/vacuna-02-min.png') }}") !important;
            background-size: cover;
            background-position: center;
            min-height: 65vh;
        }       
        .logo-image{
            background-image: url("{{ asset('img/logos/logo_final_blanco.png') }}") !important;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            min-height:15vh;            
        }
    </style>

</head>
<body style="background-image: linear-gradient(to right,#001F54, #007DC5) !important">

    <div class="container" id="app">
        <formulario-consultamivacuna :tiposdocumento='{{ json_encode($tiposDocumento) }}' url="{{ url('/') }}"></formulario-consultamivacuna>
    </div>
    
</body>

</html>