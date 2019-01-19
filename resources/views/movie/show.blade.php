
@extends('layouts.app')

@section('title', 'ver')

@section('content')

<h1 class="text-center">Mostrando {{ $movie->name }}</h1>

    <div class="jumbotron text-center">
        <h2>{{ $movie->name }}</h2>
        <p>
            <strong>Fecha inicio:</strong> {{ $movie->function_init_date }}<br>
            <strong>Fecha fin:</strong> {{ $movie->function_end_date }}<br>
            <strong>Creado:</strong> {{ $movie->created_at }}<br>
            <strong>Actualizado:</strong> {{ $movie->updated_at }}<br>
        </p>
    </div>
@endsection