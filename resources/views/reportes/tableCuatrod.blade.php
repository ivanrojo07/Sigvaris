 @if ( isset($anioInicial) )
                    @if(!isset($request->anio_ini))
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
                    @else
                    <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                        <thead>
                            <tr class="info text-center">
                                <th>mes</th>
                                @for ($anio = $request->anio_ini; $anio <= $request->anio_fin; $anio++)
                                <th>{{$anio}}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($meses as $mesNumerico => $mesTextual)
                                <tr>
                                    <td>{{$mesTextual}}</td>
                                    @for ($anio = $request->anio_ini; $anio <= $request->anio_fin; $anio++)
                                    <td>{{App\Venta::whereYear('fecha',$anio)->whereMonth('fecha',$mesNumerico)->get()->pluck('productos')->flatten()->pluck('pivot')->flatten()->pluck('cantidad')->flatten()->sum()}}</td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>    
                    </table>          
                    @endif       
@endif