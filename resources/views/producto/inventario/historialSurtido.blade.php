@extends('principal')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-5">
                {{-- ENCABEZADO DE LA SECCION --}}
                <div class="card-header">
                    <h4 class="text-center">HISTORIAL DE SURTIDO</h4>
                </div>
                {{-- TABLA DEL HISTORIAL --}}
                <div class="card-body">
                    @include('componentes.productos.inventario.historial.tablaSurdidofecha',['historialModificacionesInventario' => $historialModificacionesInventario])
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>    
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaHistorialInventario').DataTable();
    } );
</script>

@endsection