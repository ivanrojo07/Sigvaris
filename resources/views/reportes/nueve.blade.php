@extends('principal')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3> Reporte de prendas vendidas por SKU</h3>
        </div>
        {{-- Buscador de pacientes --}}
        <div class="card-body">
            <form action="{{route('reportes.9')}}" method="POST" class="form-inline">
                @csrf
                {{-- Año inicial --}}
                <div class="input-group mr-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">DE: </div>
                    </div>
                    <input type="date" class="form-control" id="fechaInicial" name="fechaInicial" required>
                </div>
                {{-- Año final --}}
                <div class="input-group mr-3">
                    {{-- <label for="anioFinal">A: </label> --}}
                    <div class="input-group-prepend">
                        <div class="input-group-text">A: </div>
                    </div>
                    <input type="date" class="form-control" id="fechaFinal" name="fechaFinal" required>
                </div>
                <button class="btn btn-primary">Buscar</button>
            </form>
        </div>
        @if ( isset($ventasPorSku) )
            {{-- Lista de pacientes --}}
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                    <thead>
                        <tr class="info">
                            <th>SKU</th>
                            <th># prendas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventasPorSku as $key => $ventas)
                        {{-- {{ dd( }} --}}
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$ventas->flatten()->pluck('pivot')->flatten()->pluck('cantidad')->flatten()->sum()}}</td>
                            </tr>
                        @endforeach
                    </tbody>    
                </table>
            </div>
        @endif
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