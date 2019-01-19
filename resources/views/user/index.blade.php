
@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')

<div class="row">

    <div class="col-md-6">
        <h1>Usuarios</h1>    
    </div>

    <div class="col-md-6">
        {{link_to('user/create', 'Nuevo',  array('class' => 'btn btn-primary float-right'), [])}}
    </div>

</div>


<div class="table-responsive">
<table class="table  table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            {{-- <td>ID</td> --}}
            <td>Nombres</td>
            <td>Apellidos</td>
            <td>Email</td>
            <td>Actualizado</td>
            <td>Acciones</td>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $key => $value)
        <tr>
            {{-- <td>{{ $value->id }}</td> --}}
            <td>{{ $value->name }}</td>
            <td>{{ $value->last_name }}</td>
            <td>{{ $value->email }}</td>
            <td>{{ $value->updated_at }}</td>
            
            <!-- we will also add show, edit, and delete buttons -->
            <td>

                <!-- show the nerd (uses the show method found at GET /user/{id} -->
                <a class="btn btn-small btn-success" href="{{ URL::to('user/' . $value->id) }}">Ver</a>

                <!-- edit this nerd (uses the edit method found at GET /user/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('user/' . $value->id . '/edit') }}">Editar</a>

                <!-- delete the nerd (uses the destroy method DESTROY /user/{id} -->
                {{ Form::open(array('url' => 'user/' . $value->id, 'class' => 'pull-right')) }}
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
{{ $users->links() }}

@endsection