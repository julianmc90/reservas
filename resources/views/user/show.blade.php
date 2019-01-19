
@extends('layouts.app')

@section('title', 'ver')

@section('content')

<h1 class="text-center">Mostrando {{ $user->name . ' '. $user->last_name }}</h1>

    <div class="jumbotron text-center">
        <h2>{{ $user->name . ' '. $user->last_name }}</h2>
        <p>
            <strong>Identificación:</strong> {{ $user->identification }}<br>
            <strong>Email:</strong> {{ $user->email }}<br>
            <strong>Creado:</strong> {{ $user->created_at }}<br>
            <strong>Actualizado:</strong> {{ $user->updated_at }}<br>
            
        </p>
    </div>
@endsection