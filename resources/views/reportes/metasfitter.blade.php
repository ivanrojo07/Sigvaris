@extends('principal')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Ventas de fitter </h3>
        </div>
        {{-- Buscador de pacientes --}}
        <div class="card-body">
            <form action="{{route('reportes.metas')}}" method="POST" class="form-row">
                @csrf
                {{-- Input de fecha inicial --}}
                <div class="form-group col-md-2">
                    <label for="fechaInicial">Fecha inicial</label>
                    <input type="month" class="form-control" name="fechaInicial" id="fechaInicial"
                        placeholder="Ej. 2019-04" pattern="2[0-9]{3,3}-((0[1-9])|(1[012]))"
                        title="Escriba una fecha valida AAAA-MM" required>
                </div>
                {{-- Input fecha final --}}
                <div class="form-group col-md-2">
                    <label for="fechaFinal">Fecha final</label>
                    <input type="month" class="form-control" name="fechaFinal" id="fechaFinal" placeholder="Ej. 2019-04"
                        pattern="2[0-9]{3,3}-((0[1-9])|(1[012]))" title="Escriba una fecha valida AAAA-MM" required>
                </div>
                {{-- Input oficinaId --}}
                <div class="form-group col-md-3">
                    <label for="oficina_id">Oficina</label>
                    <select name="oficinaId" class="form-control" id="selectOficina">
                        @foreach ($oficinas as $oficina)
                        <option value="{{$oficina->id}}">{{$oficina->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                {{-- SELECT EMPLEADOS FITTERS --}}
                <div class="form-group col-md-3">
                    <label class="mx-3">fitter</label>
                    <select name="empleadoFitterId" class="form-control mr-4" id="selectEmpleadosFitter">
                        <option value="">Todos</option>
                        @foreach ($empleadosFitter as $empleadoFitter)
                        <option value="{{$empleadoFitter->id}}">
                            {{$empleadoFitter->nombre}} {{$empleadoFitter->appaterno}} {{$empleadoFitter->apmaterno}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <button class="btn btn-primary my-4">Buscar</button>
                </div>
            </form>
             <form action="{{route('reportes.11.export')}}"   method="POST" class="form-inline">
                 @csrf
                 <input type="hidden" name="fecha_ini" value="{{ $fecha_ini  }}">
                  <input type="hidden" name="fecha_fin" value="{{$fecha_fin}}">
                  <input type="hidden" name="fitter_" value="{{ $fitter}}">
                  <input type="hidden" name="ventasMes" value="{{ json_encode($datosVentasMes)}}">
                <button class="btn btn-primary">Exportar</button>
                 </form>
        </div>
        @if ( isset($fitter) )
        <div class="input-group col-md-5 my-4">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon3">Nombre de la fitter</span>
            </div>
            <input type="text" class="form-control"
                @if(isset($fitter))value="{{ $fitter->nombre ." ".$fitter->appaterno ." ".$fitter->apmaterno}}" @else
                value="--" @endif readonly="">
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                <thead>
                    <tr class="info text-center">
                        <th>Mes</th>
                        <th colspan="3">Monto de venta</th>
                        <th colspan="3">Pacientes > 1 prenda</th>
                        <th colspan="3">Recompras</th>
                    </tr>
                    <tr class="info text-center">
                        <th></th>
                        <th>Objetivo</th>
                        <th>Real</th>
                        <th>%</th>
                        <th>Objetivo</th>
                        <th>Real</th>
                        <th>%</th>
                        <th>Objetivo</th>
                        <th>Real</th>
                        <th>%</th>
                        <th>Piezas promedio por paciente</th>
                         <th>Piezas promedio mensual</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($datosVentasMes["montoVenta"]))
                    @foreach($datosVentasMes["montoVenta"] as $key => $row)
                    <tr class="text-center">
                    <!--     {{dd($datosVentasMes["montoVenta"])}} -->
                        <td>{{ $key + 1 }}</td>
                        <td>${{ number_format($row["meta"], 2) }}</td>
                        <td>${{ number_format($row["valor"], 2) }}</td>
                        <td>{{ number_format($row["porcentaje"], 2) }}%</td>
                        <td>{{ $datosVentasMes["pacientes"][$key]["meta"] }}</td>
                        <td>{{ $datosVentasMes["pacientes"][$key]["valor"] }}</td>
                        @if($datosVentasMes["pacientes"][$key]["porcentaje"] != "-")
                        <td>{{ $datosVentasMes["pacientes"][$key]["porcentaje"] }}%</td>
                        @else
                        <td>{{ $datosVentasMes["pacientes"][$key]["porcentaje"] }}</td>
                        @endif
                        <td>{{ $datosVentasMes["recompras"][$key]["meta"] }}</td>
                        <td>{{ $datosVentasMes["recompras"][$key]["valor"] }}</td>
                        @if($datosVentasMes["recompras"][$key]["porcentaje"] != "-")
                        <td>{{ $datosVentasMes["recompras"][$key]["porcentaje"] }}%</td>
                        @else
                        <td>{{ $datosVentasMes["recompras"][$key]["porcentaje"] }}</td>
                        @endif
                    </tr>
                    @endforeach
                    <tr class="text-center">
                        <td>TOTAL</td>
                        <td>${{ number_format($row["meta"], 2) }}</td>
                        <td>${{ number_format($datosVentasMes["totales"]["montoVenta"]["valor"], 2) }}</td>
                        <td>{{ number_format($datosVentasMes["totales"]["montoVenta"]["porcentaje"]) }}%</td>
                        <td>{{ $datosVentasMes["pacientes"][$key]["meta"] }}</td>
                        <td>{{ $datosVentasMes["totales"]["pacientes"]["valor"] }}</td>
                        <td>{{ number_format($datosVentasMes["totales"]["pacientes"]["porcentaje"]) }}%</td>
                        <td>{{ $datosVentasMes["recompras"][$key]["meta"] }}</td>
                        <td>{{ $datosVentasMes["totales"]["recompras"]["valor"] }}</td>
                        <td>{{ number_format($datosVentasMes["totales"]["recompras"]["porcentaje"]) }}%</td>
                    </tr>
                    @else
                    @for ($i = 0; $i < count($datosVentasMes) - 1; $i++) <tr class="text-center">
                        <td>{{ $datosVentasMes[$i]["mes"] }}</td>
                        <td>${{ number_format($datosVentasMes[$i]["metas"]["montoVenta"], 2) }}</td>
                        <td>${{ number_format($datosVentasMes[$i][0]["montoVenta"]["valor"], 2) }}</td>
                        <td>{{ number_format($datosVentasMes[$i][0]["montoVenta"]["porcentaje"], 2) }}%</td>
                        <!-- Pacientes-->
                        <td>{{ $datosVentasMes[$i]["metas"]["pacientes"] }}</td>
                        <td>{{ $datosVentasMes[$i][0]["pacientes"]["valor"] }}</td>
                        @if($datosVentasMes[$i][0]["pacientes"]["porcentaje"] != "-")
                        <td>{{ $datosVentasMes[$i][0]["pacientes"]["porcentaje"] }}%</td>
                        @else
                        <td>{{ $datosVentasMes[$i][0]["pacientes"]["porcentaje"] }}</td>
                        @endif
                        <!-- Recompras-->
                        <td>{{ $datosVentasMes[$i]["metas"]["recompras"] }}</td>
                        <td>{{ $datosVentasMes[$i][0]["recompras"]["valor"] }}</td>
                        @if($datosVentasMes[$i][0]["recompras"]["porcentaje"] != "-")
                        <td>{{ $datosVentasMes[$i][0]["recompras"]["porcentaje"] }}%</td>
                        @else
                        <td>{{ $datosVentasMes[$i][0]["recompras"]["porcentaje"] }}</td>
                        @endif
                        <td>0</td>
                         <td>0</td>
                        </tr>
                        @endfor
                        <tr class="text-center">
                            <td>TOTAL</td>
                            <td>${{ number_format($datosVentasMes["totales"]["montoVenta"][0], 2) }}</td>
                            <td>${{ number_format($datosVentasMes["totales"]["montoVenta"][1], 2) }}</td>
                            <td>{{ number_format($datosVentasMes["totales"]["montoVenta"][2]) }}%</td>
                            <td>{{ $datosVentasMes["totales"]["pacientes"][0] }}</td>
                            <td>{{ $datosVentasMes["totales"]["pacientes"][1] }}</td>
                            <td>{{ number_format($datosVentasMes["totales"]["pacientes"][2]) }}%</td>
                            <td>{{ $datosVentasMes["totales"]["recompras"][0] }}</td>
                            <td>{{ $datosVentasMes["totales"]["recompras"][1] }}</td>
                            <td>{{ number_format($datosVentasMes["totales"]["recompras"][2]) }}%</td>
                             <td>0</td>
                              <td>0</td>
                        </tr>
                        @endif
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<script src="{{ URL::asset('js/handleFitters.js') }}"></script>
<script>
    $(document).on('change', '#selectOficina', function(){
        const OFICINA_ID = $(this).val();
        actualizarOpcionesFitters(OFICINA_ID);
    });
</script>

@endsection