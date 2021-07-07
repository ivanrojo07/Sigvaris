@extends('principal')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>“Total prendas vendidas por año</h3>
            </div>
            {{-- Buscador de pacientes --}}
            <div class="card-body">
                <form action="{{route('reportes.4d')}}" method="POST" class="form-inline">
                    @csrf
                    {{-- INPUT AÑO INICIAL --}}
                    <label for="anioInicial" class="mr-2">de: </label>
                    <div class="input-group mr-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">AÑO: </div>
                        </div>
                    <input type="number" min="2000" max="2100" class="form-control" id="anioInicial" name="anioInicial" value="{{ old('anioInicial') }}" required>
                    </div>
                    {{-- INPUT AÑO FINAL --}}
                    <label for="anioInicial" class="mr-2">a: </label>
                    <div class="input-group mr-3">
                        {{-- <label for="anioFinal">A: </label> --}}
                        <div class="input-group-prepend">
                            <div class="input-group-text">AÑO: </div>
                        </div>
                        <input type="number" min="2000" max="2100" class="form-control" id="anioFinal" name="anioFinal" required value="{{old('anioFinal')}}">
                    </div>
                    <button class="btn btn-primary">Buscar</button>
                </form>
                 <form action="{{route('reportes.4d.export')}}"   method="POST" class="form-inline">
                 @csrf
                 <input type="hidden" name="anio_ini" value="{{ $anioInicial  }}">
                  <input type="hidden" name="anio_fin" value="{{$anioFinal}}">
                  <input type="hidden" name="meses_" value="{{ json_encode($meses)}}">
                <button class="btn btn-primary">Exportar</button>
                 </form>
            </div>
               <div class="card-body">
            @include('reportes.tableCuatrod',[$anioInicial,$meses,$anioFinal])
             </div>
           <!--  @if ( isset($anioInicial) )
                {{-- TABLA DE PACIENTES --}}
                <div class="card-body">
                    <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                        <thead>
                            <tr class="info text-center">
                                <th>mes</th>
                                @for ($anio = $anioInicial; $anio <= $anioFinal; $anio++)
                                <th>{{$anio}}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($meses as $mesNumerico => $mesTextual)
                                <tr>
                                    <td>{{$mesTextual}}</td>
                                    @for ($anio = $anioInicial; $anio <= $anioFinal; $anio++)
                                    <td>{{App\Venta::whereYear('fecha',$anio)->whereMonth('fecha',$mesNumerico)->get()->pluck('productos')->flatten()->pluck('pivot')->flatten()->pluck('cantidad')->flatten()->sum()}}</td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>    
                    </table>
                </div>
                {{-- GRAFICA DE TABLA --}}
                <div class="card-body">
                    <canvas id="canvas" height="280" width="600"></canvas>
                </div>
                {{-- BOTÓN DE DESCARGA PDF --}}
                <div class="card-body">
                    <button class="btn btn-success" id="download-pdf">Descargar PDF</button>
                </div>
            @endif -->
                {{-- GRAFICA DE TABLA --}}
                <div class="card-body">
                    <canvas id="canvas" height="280" width="600"></canvas>
                </div>
                {{-- BOTÓN DE DESCARGA PDF --}}
                <div class="card-body">
                    <button class="btn btn-success" id="download-pdf">Descargar PDF</button>
                </div>
        </div>
    </div>

{{-- SCRIPT PARA DESCARGAR EN PDF --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>

{{-- SCRIPTS PARA GRAFICAR DE TABLA --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script>

<script>

var canvas = document.getElementById("canvas");
var ctx = canvas.getContext('2d');
ctx.fillStyle = "#FFFFFF";

var datasets = new Array();

var aniosSolicitados = {!! json_encode($aniosSolicitados) !!};
var productosPorAnio = {!! json_encode($productosPorAnio) !!};
var aniosYProductosPorMes = {!! json_encode($aniosYProductosPorMes) !!};
console.log(aniosSolicitados);
console.log('aniosYProductosPorMes',Object.values(aniosYProductosPorMes[0])[0]);

for (const i in aniosSolicitados) {
    if (aniosSolicitados.hasOwnProperty(i)) {
        
        const anio = aniosSolicitados[i];

        const color = getRandomColor();    
        
        const objeto = {
            label: aniosSolicitados[i],
            fill: false,
            lineTension: 0.1,
            backgroundColor: color,
            borderColor: color, // The main line color
            borderCapStyle: 'square',
            borderDash: [], // try [5, 15] for instance
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "black",
            pointBackgroundColor: "white",
            pointBorderWidth: 1,
            pointHoverRadius: 8,
            pointHoverBackgroundColor: color,
            pointHoverBorderColor: "brown",
            pointHoverBorderWidth: 2,
            pointRadius: 4,
            pointHitRadius: 10,
            // notice the gap in the data and the spanGaps: true
            data: Object.values(aniosYProductosPorMes[i])[0],
            spanGaps: true,
        };

        datasets.push(objeto);
        
    }
}

function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

// Global Options:
Chart.defaults.global.defaultFontColor = 'black';
Chart.defaults.global.defaultFontSize = 16;

var data = {
  labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
  datasets: datasets
};

// Notice the scaleLabel at the same level as Ticks
var options = {
  scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                },
                scaleLabel: {
                     display: true,
                     labelString: 'Ventas vs Mes',
                     fontSize: 20 
                  }
            }]            
        }  
};

// Chart declaration:
var myBarChart = new Chart(ctx, {
  type: 'line',
  data: data,
  options: options
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
	doc.text(10, 10, "Prendas vendidas por año");
	doc.addImage(newCanvasImg, 'PNG', 10, 10, 280, 150 );
	doc.save('prendas-vendidas-por-anio.pdf');
 }

</script>

@endsection