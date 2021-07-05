@extends('principal')
@section('content')
    <div class="container">
        <div class="card">
            {{-- TITULO DE LA SECCIÓN ACTUAL --}}
            <div class="card-header">
                <h3>Prendas vendidas por rango de fecha</h3>
            </div>
            {{-- CONTENEDOR BUSCADOR DE PACIENTES --}}
            <div class="card-body">
                    
                <form action="{{route('reportes.3')}}" method="POST">
                        <div class="row">
                    @csrf
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="opcionBusqueda" required id="opcionDia" value="dia">
                                <label for="opcionDia">Día</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="opcionBusqueda" required id="opcionSemana" value="semana">
                                <label for="opcionDia">Semana</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="opcionBusqueda" required id="opcionMes" value="mes">
                                <label for="opcionMes">Mes</label>
                            </div>
                           <!--  <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="opcionBusqueda" required id="opcionTrimestre" value="trimestre">
                                <label for="opcionTrimestre">Trimestre</label>
                            </div> -->
                        </div>

                        <div class="col-12">
                            <div class="row" id="contenedorInputsBusqueda">

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                {{-- INPUT OFICINA --}}
                                <div class="col-12 col-md-4">
                                    <div class="for-group mr-4">
                                        <label for="oficinaId"></label>
                                        <select name="oficina_id" id="selectOficina" class="form-control">
                                            <option value="">Todas</option>
                                            @foreach ($oficinas as $oficina)
                                                <option value="{{$oficina->id}}">{{$oficina->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                <div class="col-12 col-md-4 mt-3">
                                    <button class="btn btn-primary">Buscar</button>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                </form>
                @if ( isset($arregloFechasConVentas) )

                                <hr>
                                <br>
                                <form action="{{route('reportes.3.export')}}" method="POST">
                                         @csrf
                                          <input type="hidden"  name="fechaFinal" value="{{$fechaFinal}}">
                                           <input type="hidden"  name="fechaInicial" value="{{$fechaInicial}}">
                                            <input type="hidden"  name="fechaFinal" value="{{$anioFin != null ?$anioFin:''}}">
                                           <input type="hidden"  name="fechaInicial" value="{{$mesIni != null ?$mesIni:''}}">
                                            <input type="hidden"  name="fechaFinal" value="{{$mesFin  != null ?$mesFin:''}}">
                                           <input type="hidden"  name="fechaInicial" value="{{$anioIni!= null ?$anioIni:''}}">
                                         
                                    <button class="btn btn-primary">EXPORTAR</button>
                                </form>
                                @endif
            </div>
            @if ( isset($arregloFechasConVentas) )
            {{-- LISTA DE PACIENTES --}}
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                    <thead>
                        <tr class="info">
                            <th>Fecha</th>
                            <th> Pacientes 1 prenda</th>
                            <th># Prendas > 1 prenda</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($ventas as $paciente_id => $ventasPorFecha) --}}
                            @for ($i = 0; $i < count($arregloFechasConVentas); $i++)
                                
                                <tr>
                                    <td>{{$arregloFechasConVentas[$i]}}</td>
                                    <td>{{$arregloTotalPacientesConUnProducto[$i]}}</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>{{$arregloFechasConVentas[$i]}}</td>
                                    <td>{{$arregloTotalPacientesConMasDeUnProducto[$i]}}</td>
                                    <td class="text-success">>1</td>
                                </tr>
                            @endfor
                        {{-- @endforeach --}}
                    </tbody>    
                </table>
                <div class="row mt-3">
                    <div class="col-3">
                        <label for="" class="text-uppercase text-muted">
                            TOTAL PACIENTES
                        </label>
                        <input type="text" value="{{$totalPacientesConUnaPrenda + $totalPacientesConMasDeUnaPrenda}}" class="form-control" readonly>
                    </div>
                    <div class="col-3">
                        <label for="" class="text-uppercase text-muted">
                            PACIENTES 1 PRENDA
                        </label>
                        <input type="text" value="{{$totalPacientesConUnaPrenda}}" class="form-control" readonly>
                    </div>
                    <div class="col-3">
                        <label for="" class="text-uppercase text-muted">
                            PACIENTES > 1 PRENDA
                        </label>
                        <input type="text" value="{{$totalPacientesConMasDeUnaPrenda}}" class="form-control" readonly>
                    </div>
                </div>
            </div>
            {{-- INFORMACION GENERAL DE LA BUSQUEDA --}}
            {{-- <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Total de ventas</label>
                            <input type="text" class="form-control" readonly value="{{ count( $ventas->pluck('productos')->flatten() ) }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Total de días</label>
                            <input type="text" class="form-control" readonly value="{{ count( $rangoDias ) }}">
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- GRAFICA DE TABLA --}}
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

var arregloSumaPacientes = {!! json_encode([ $totalPacientesConUnaPrenda, $totalPacientesConMasDeUnaPrenda ]) !!};
arregloSumaPacientes = Object.values(arregloSumaPacientes);

// Global Options:
Chart.defaults.global.defaultFontColor = 'black';
Chart.defaults.global.defaultFontSize = 16;

var data = {
  labels: ['Una prenda', '> Una prenda'],
  datasets: [{
      label: "Numero de pacientes",
      fill: false,
      lineTension: 0.1,
      backgroundColor: "rgba(50,200,50,0.9)",
      borderColor: "rgba(50,200,50,0.9)", // The main line color
      borderCapStyle: 'square',
      borderDash: [], // try [5, 15] for instance
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: "black",
      pointBackgroundColor: "white",
      pointBorderWidth: 1,
      pointHoverRadius: 8,
      pointHoverBackgroundColor: "red",
      pointHoverBorderColor: "brown",
      pointHoverBorderWidth: 2,
      pointRadius: 4,
      pointHitRadius: 10,
      // notice the gap in the data and the spanGaps: true
      data: arregloSumaPacientes,
      spanGaps: true,
    }
  ]
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
                     labelString: '# pacientes vs # prendas',
                     fontSize: 20 
                  }
            }]            
        }  
};

