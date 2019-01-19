

@extends('layouts.app')

@section('title', 'Nuevo')

@section('content')

<h1>Nuevo usuario</h1>

<!-- if there are creation errors, they will show here -->

<ul>
    @foreach($errors->all() as $key => $value)
        <li>
            {{$value}}
        </li>
    @endforeach
</ul>

{{ Form::open(array('url' => 'user')) }}

    <div class="form-group">
        {{ Form::label('identification', 'Identificación') }}
        {{ Form::text('identification', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Nombres') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('last_name', 'Apellidos') }}
        {{ Form::text('last_name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', null, array('class' => 'form-control')) }}
    </div>

    {{-- <div class="form-group">
        {{ Form::label('nerd_level', 'Nerd Level') }}
        {{ Form::select('nerd_level', array('0' => 'Select a Level', '1' => 'Sees Sunlight', '2' => 'Foosball Fanatic', '3' => 'Basement Dweller'), null, array('class' => 'form-control')) }}
    </div> --}}

    {{ Form::submit('Guardar', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@endsection