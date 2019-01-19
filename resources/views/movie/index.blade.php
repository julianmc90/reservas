
@extends('layouts.app')

@section('title', 'Peliculas')

@section('content')



<div class="row">

    <div class="col-md-6">
        <h1>Películas</h1>
    </div>

    <div class="col-md-6">
        {{link_to('movie/create', 'Nuevo',  array('class' => 'btn btn-primary float-right'), [])}}
    </div>

</div>

<div class="table-responsive">
<table class="table  table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            {{-- <td>ID</td> --}}
            <td>Nombre</td>
            <td>Fecha inicio</td>
            <td>Fecha fin</td>
            <td>Actualizado</td>
            <td>Acciones</td>
        </tr>
    </thead>
    <tbody>
    @foreach($movies as $key => $value)
        <tr>
            {{-- <td>{{ $value->id }}</td> --}}
            <td>{{ $value->name }}</td>
            <td>{{ $value->function_init_date }}</td>
            <td>{{ $value->function_end_date }}</td>
            <td>{{ $value->updated_at }}</td>
            
            <!-- we will also add show, edit, and delete buttons -->
            <td>

                <!-- show the nerd (uses the show method found at GET /movie/{id} -->
                <a class="btn btn-small btn-success" href="{{ URL::to('movie/' . $value->id) }}">Ver</a>

                <!-- edit this nerd (uses the edit method found at GET /movie/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('movie/' . $value->id . '/edit') }}">Editar</a>

                <!-- delete the nerd (uses the destroy method DESTROY /user/{id} -->
                {{ Form::open(array('url' => 'movie/' . $value->id, 'class' => 'pull-right')) }}
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
{{ $movies->links() }}

@endsection