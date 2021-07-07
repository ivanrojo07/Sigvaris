 @if ( isset($anioInicial) )
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
@endif