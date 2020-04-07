@extends('principal')
@section('content')

    <div class="container">
        <div class="card">
            {{-- TITULO DEL REPORTE --}}
            <div class="card-header">
                <h3>Reporte de prendas compradas</h3>
            </div>
            {{-- BUSCADOR PACIENTES --}}
            <div class="card-body">
                <form action="{{route('reportes.4a')}}" method="POST" class="form-inline">
                    @csrf
                    {{-- Input de fecha inicial --}}
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                            <label for="fechaInicial"></label>
                            <input type="date" class="form-control" name="fechaInicial" id="fechaInicial" required>        
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                            {{-- Input fecha final --}}
                            <label for="fechaFinal"></label>
                            <input type="date" class="form-control" name="fechaFinal" id="fechaFinal" required>
                        </div>
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
                        {{-- INPUT FITTER --}}
                        <div class="col-12 col-sm-6 col-md-4 mt-2">
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
                        {{-- BOTON ENVIAR FORMULARIO --}}
                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                            <button class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                    
                </form>
            </div>
            @if ( isset($pacientesConCompra) )
            {{-- TABLA PACIENTES --}}
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                    <thead>
                        <tr class="info">
                            <th>Paciente</th>
                            <th># prendas</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pacientesConCompra as $paciente)
                                <tr>
                                    <td>{{$paciente->nombre . " " . $paciente->paterno . " " . $paciente->materno}}</td>
                                    <td>
                                        {{
                                            $paciente->ventas->pluck('productos')->flatten()->pluck('pivot')->flatten()->pluck('cantidad')->sum()
                                        }}</td>
                                    <td>
                                        {{ round($paciente
                                            ->ventas
                                            ->pluck('productos')
                                            ->flatten()
                                            ->pluck('pivot')
                                            ->flatten()
                                            ->pluck('cantidad')->sum() / $totalProductosCompras * 100, 2) }}%
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>    
                </table>
                <div class="row mt-3">
                    <div class="col-3"></div>
                    <div class="col-3"></div>
                    <div class="col-3"></div>
                    <div class="col-3">
                        <label for="totalCompras" class="text-uppercase"><strong>Compras totales</strong></label>
                        <input type="text" readonly value="{{$totalProductosCompras}}" class="form-control">
                    </div>
                </div>
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