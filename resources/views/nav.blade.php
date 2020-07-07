<nav class="navbar navbar-expand-md  navbar-dark" style="background-color: #1c3160;">
    
    <a class="navbar-brand imagenHeader-i" style="display: none" href="/">
        Sigvaris
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            @if (Auth::user()->role->proveedores)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Proveedores
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('proveedores.create') }}"><span>Alta</span><i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ route('proveedores.index') }}"><span>Buscar</span><i class="fa fa-search float-right"></i></a>
                    </div>
                </li>    
            @endif

            @if (Auth::user()->role->pacientes)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Pacientes
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('pacientes.create') }}"><span>Alta</span><i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ route('pacientes.index') }}"><span>Buscar</span><i class="fa fa-search float-right"></i></a>
                    </div>
                </li>                
            @endif            
            
            @if (Auth::user()->role->doctores)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Doctores
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('doctores.create') }}"><span>Alta</span><i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ route('doctores.index') }}"><span>Buscar</span><i class="fa fa-search float-right"></i></a>
                    </div>
                </li>
            @endif

            @if (Auth::user()->role->recursos_humanos)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Recursos Humanos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{url('empleados/create')}}"><span>Alta</span><i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('empleados') }}"><span>Buscar</span><i class="fa fa-search float-right"></i></a>
                    </div>
                </li>    
            @endif
            
            @if (Auth::user()->role->recursos_humanos)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Precargas
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{route('descuentos.index')}}">Descuentos<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('contratos') }}">Contratos<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('/faltas') }}">Faltas<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('/areas') }}">Áreas<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('/puestos') }}">Puestos<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('/bajas') }}">Bajas<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('/bancos') }}">Bancos<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ route('niveles.index') }}">Niveles<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ route('estados.index') }}">Estados<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('/giros') }}">Giros<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{ route('hospitals.index') }}">Hospitales<i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{route('foliosSigpesos.index')}}">Folios Sigpesos<i class="fa fa-plus float-right"></i></a>
                    </div>
                </li>    
            @endif
            
            @if (Auth::user()->role->punto_de_venta)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Punto de Venta
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{route('ventas.create')}}"><span>Venta</span><i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{route('ventas.index')}}"><span>Historial</span><i class="fa fa-search float-right"></i></a>
                        <a class="dropdown-item" href="{{route('corte-caja.index')}}"><span>Corte de caja</span><i class="fa fa-search float-right"></i></a>
                        {{-- <a class="dropdown-item" href="{{route('cambio-fisico.create')}}"><span>Cambio físico</span><i class="fa fa-search float-right"></i></a> --}}
                        {{--  <a class="dropdown-item" href="{{url('/damage')}}"><span>Damage</span><i class="fa fa-search float-right"></i></a>--}}
                        {{-- <a class="dropdown-item" href="{{route('cambio-fisico.create')}}"><span>Cambio físico</span><i class="fa fa-search float-right"></i></a> --}}
                    </div>
                </li>    
            @endif
            
            @if (Auth::user()->role->productos)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Productos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{route('productos.create')}}"><span>Alta</span><i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{route('productos.index')}}"><span>Buscar</span><i class="fa fa-search float-right"></i></a>
                        <a class="dropdown-item" href="{{ url('import-export-csv-excel') }}"><span>Excel</span><i class="fa fa-file-excel float-right" aria-hidden="true"></i></a>
                        <a class="dropdown-item" href="{{ route('productos.inventario') }}"><span>Inventario</span><i class="fa fa-history float-right" aria-hidden="true"></i></a>
                        <a class="dropdown-item" href="{{ route('productos.damage') }}"><span>Damage</span><i class="fa fa-window-close-o" aria-hidden="true"></i></a>
                        <a class="dropdown-item" href="{{ route('productos.surtido') }}"><span>Surtido de producto</span></a>
                        <a class="dropdown-item" href="{{ route('productos.inventario.historial') }}"><span>Historial</span><i class="fa fa-search float-right"></i></a>
                        <a class="dropdown-item" href="{{ route('productos.inventario.historialSurtido') }}"><span>Historial de Surtido</span><i class="fa fa-search float-right"></i></a>
                        <a class="dropdown-item" href="{{route('negado.index')}}"><span>Negado</span><i class="fa fa-plus float-right"></i></a>

                        
                    </div>
                </li>    
            @endif
            
            @if (Auth::user()->role->crm)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        C.R.M.
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{route('crm.index')}}"><span>General</span><i class="fa fa-search float-right"></i></a>
                    </div>
                </li>    
            @endif
            
            @if (Auth::user()->role->oficinas)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Tiendas
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{route('oficinas.index')}}"><span>Buscar</span><i class="fa fa-search float-right"></i></a>
                        <a class="dropdown-item" href="{{route('oficinas.create')}}"><span>Crear</span><i class="fa fa-plus float-right"></i></a>
                    </div>
                </li>    
            @endif
            @if (Auth::user()->role->facturacion)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        Facturacion
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{route('facturas.index')}}"><span>Buscar</span><i class="fa fa-search float-right"></i></a>
                        <a class="dropdown-item" href="{{route('facturas.create')}}"><span>Crear</span><i class="fa fa-plus float-right"></i></a>
                        <a class="dropdown-item" href="{{route('devolucion.indexall')}}"><span>Devolucion de dinero</span></a>
                    </div>
                </li>    
            @endif

             @if (Auth::user()->role->roles || Auth::user()->role->usuarios)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                            Usuarios
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                @if (Auth::user()->role->roles)
                            <a class="dropdown-item" href="{{route('roles.index')}}"><span>Roles</span><i class="fa fa-search float-right"></i></a>
                @endif
                @if (Auth::user()->role->usuarios)
                            <a class="dropdown-item" href="{{route('usuarios.index')}}"><span>Usuarios</span><i class="fa fa-search float-right"></i></a>
                @endif
            @endif

            @if (Auth::user()->role->reportes || Auth::user()->id == 1 )
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                            Reportes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('reportes.1')}}"><span>Pacientes que no compraron</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.2')}}"><span>Prendas compradas por paciente</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.3')}}"><span>Prendas vendidas por rango de fecha</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.4a')}}"><span>Prendas compradas</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.4b')}}"><span>Prendas por SKU</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.4c')}}"><span>Prendas vendidas</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.4d')}}"><span>Prendas vendidas por año</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.5')}}"><span>Pacientes por año y mes</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.9')}}"><span>Prendas vendidas por SKU</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.10')}}"><span>Recomendaciones de doctor</span><i class="fa fa-search float-right"></i></a>
                            <a class="dropdown-item" href="{{route('reportes.metas')}}"><span>Ventas de fitter</span><i class="fa fa-search float-right"></i></a>
                            {{-- <a class="dropdown-item" href="{{route('reportes.11')}}"><span>11</span><i class="fa fa-search float-right"></i></a> --}}
            @endif


                    </div>
                </li>
            <a  class="btn btn-info btn-sm" href="{{ url('logout') }}">
                    <strong>
                       {{--  <i class="far fa-save"></i> --}}
                       <i class="fas fa-sign-out-alt"></i>
                        Salir
                    </strong>
            </a>


        </ul>
    </div>
