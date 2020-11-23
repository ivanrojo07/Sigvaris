@extends('principal')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">CORTE DE CAJA POR DIA</h4>
                </div>
                {{-- Busqueda por fecha --}}
                <div class="card-body">
                    <form class="form-inline" method="POST" action="{{route('reportes.cortecaja')}}">
                        @csrf
                        {{-- Input de fecha inicial --}}
                        <div class="form-group mr-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">FECHA DE CORTE DE CAJA:</div>
                            </div>
                            <input type="date" class="form-control input-fecha" name="fecha_inicial" id="fechaInicial">
                        </div>
                        
                        {{-- Boton submit para fechas --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">GENERAR</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    {{-- Tabla de las prendas --}}
                    <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaPrendas">
                        <thead>
                            <tr class="info">
                                <th>#</th>
                                <th>SKU</th>
                                <th>CANTIDAD</th>
                                <th>UNITARIO (SIN IVA) </th>
                                <th>TOTAL (SIN IVA)</th>
                                 <th>TIENDA</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>    
                    </table>
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
        $('#listaPrendas').DataTable();
    } );
</script>


<script>
   $('.input-fecha').change(function(){
       
   });
</script>

@endsection