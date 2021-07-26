@extends('principal')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Reporte de pacientes que no compraron</h3>
        </div>
        {{-- Buscador de pacientes --}}
        <div class="card-body">
            <form action="{{route('reportes.1')}}" method="POST" class="form-inline">
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
                {{-- Input oficina --}}
                <div class="form-group mr-4">
                    <label for="oficina_id"></label>
                    <select name="oficinaId" class="form-control" id="selectOficina">
                        <option value="">Todas</option>
                        @foreach ($oficinas as $oficina)
                            <option value="{{$oficina->id}}">{{$oficina->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Input fitter --}}
                <div class="form-group mr-4">
                    <label for="fitters"></label>
                    <select name="empleadoFitterId" class="form-control" id="selectEmpleadosFitter">
                        <option value="">Todos</option>
                        @foreach ($empleadosFitter as $empleadoFitter)
                            <option value="{{$empleadoFitter->id}}">
                                {{$empleadoFitter->nombre}} {{$empleadoFitter->appaterno}} {{$empleadoFitter->apmaterno}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary">Buscar</button>
            </form>
        </div>
        @if ( isset($pacientes_sin_compra) )
            {{-- TABLA DE PACIENTES --}}
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                    <thead>
                        <tr class="info">
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Apellido paterno</th>
                            <th>Apellido materno</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pacientes_sin_compra as $key => $paciente)
                            <tr>
                                <td>{{$paciente->created_at}}</td>
                                <td>{{$paciente->nombre}}</td>
                                <td>{{$paciente->paterno}}</td>
                                <td>{{$paciente->materno}}</td>
                                <td><button class="btn btn-warning">Ver</button></td>
                            </tr>
                        @endforeach
                    </tbody>    
                </table>
            </div>
            {{-- DATOS GENERALES DE LA TABLA --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4"></div>
                    <div class="col-12 col-md-4"></div>
                    <div class="col-12 col-md-4">
                        {{-- <h5 class="text-center">
                            <strong>TOTAL DE PACIENTES</strong>
                            <br>
                            {{count($pacientes_sin_compra)}}
                        </h5> --}}
                        <div class="form-group">
                            <label for=""><strong>TOTAL DE PACIENTES</strong></label>
                            <input type="text" class="form-control" readonly value="{{count($pacientes_sin_compra)}}">
                        </div>
                    </div>
                </div>
            </div>
            {{-- GRAFICA DE LA TABLA --}}
            <div class="card-body">
                <canvas id="canvas" height="280" width="600"></canvas>
            </div>
            {{-- BOTÓN DE DESCARGA PDF --}}
            <div class="card-body">
                <button class="btn btn-success" id="download-pdf">Descargar PDF</button>
            </div>
        @endif
    </div>
</div>

<script src="{{ URL::asset('js/handleFitters.js') }}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>    
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#listaEmpleados').DataTable();
    } );
</script>

{{-- SCRIPTS PARA GRAFICAR DE TABLA --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script>
<script>


var Years = <?php echo json_encode($fechas_pacientes_sin_compra) ?>;
var Labels = new Array("l1", "l2", "l3");
var Prices = <?php echo json_encode($num_pacientes_por_fecha) ?>;

//download pdf form hidden canvas
function downloadPDF2() {
	var newCanvas = document.querySelector('#canvas');

  //create image from dummy canvas
	var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
  
  	//creates PDF from img
	var doc = new jsPDF('landscape');
	doc.setFontSize(20);
	doc.text(15, 15, "Pacientes sin compras");
	doc.addImage(newCanvasImg, 'PNG', 10, 10, 280, 150 );
	doc.save('pacientes-sin-compras.pdf');
 }



/**
* ==========================
* EVENTOS
* ==========================
*/

//add event listener to 2nd button
// document.getElementById('download-pdf').addEventListener("click", downloadPDF2);

$(document).on('click', '#download-pdf', function(){
    downloadPDF2();
});


$(document).on('change', '#selectOficina', function(){
    const OFICINA_ID = $(this).val();
    actualizarOpcionesFitters(OFICINA_ID);
});

$(document).ready(function(){

    var ctx = document.getElementById("canvas").getContext('2d');

    ctx.fillStyle = "#FFFFFF";

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels:Years,
                datasets: [{
                    label: 'Total de pacientes',
                    data: Prices,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    // });
});

</script>



@endsection