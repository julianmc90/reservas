
@extends('layouts.app')

@section('title', 'ver')

@section('content')

<br/>
<div class="row">

    <div class="col-md-6">
        
        <h3>Usuario:</h3>
        {{ $reservation->user->name . ' ' .$reservation->user->last_name. ', ' . $reservation->user->email }}
 
    </div>

    <div class="col-md-6">

        <h3>Pelicula:</h3>

       {{ $reservation->movie->name . ', ' .$reservation->movie->function_init_date. ', ' . $reservation->movie->function_end_date}}
   
        
    </div>

    <div class="col-md-6">
    <h3>Fecha</h3>
        {{$reservation->reservation_date}}
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

    </div>

</div>


@endsection



@section('styles')
 <link href="{{ mix('/css/jquery.seat-charts.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ mix('/js/jquery.seat-charts.min.js') }}"></script>
    <script>
        var reservation = @json($reservation); 
    </script>
    <script src="{{ mix('/js/view-seats.js') }}"></script>
@endsection



