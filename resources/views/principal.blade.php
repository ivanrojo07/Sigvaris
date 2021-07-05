<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkboxes.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
        integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <script src="{{ asset('js/app.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="             crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

</head>

<body>
    <div class="row p-0 m-0">
        <div class="col-12 p-0 m-0">
            <a href="{{ url('/inicio') }}">
                <img id="imagenHeaderid" src="{{asset('img/header.jpg')}}" class="img-fluid" alt="Sigvaris.">
            </a>
        </div>
    </div>
    <div class="row m-0 p-0">
        <div class="col-12 m-0 p-0">
            @if (Auth::check())                        
                @include('nav')
            @endif
            
        </div>
    </div>
    <div class="container-fliud" style="margin-top: 20px">
        @yield('content')
    </div>
    <script type="text/javascript" src="{{ asset('js/forms.js') }}"></script>
    <script>
        function deleteFunction(etiqueta) {
            event.preventDefault(); // prevent form submit
            var form = event.target.form; // storing the form
            swal({
                title: "¿Estas seguro?",
                text: "Si eliminas, no podras recuperar tu información.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "SI",
                cancelButtonText: "¡NO!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (!isConfirm) {

                    swal("Cancelado", "", "error");
                } else {

                    document.getElementById(etiqueta).submit();          // submitting the form when user press yes
                }
            });
        }
    </script>
</body>
<div id="app"></div>
@include('sweet::alert')

</html>