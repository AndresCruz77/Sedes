<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Medimas Solution Factory') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> 

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles Boostrap Theme -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/css/DataTables/dataTables.css') }}">
    <script defer src="{{ asset('/js/DataTables/dataTables.js') }}"></script>
    <script defer src="{{ asset('/js/DataTables/dataTables.buttons.js') }}"></script>
    <script defer src="{{ asset('/js/DataTables/buttons.html5.min.js') }}"></script>
    <script defer src="{{ asset('/js/DataTables/jszip.min.js') }}"></script>   

    <style type="text/css">

        .linkacceso {
            border-left: #0081c9 solid 8px !important;
        }

        .modal .modal-dialog-aside{
            width: 350px;
            max-width:80%; height: 100%; margin: 0; margin-top: 1%; margin-left: 1%;
            transform: translate(0); transition: transform .2s;
        }
        
        
        .modal .modal-dialog-aside .modal-content{  height: inherit; border:0; border-radius: 0;}
        .modal .modal-dialog-aside .modal-content .modal-body{ overflow-y: auto }
        .modal.fixed-left .modal-dialog-aside{ margin-left:auto;  transform: translateX(100%); }
        .modal.fixed-right .modal-dialog-aside{ margin-right:auto; transform: translateX(-100%); }
        
        .modal.show .modal-dialog-aside{ transform: translateX(0);  }

        .collapse_menu[data-toggle=collapse].collapsed::after {
            content: '\f105';
        }       

        .collapse_menu[data-toggle=collapse]::after {
        width: 1rem;
        text-align: center;
        float: right;
        vertical-align: 0;
        border: 0;
        font-weight: 900;
        content: '\f107';
        font-family: 'Font Awesome 5 Free';
    }
        
    
    </style>    

</head>
<body id="page-top">

    <div id="app">

        <!-- Page Wrapper -->
        <div id="wrapper">   
            
        @auth

            @include('layouts.sidebar')

        @endauth

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column mb-0 bg-white">

            <!-- Main Content -->
            <div id="content">    

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-2 static-top shadow" style="background-color: #0081c9">

                    @auth
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3 text-white" data-toggle="modal" data-target="#modal_aside_right">
                            
                            <i class="fa fa-bars"></i>
                            
                        </button>
                    @endauth

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        @auth

                            <!-- Nav Item - Alerts -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw text-white"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter">0</span>
                                </a>
                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow-md animated--grow-in"
                                    aria-labelledby="alertsDropdown">
                                    <h6 class="dropdown-header"  style="background-color: #0081c9">
                                        Alerts Center
                                    </h6>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-file-alt text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">December 12, 2019</div>
                                            <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                        </div>
                                    </a>
                                    <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                                </div>
                            </li>

                            <!-- Nav Item - Messages -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-envelope fa-fw text-white"></i>
                                    <!-- Counter - Messages -->
                                    <span class="badge badge-danger badge-counter">0</span>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="messagesDropdown">
                                    <h6 class="dropdown-header"  style="background-color: #0081c9">
                                        Message Center
                                    </h6>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="{{ asset('img/icons/profile_2.svg') }}"
                                                alt="">
                                            <div class="status-indicator bg-success"></div>
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                                problem I've been having.</div>
                                            <div class="small text-gray-500">Emily Fowler · 58m</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                                </div>
                            </li>

                            <div class="topbar-divider d-none d-sm-block"></div>

                        @endauth

                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item py-4 mr-3">
                                <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Ingresar') }}</a>
                            </li>
                            {{-- @if (Route::has('register'))
                                <li class="nav-item py-4">
                                    <a class="btn btn-primary text-white" href="{{ route('register') }}">{{ __('Registrarme') }}</a>
                                </li>
                            @endif --}}
                        @else

                            <!-- Nav Item - Messages -->
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="mr-2 d-none d-lg-inline text-white small"> 
                                        <b>{{ strtoupper(Auth::user()->rolUsuario->LOR_NOMBRE) }}</b>
                                    </span>
                                </a>
                            </li>

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">

                                

                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-white small">
                                        <b>{{ Auth::user()->USU_PRIMER_NOMBRE }} {{ Auth::user()->USU_SEGUNDO_NOMBRE }} {{ Auth::user()->USU_PRIMER_APELLIDO }} {{ Auth::user()->USU_SEGUNDO_APELLIDO }}</b>
                                    </span>
                                    <img class="img-profile rounded-circle"
                                    src="{{ asset('img/icons/profile_2.svg') }}">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">

                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Perfil
                                    </a>
                                    <div class="dropdown-divider"></div>

                                    <div>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                            {{ __('Salida segura') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>

                                </div>
                            </li>

                        @endguest                        

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @auth
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h4 mb-0" style="color: #0081c9">{{ strtoupper(str_replace('/',' > ',Request::path())) }}</h1>
                        </div>
                    @endauth

                    <main>
                        @yield('auth')
                    </main>

                    @auth
                        <main class="mt-3 mb-5" role="main">
                            @yield('content')
                        </main>                    
                    @endauth

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            
            {{-- @include('layouts.footer') --}}

        </div>
        <!-- End of Content Wrapper -->

        </div>


    </div>

</body>
</html>
