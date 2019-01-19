
@extends('layouts.app')

@section('title', 'Reservas')

@section('content')


<div class="row">

    <div class="col-md-6">
        <h1>Reservas</h1>
    </div>

    <div class="col-md-6">
        {{link_to('reservation/manageReservation', 'Nuevo',  array('class' => 'btn btn-primary float-right'), [])}}
    </div>

</div>

<div class="table-responsive">
<table class="table  table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            {{-- <td>ID</td> --}}
            <td>Pelicula</td>
            <td>Usuario</td>
            <td>Personas</td>
            <td>Fecha</td>
            <td>Sillas</td>

            <td>Acciones</td>
        </tr>
    </thead>
    <tbody>
    @foreach($reservations as $key => $value)
        <tr>
            {{-- <td>{{ $value->id }}</td> --}}
            <td>{{ $value->movie->name }}</td>
            <td>{{ $value->user->name . ' ' . $value->user->last_name}}</td>
            <td>{{ $value->people }}</td>
            <td>{{ $value->reservation_date }}</td>
            <td>

                @foreach($value->positions as $keyP => $valueP)
                <span>{{$valueP->x.$valueP->y}}</span>
                @endforeach

            </td>
            
            {{-- <td>{{ $value->last_name }}</td>
            <td>{{ $value->email }}</td> --}}
            
            <!-- we will also add show, edit, and delete buttons -->
            <td>
                <!-- delete the nerd (uses the destroy method DESTROY /reservation/{id} -->
                <!-- we will add this later since its a little more complicated than the other two buttons -->

                <!-- show the nerd (uses the show method found at GET /reservation/{id} -->
                <a class="btn btn-small btn-success" href="{{ URL::to('reservation/' . $value->id) }}">Ver</a>

                <!-- edit this nerd (uses the edit method found at GET /reservation/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('reservation/manageReservation/' . $value->id ) }}">Editar</a>

                <!-- delete the nerd (uses the destroy method DESTROY /reservation/{id} -->
                {{ Form::open(array('url' => 'reservation/' . $value->id, 'class' => 'pull-right')) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Eliminar', array('class' => 'btn btn-danger')) }}
                {{ Form::close() }}

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

{{-- Pagination links --}}
{{ $reservations->links() }}

@endsection