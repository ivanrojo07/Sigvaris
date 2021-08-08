@extends('principal')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Pacientes nuevos por fecha</h3>
        </div>
        {{-- Buscador de pacientes --}}
        <div class="card-body">
            <form action="{{route('reportes.pacientes')}}" method="POST" class="form-inline">
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
                 <div class="col-12 col-sm-6 col-md-4 mt-2">
                            {{-- INPUT OFICINA --}}
                            <label for="oficina"></label>
                            <select name="oficina_id" id="selectOficina" class="form-control">
                                <option value="">Todas</option>
                                @foreach ($oficinas as $oficina)
                                    <option value="{{$oficina->id}}">{{$oficina->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
            </form>
                       <button id="btnExportar" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar datos a Excel
    </button>
                
            <hr>
            @if(isset($pacientes))
               <div class="card-body">
            <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                <thead>
                    <tr class="info">
                        <th>NUM</th>
                        <th>PACIENTES</th>
                        <th>OFICINA</th>
                    </tr>
                </thead>
                <tbody>
                   
                    @foreach($pacientes as $key => $sku)
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$sku->nombre}} {{$sku->paterno}} {{$sku->materno}}</td>
                        <td> {{$sku->oficina_id= 1 ? "POLANCO": "PERISUR"}}</td>
                    </tr>
                   
                    @endforeach
                </tbody>
                
             </table> 
             @endif
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<!-- script para exportar a excel -->
<script>
    const $btnExportar = document.querySelector("#btnExportar"),
        $tabla = document.querySelector("#listaEmpleados");
        var FitterName = $('#FitterName').val();
        var nameFile = "Ventas fitter_"+FitterName ;
         console.log('FitterName',nameFile);

    $btnExportar.addEventListener("click", function() {
        let tableExport = new TableExport($tabla, {
            exportButtons: false, // No queremos botones
            filename: nameFile, //Nombre del archivo de Excel
            sheetname: "Reporte de prueba", //Título de la hoja
        });
        
        let datos = tableExport.getExportData();
        console.log('datos',datos);
        
        let preferenciasDocumento = datos.listaEmpleados.xlsx;
        tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
    });
</script>

@endsection