

@extends('layouts.app')

@section('title', 'Nuevo')

@section('content')

<h1> 
@if ($reservation != null )
    Editar
@else
    Nueva
@endif
Reserva</h1>

<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('users_id', 'Usuario') }}
            {{ Form::select('users_id',  $users, null, array('class' => 'form-control') ) }}
        </div>

    </div>

    <div class="col-md-6">
    
        <div class="form-group">
            {{ Form::label('movies_id', 'Pelicula') }}
            {{ Form::select('movies_id',  $moviesList, null, array('class' => 'form-control') ) }}
        </div>

    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('reservation_date', 'Fecha') }}
            {{ Form::select('reservation_date', [], null, array('class' => 'form-control') ) }}
        </div>
    </div>

</div>




<div class="row">
    <div class="col-md-6">
    
    <div class="form-group">
        <label >Silla</label>
    </div>
        <div id="seat-map">
        </div>
    </div>
    <div class="col-md-6">

        <label>Sillas seleccionadas <b>(<span id="counter"></span>):</b></label>
        <br/>
        <ul id="selected-seats" class="list-group"></ul>
        <button class="save-reservation btn btn-block btn-primary ">
            Guardar Reserva
        </button>
    </div>
</div>

@endsection

@section('styles')
 <link href="{{ mix('/css/jquery.seat-charts.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ mix('/js/jquery.seat-charts.min.js') }}"></script>
    <script>
        var movies = @json($movies);
        var reservation = @json($reservation);
    </script>
    <script src="{{ mix('/js/seats.js') }}"></script>
@endsection