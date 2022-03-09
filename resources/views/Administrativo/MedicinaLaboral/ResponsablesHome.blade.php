@extends('layouts.app')

@section('content')

    <ml-responsables-component 
        :usuarios='{{ json_encode($usuarios) }}'
        >
    </ml-responsables-component>

@endsection