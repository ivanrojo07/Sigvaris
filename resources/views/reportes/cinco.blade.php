@extends('principal')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Pacientes nuevos  y recompra</h3>
        </div>
        {{-- Buscador de pacientes --}}
        <div class="card-body">
            <form action="{{route('reportes.5')}}" method="POST" class="form-inline">
                @csrf
                {{-- Año inicial --}}
                <label for="anioInicial">DE: </label>
                <div class="input-group mr-3 ml-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Año</div>
                    </div>
                    <input type="number" class="form-control" id="anioInicial" name="anioInicial" required min="2010"
                        max="2100">
                </div>
                {{-- Año final --}}
                <label for="anioFinal">A: </label>
                <div class="input-group mr-3">
                    {{-- <label for="anioFinal">A: </label> --}}
                    <div class=" ml-3 input-group-prepend">
                        <div class="input-group-text">Año</div>
                    </div>
                    <input type="number" class="form-control" id="anioFinal" name="anioFinal" required min="2010"
                        max="2100">
                </div>
                {{-- Selección de tipo --}}
                <div class="btn-group btn-group-toggle mr-3" data-toggle="buttons">
                    <label class="btn btn-success active">
                        <input type="radio" name="opciones" value="primeraVez" id="option1" autocomplete="off" checked>
                        1° vez
                    </label>
                    <label class="btn btn-success">
                        <input type="radio" name="opciones" value="recompra" id="option3" autocomplete="off"> Recompra
                    </label>
                </div>
                <button class="btn btn-primary">Buscar</button>
            </form>
             <form action="{{route('reportes.5.export')}}"   method="POST" class="form-inline">
                 @csrf
                 <input type="hidden" name="pacientes_" value="{{ $pacientes }}">
                  <input type="hidden" name="anios_" value="{{json_encode($anios)}}">
                  <input type="hidden" name="meses_" value="{{ json_encode($meses)}}">
                  <input type="hidden" name="numPacientesPorAnio" value="{{ json_encode($numPacientesPorAnio)}}">
                  <input type="hidden" name="opcion" value="{{ $opcion}}">
                <button class="btn btn-primary">Exportar</button>
                 </form>
        </div>
        {{-- @if ( isset($pacientes_sin_compra) ) --}}
        {{-- TABLA DE PACIENTES --}}
        <div class="card-body">
              @include('reportes.tableCinco',[$pacientes,$anios,$meses,$opcion,$numPacientesPorAnio,])
           <!--  <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="tabla">
                <thead>
                    <tr class="info">
                        <th>mes</th>
                        @foreach ($anios as $anio)
                        <th>{{$anio}}</th>
                        @endforeach
                    </tr>

                </thead>
                <tbody>

                    @foreach ($meses as $mes)
                    <tr>
                        <td>{{$mes}}</td>
                        @foreach($anios as $key => $anio)
                        {{--  --}}
                        @if ($opcion == "primeraVez")
                        <td>
                            {{
                                App\Paciente::whereYear('created_at',$anio)
                                ->whereMonth('created_at',$mes)
                                ->has('ventas')
                                ->get()
                                ->filter( function($paciente) use ($mes, $anio){
                                    return $paciente->ventas()->count() == 1;
                                } )->count()
                            }}
                        </td>
                        @else
                        <td>{{
                                App\Paciente::whereYear('created_at',$anio)
                                ->whereMonth('created_at',$mes)
                                ->has('ventas')
                                ->get()
                                ->filter( function($paciente){
                                    return $paciente->ventas->count() > 1;
                                } )->count()
                            }}
                        </td>
                        @endif

                        @endforeach
                    </tr>
                    @endforeach



                </tbody>
            </table> -->
        </div>
        {{-- @endif --}}
        {{-- GRAFICA DE PACIENTES --}}
        <div class="card-body">
            <canvas id="canvas" height="280" width="600"></canvas>
        </div>
        {{-- BOTÓN DE DESCARGA PDF --}}
        <div class="card-body">
            <button class="btn btn-success" id="download-pdf">Descargar PDF</button>
        </div>
    </div>
</div>

{{-- SCRIPTS PARA BUSQUEDA EN TABLA --}}
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" defer></script>
<script>
    $(document).ready(function() {
        $('#tabla').DataTable();
    } );
</script>

{{-- SCRIPT PARA DESCARGAR EN PDF --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>


{{-- SCRIPTS PARA GRAFICAR TABLAS --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js" charset="utf-8">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script>
<script>
    var url = "{{url('stock/chart')}}";
// var Years = new Array("2019", "2020", "2021");
var Years = <?php echo json_encode($anios) ?>;
var Labels = new Array("l1", "l2", "l3");
// var Prices = new Array("100", "200", "300");
var Prices = <?php echo json_encode($numPacientesPorAnio) ?>;
console.log('pa por año', Prices)
$(document).ready(function(){
    // $.get(url, function(response){
    // response.forEach(function(data){
    //     Years.push(data.stockYear);
    //     Labels.push(data.stockName);
    //     Prices.push(data.stockPrice);
    // });


    var ctx = document.getElementById("canvas").getContext('2d');
    ctx.fillStyle = "#FFFFFF";
        var myChart = new Chart(ctx, {
            type: 'bar',
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

//add event listener to 2nd button
document.getElementById('download-pdf').addEventListener("click", downloadPDF2);

//download pdf form hidden canvas
function downloadPDF2() {
	var newCanvas = document.querySelector('#canvas');

  //create image from dummy canvas
	var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
  
  	//creates PDF from img
	var doc = new jsPDF('landscape');
	doc.setFontSize(20);
	doc.text(10, 10, "Pacientes por año");
	doc.addImage(newCanvasImg, 'PNG', 10, 10, 280, 150 );
	doc.save('pacientes-por-anio.pdf');
 }

});



</script>


@endsection