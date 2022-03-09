@extends('layouts.app')

@section('content')

    <ml-gestion-procesos
      :procesos='{{ json_encode($procesosUsuario) }}'
      :user='{{ json_encode(Auth::user()) }}'
    >
    </ml-gestion-procesos>

@endsection