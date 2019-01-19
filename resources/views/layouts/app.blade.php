<html>
    <head>
        <title>{{ config('app.name', 'Reservas') }} - @yield('title')</title>
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        

        <!-- Styles -->
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
        
        @yield('styles')

        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};

            //Base url fo Xhrs
            var baseUrl = '{{url('/')}}';
        </script>
    </head>
    <body>
        
        <div id="app">

            @include('includes.navbar')

            <div class="container mt-5">
                <!-- will be used to show any messages -->
                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif

                <!-- will be used to show any messages -->
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
            </div>

            <div class="container mt-5">
                @yield('content')
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ mix('/js/app.js') }}"></script>

        @yield('scripts')
    </body>
</html>