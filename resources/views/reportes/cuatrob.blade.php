@extends('principal')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Prendas por SKU</h3>
        </div>
        {{-- Buscador de pacientes --}}
        <div class="card-body">
            <form action="{{route('reportes.4b')}}" method="POST" class="form-inline">
                @csrf
                {{-- Input de fecha inicial --}}
                <div class="form-group mr-3">
                    <label for="fechaInicial"></label>
                    <input type="date" class="form-control" name="fechaInicial" id="fechaInicial" required>
                </div>
                {{-- Input fecha final --}}
                <div class="form-group mr-4">
                    <label for="fechaFinal"></label>
                    <input type="date" class="form-control" name="fechaFinal" id="fechaFinal" required>
                </div>
                <button class="btn btn-primary">Buscar</button>
            </form>
          <!--   <div class="form-group mr-4"> -->
            <hr>
            <form action="{{route('reportes.4b.export')}}"   method="POST" class="form-inline">
                 @csrf
                 <input type="hidden" name="Ventas" value="{{ $Ventas }}">
                  <input type="hidden" name="VentasPrendas" value="{{$totalPrendasVendidas}}">
                <button class="btn btn-primary">Exportar</button>
            </form>
          <!--    </div> -->
        </div>

            @if(sizeof($skusConVentas) >0)
            @include('reportes.tableCuatrob',[$skusConVentas,$totalPrendasVendidas])
            @endif
            <div class="row">
                <div class="col-4">
                    <label for="" class="text-uppercase text-muted">TOTAL PACIENTES</label>
                    <input value="{{$skusConVentas ? $skusConVentas->flatten()->pluck('ventas')->flatten()->pluck('paciente')->flatten()->unique()->count() : ''}}" type="text" readonly class="form-control">
                </div>
                <div class="col-4">
                    <label for="" class="text-uppercase text-muted">TOTAL PRENDAS VENDIDAS</label>
                    <input value="{{$totalPrendasVendidas}}" type="text" readonly class="form-control">
                </div>
            </div>
        </div>
        {{-- INFORMACION GENERAL --}}
        {{-- <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <strong>TOTAL PRENDAS</strong>
                    <input type="text" readonly value="0" class="form-control">
                </div>
                <div class="col-12 col-md-3">
                    <strong>TOTAL SKU</strong>
                    <input type="text" readonly value="0" class="form-control">
                </div>
            </div>
        </div> --}}
        
        {{-- <div class="card-body">
            <canvas id="canvas" height="280" width="600"></canvas>
        </div> --}}
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#listaEmpleados').DataTable();
    } );
</script>

@endsection