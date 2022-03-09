<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  
  <title>Medimas Solution Factory - Login</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="{{ asset('css/authlogin.css') }}">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script> 
  
</head>
<body style="background-image: linear-gradient(to left,#002453, #0081c9) !important">

<div class="container">

  <div class="row py-3 text-white">

      <div class="d-none d-md-flex col-md-3 col-lg-5 bg-image mt-5"></div>
      <div class="col-md-8 col-lg-7">
        <div class="login d-flex align-items-center">
          <div class="container">
            <div class="row">
              <div class="col-md-9 col-lg-8 mx-auto">
                <h1 class="login-heading text-center">Gestión Administrativa de Sedes Medimás</h1>
                <h3 class="login-heading mb-4 text-center">Bienvenido, inicie sesión</h3>

                @if(Session::has('message'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                  @csrf
                  
                  <div class="form-label-group">
                    {{-- <input type="text" name="num_identifica" id="tipo_doc" class="form-control @error('num_identifica') is-invalid @enderror" value="{{ old('num_identifica') }}" required autocomplete="num_identifica" autofocus> --}}
                    <select name="tip_doc" id="tip_doc" class="form-control @error('tip_doc') is-invalid @enderror" required autofocus>
                      <option value="">Tipo Identificación</option>
                      <option {{ old('database') ===  'AS' ? 'selected' : '' }} value='AS'>Adulto sin Identificación</option>
                      <option {{ old('database') ===  'CC' ? 'selected' : '' }} selected value='CC'>Cédula Ciudadanía</option>
                      <option {{ old('database') ===  'CD' ? 'selected' : '' }} value='CD'>Carné Diplomático</option>
                      <option {{ old('database') ===  'CE' ? 'selected' : '' }} value='CE'>Cédula Extranjería</option>
                      <option {{ old('database') ===  'CN' ? 'selected' : '' }} value='CN'>Certificado de Nacido Vivo</option>
                      <option {{ old('database') ===  'IN' ? 'selected' : '' }} value='IN'>IN - Documento Temporal</option>
                      <option {{ old('database') ===  'M'  ? 'selected' : '' }}  value='M'>Registro Mercantil</option>
                      <option {{ old('database') ===  'MG' ? 'selected' : '' }} value='MG'>Migración</option>
                      <option {{ old('database') ===  'MS' ? 'selected' : '' }} value='MS'>Menor Sin Identificación</option>
                      <option {{ old('database') ===  'NI' ? 'selected' : '' }} value='NI'>Nit - No. Identificación Tributaria</option>
                      <option {{ old('database') ===  'NU' ? 'selected' : '' }} value='NU'>Registro Civil - NUIP</option>
                      <option {{ old('database') ===  'PA' ? 'selected' : '' }} value='PA'>Pasaporte</option>
                      <option {{ old('database') ===  'PE' ? 'selected' : '' }} value='PE'>Permiso Especial de Permanencia</option>
                      <option {{ old('database') ===  'RC' ? 'selected' : '' }} value='RC'>Registro Civil - NIP</option>
                      <option {{ old('database') ===  'SC' ? 'selected' : '' }} value='SC'>Salvoconducto de Permanencia</option>
                      <option {{ old('database') ===  'TI' ? 'selected' : '' }} value='TI'>Tarjeta Identidad</option>
                      <option {{ old('database') ===  'DE' ? 'selected' : '' }} value='DE'>Documento extranjero</option>
                    </select>
                    @error('tip_doc')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                  </div>



                  <div class="form-label-group">
                    <input type="text" name="num_identifica" id="tipo_doc" class="form-control @error('num_identifica') is-invalid @enderror" value="{{ old('num_identifica') }}" required autocomplete="num_identifica" >
                    <label for="num_identifica"># Identificación</label>

                    @error('num_identifica')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                  </div>


                  <div class="form-label-group">
                    <input type="text" name="usu_username" id="usu_username" class="form-control @error('usu_username') is-invalid @enderror" value="{{ old('usu_username') }}" required autocomplete="usu_username" >
                    <label for="usu_username">Usuario</label>

                    @error('usu_username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                  </div>
  
                  <div class="form-label-group">
                    <input type="password" name="usu_password" id="usu_password" class="form-control @error('usu_password') is-invalid @enderror" placeholder="Contraseña" required autocomplete="current-password">
                    <label for="usu_password">Contraseña</label>

                    @error('usu_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                  </div>
               
  
                  <div class="custom-control custom-checkbox mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Recordar sesión</label>
                  </div>

                {{--   <div class="form-group">                   
                    <div class="g-recaptcha" data-sitekey="6LcNsZAaAAAAADOw7uB3Dub7nHSm17bCIlQ2S30h"></div>                     
                  </div>                  

                  @error('g-recaptcha-response')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror      --}}         

                  <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2 rounded-pill" type="submit">Ingresar</button>

               {{--    @if (Route::has('password.request'))
                    <a class="btn btn-link text-white" href="{{ route('password.request') }}">
                        {{ __('¿Olvido su contraseña?') }}
                    </a>
                  @endif   --}}

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  </body>
  </html>