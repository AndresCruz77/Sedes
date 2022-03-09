@extends('layouts.app')

@section('content')

    <admin-users 
        :tiposdocumento='{{ json_encode($tipoDocumento) }}'
        :roles='{{json_encode($roles)}}'
    ></admin-users>

@endsection