<style>
    
     body{margin-top:20px;}
    /* ==========================================================================
       Credit Card Payment Section
       ========================================================================== */
    
    .card-holder {
      margin: 3em 0;      
        
    }
    
    .img-box {
     padding-top: 20px;    
     display: flex;
     justify-content: center;     
    }
    .card-box {
      font-weight: 800;
      padding: 1em 0em;
      border-radius: 2.25em;
      box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);    
    }
    .bg-news {
      background: -webkit-linear-gradient(110deg, #0082BA 45%, #ffffff 41%);
      background: -o-linear-gradient(110deg, #0082BA 45%, #ffffff 41%);
      background: -moz-linear-gradient(110deg, #0082BA 45%, #ffffff 41%);
      background: linear-gradient(110deg, #0082BA 45%, #ffffff 41%);
    }
    
    
    .credit-card form{
        background-color: #ffffff;
        max-width: 600px;
    }
    
    .credit-card .title{        
        font-size: 1em;
        color: #2C3E50;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        margin-bottom: 0.8em;
        font-weight: 600;
        padding-bottom: 8px;
    }
    .credit-card .card-details{
        padding: 25px 25px 15px 0px;
    }
    
    .inner-addon {
      position: relative;
    }
    
    .inner-addon .fas, .inner-addon .far {
      position: absolute;
      padding: 10px;
      pointer-events: none;
      color: #bcbdbd !important;
    }
    .right-addon .fas, .right-addon .far { right: 0px; top: 40px;}
    .right-addon input { padding-right: 20px; }
    
    .credit-card .card-details label{
        font-family: 'Abhaya Libre', serif;
        font-size: 14px;
        font-weight: 400;
        margin-bottom: 15px;
        color: #79818a;
        text-transform: uppercase;
    }
    
    @media (max-width: 768px) {
        .credit-card{
          height: 250vh;
          width: 100%;
        }
        .credit-card .title {
            font-size: 1.2em; 
        }
    
        .credit-card .row .col-lg-6 {
            margin-bottom: 40px; 
          }
    
          .credit-card .card-details button {
            margin-top: 2em; 
        } 
    } 

  

</style>
@extends('layouts.app')

@section('content')
	
<section class="credit-card">
    <div class="container">
        
        <div class="card-holder">
            <div class="card-box bg-news">
            <div class="row">
                
            <div class="col-lg-6">
            <div class="text-white p-4">
                <img src="{{ asset('img/icons/profile_2.svg') }}" width="80px" height="80px" class="img-fluid" />
                <p>Bienvenido, </p>                                
                {{-- <h2>{{  Auth::user()->USU_PRIMER_NOMBRE }} {{ Auth::user()->USU_SEGUNDO_NOMBRE }} {{ Auth::user()->USU_PRIMER_APELLIDO }} {{ Auth::user()->USU_SEGUNDO_APELLIDO }}</h2> --}}
                <h2>{{  Auth::user()->USU_CORREO }}</h2>                                
            </div>
            </div>
            <div class="col-lg-6">
            
            <form>
                <div class="card-details">
                <h3 class="title">INFORMACIÓN DE LA CUENTA</h3>
                <div class="row">
                    <div class="form-group col-sm-6">
                    <div class="inner-addon right-addon">
                    <label for="card-holder">Primer Nombre</label>
                    <i class="far fa-user"></i>
                    <input id="card-holder" type="text" class="form-control" disabled placeholder="Primer Nombre" value="{{  Auth::user()->USU_PRIMER_NOMBRE }}">
                    </div>	
                    </div>
                    <div class="form-group col-sm-6">
                    <div class="inner-addon right-addon">
                    <label for="card-holder">Segundo Nombre</label>
                    <i class="far fa-user"></i>
                    <input id="card-holder" type="text" class="form-control" placeholder="Segundo Nombre" disabled value="{{ Auth::user()->USU_SEGUNDO_NOMBRE }}">
                    </div>	
                    </div>
                    <div class="form-group col-sm-6">
                    <div class="inner-addon right-addon">
                    <label for="card-holder">Primer Apellido</label>
                    <i class="far fa-user"></i>
                    <input id="card-holder" type="text" class="form-control" placeholder="Primer Apellido" disabled value="{{ Auth::user()->USU_PRIMER_APELLIDO }}">
                    </div>	
                    </div>
                    <div class="form-group col-sm-6">
                    <div class="inner-addon right-addon">
                    <label for="card-holder">Segundo Apellido</label>
                    <i class="far fa-user"></i>
                    <input id="card-holder" type="text" class="form-control" placeholder="Segundo Apellido" disabled value="{{ Auth::user()->USU_SEGUNDO_APELLIDO }}">
                    </div>	
                    </div>                                            
                    <!-- <div class="form-group col-sm-12">
                    <button type="button" class="btn btn-primary btn-block">Proceed</button>
                    </div> -->
                </div>
                </div>
            </form>				
            
            </div><!--/col-lg-6 --> 
        
            </div><!--/row -->
            </div><!--/card-box -->
        </div><!--/card-holder -->		 
        
    </div><!--/container -->
    </section>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div> --}}

@endsection