// Chart declaration:
var myBarChart = new Chart(ctx, {
  type: 'bar',
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
	doc.text(15, 15, "Prendas vendidas");
	doc.addImage(newCanvasImg, 'PNG', 10, 10, 280, 150 );
	doc.save('prendas-vendidas.pdf');
 }

</script>

<script>

function mostrarInputsBusquedaDia(){

    $('#contenedorInputsBusqueda').html(`

    <div class="col-12 col-md-4">

        <div class="form-group mr-3">
            <label for="fechaInicial"></label>
            <input type="date" class="form-control" name="fechaInicial" value="{{Request::old('fechaInicial')}}" id="fechaInicial" required>
        </div>

    </div>

    
    {{-- INPUT DE FECHA FINAL --}}

    <div class="col-12 col-md-4">

        <div class="form-group mr-4">
            <label for="fechaFinal"></label>
            <input type="date" class="form-control" name="fechaFinal" id="fechaFinal" value="{{Request::old('fechaFinal')}}" required>
        </div>

    </div>
    
    `);

}

function mostrarInputsBusquedaSemana(){
    $('#contenedorInputsBusqueda').html(`
    
    <div class="col-12 col-md-4">

        <div class="form-group mr-3">
            <label for="fechaInicial"></label>
            <input type="date" class="form-control" name="fechaInicial" id="fechaInicial" required>
        </div>

    </div>

    <div class="col-12 col-md-4">

        <div class="form-group mr-3">
            <label for="fechaFinal"></label>
            <input type="date" class="form-control" name="fechaFinal" id="fechaFinal" required>
        </div>

    </div>
    
    `);
}

function mostrarInputsBusquedaMes(){
    $('#contenedorInputsBusqueda').html(`
    
    <div class="col-12 col-md-4">

        <div class="form-group mr-3">
            <label for="mesInicial"></label>
            <input type="month" class="form-control" name="mesInicial" id="mesInicial" placeholder="2019-04"
                pattern="2[0-9]{3,3}-((0[1-9])|(1[012]))" title="Escriba una fecha valida AAAA-MM" required>
        </div>

    </div>

    <div class="col-12 col-md-4">

        <div class="form-group mr-3">
            <label for="mesFinal"></label>
            <input type="month" class="form-control" name="mesFinal" id="mesFinal" placeholder="2019-04"
                pattern="2[0-9]{3,3}-((0[1-9])|(1[012]))" title="Escriba una fecha valida AAAA-MM" required>
        </div>

    </div>
    
    `);
}

function mostrarInputsBusquedaTrimestre(){
    $('#contenedorInputsBusqueda').html(`
    
    <div class="col-12 col-md-4">

        <div class="form-group mr-3">
            <label for="mesInicial"></label>
            <input type="month" class="form-control" name="mesInicial" id="mesInicial" placeholder="2019-04"
                pattern="2[0-9]{3,3}-((0[1-9])|(1[012]))" title="Escriba una fecha valida AAAA-MM" required>
        </div>

    </div>
    
    `);
}

function mostrarInputsBusqueda(OPCION){

    if(OPCION == 'dia'){
        mostrarInputsBusquedaDia();
    }else if(OPCION == 'semana'){
        mostrarInputsBusquedaSemana();
    }else if(OPCION == 'mes'){
        mostrarInputsBusquedaMes();
    }else if(OPCION == 'trimestre'){
        mostrarInputsBusquedaTrimestre();
    }

}

$(document).ready(function(){

mostrarInputsBusqueda();

$(document).on('change', 'input[name=opcionBusqueda]', function(){
    const OPCION = $(this).val();
    mostrarInputsBusqueda(OPCION);
});


});

$(document).on('change', '#selectOficina', function(){
    const OFICINA_ID = $(this).val();
    actualizarOpcionesFitters(OFICINA_ID);
});

</script>

@endsection