</nav>
<!-- <div class="row m-0 p-0 text-white" style="background-color: #1c3160;">
    <div class="col">
        <ul id="pestanias" class="nav nav-tabs">
            <li id="pestania_1" class="nav-item">
                <a class="text-white nav-link pestania" onclick="abrirPestania('pestania_1')" href="#"><span>Prueba </span><span style="font-size: 1.2em;"><i onclick="cerrarPestania('pestania_${no_pestanias}')" class="fas fa-times-circle ml-2"></i></span></a>
            </li>
        </ul>
    </div>
</div> -->
<script>

    var no_pestanias = 0;
    $(document).ready(function(){

        //CREAR PESTAÑA
        $('.dropdown-item').click({url: 'www.facebook.com'}, crearPestania);

        //CERRAR PESTAÑA





        



    });

    function crearPestania(event){
        no_pestanias++;
        //alert(no_pestanias);
        let titulo = event.data.url;
        let nuevapestania = `<li id="pestania_${no_pestanias}" class="nav-item">
                                <a class="text-white nav-link pestania" onclick="abrirPestania('pestania_${no_pestanias}')" href="#"><span> ${titulo + no_pestanias} </span><span style="font-size: 1.2em;"><i onclick="cerrarPestania('pestania_` + no_pestanias + `')" class="fas fa-times-circle ml-2"></i></span></a>
                            </li>`
        $('#pestanias').append(nuevapestania);
        //return false;
    }

    function cerrarPestania(id){
        alert('#'+id);
        $('#'+id).remove();
    }

    function abrirPestania(id){
        $('.nav-link.pestania').removeClass('active text-dark');
        $('.nav-link.pestania').addClass('text-white');
        $('#'+id).find('a').addClass('active text-dark');
        
    }
</script>