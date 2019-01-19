
<nav class="navbar navbar-dark bg-dark navbar-expand-lg  ">
    <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Reservas') }}
        </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
      <li class="nav-item">
        <a class="nav-link {{ Request::segment(1) === 'user' ? 'active' : '' }}" href="{{url('/user')}}">Usuarios</a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Request::segment(1) === 'movie' ? 'active' : '' }}" href="{{url('/movie')}}">Películas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Request::segment(1) === 'reservation' ? 'active' : '' }}" href="{{url('/reservation')}}">Reservas</a>
      </li>      
    </ul>
  </div>
</nav>