@extends('principal')
@section('content')
<script src="https://unpkg.com/xlsx@0.16.9/dist/xlsx.full.min.js"></script>
<script src="https://unpkg.com/file-saverjs@latest/FileSaver.min.js"></script>
<script src="https://unpkg.com/tableexport@latest/dist/js/tableexport.min.js"></script>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>CRM LLamadas por recompra</h3>
        </div>
        {{-- Buscador de pacientes --}}
        <div class="card-body">
            <form action="{{route('reportes.crmR')}}" method="POST" class="form-inline">
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
            @if(isset($CRM))
               <div class="card-body">
            <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                <thead>
                    <tr class="info">
                         <th>NUM</th>
                         <th>Efectivas</th>
                         <th>VENTAS</th>
                         <th>PORCENTAJE</th>
                         
                    </tr>
                </thead>
                <tbody>
                   
                    @foreach($CRM as $key => $sku)
                    <tr>
                        <td>{{$sku['nombre_mes']}}</td>
                       
                        <td>{{$sku['Efectivas']}} </td>
                        <td>{{$sku['ventas']}}</td>
                        <td>{{number_format($sku['porcentaje'],1)}} %</td>
                   
                    </tr>
                   
                    @endforeach
                </tbody>
                
             </table> 
             @endif
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
</div>




<!-- script para exportar a excel -->
<script>
    const $btnExportar = document.querySelector("#btnExportar"),
        $tabla = document.querySelector("#listaEmpleados");
        // var FitterName = $('#FitterName').val();
        // var nameFile = "Ventas fitter_"+FitterName ;
         // console.log('FitterName',nameFile);

    $btnExportar.addEventListener("click", function() {
        let tableExport = new TableExport($tabla, {
            exportButtons: false, // No queremos botones
            filename: "CRM LLAMADAS", //Nombre del archivo de Excel
            sheetname: "CRM LLAMADAS", //Título de la hoja
        });
        
        let datos = tableExport.getExportData();
        console.log('datos',datos);
        
        let preferenciasDocumento = datos.listaEmpleados.xlsx;
        tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
    });
</script>

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




var meses = {!! json_encode($Mes_name) !!};
var crmDatos = {!! json_encode($DatosMesCrm) !!};

var aniosYProductosPorMes = {!! json_encode($DatosMesCrm) !!};
// Global Options:
Chart.defaults.global.defaultFontColor = 'black';
Chart.defaults.global.defaultFontSize = 16;

var data = {
  labels: meses,
  datasets: [{
      label: "CRMS RECOMPRA",
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
      data: crmDatos,
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
                     labelString: 'CRMS RE vs MES',
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
    doc.text(10, 10, "CRMS RECOMPRA POR MES" );
    doc.save('crms-recompra-por-mes.pdf');
 }

</script>








@endsection