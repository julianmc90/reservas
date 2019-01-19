

@extends('layouts.app')

@section('title', 'Editar')

@section('content')


<h1>Editar {{ $movie->name }}</h1>

<!-- if there are creation errors, they will show here -->

<ul>
    @foreach($errors->all() as $key => $value)
        <li>
            {{$value}}
        </li>
    @endforeach
</ul>

{{ Form::model($movie, array('route' => array('movie.update', $movie->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('name', 'Nombre') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('function_init_date', 'Fecha inicio') }}
        {{ Form::date('function_init_date', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('function_end_date', 'Fecha fin') }}
        {{ Form::date('function_end_date', null, array('class' => 'form-control')) }}
    </div>

    {{-- <div class="form-group">
        {{ Form::label('nerd_level', 'Nerd Level') }}
        {{ Form::select('nerd_level', array('0' => 'Select a Level', '1' => 'Sees Sunlight', '2' => 'Foosball Fanatic', '3' => 'Basement Dweller'), null, array('class' => 'form-control')) }}
    </div> --}}

    {{ Form::submit('Editar', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@endsection