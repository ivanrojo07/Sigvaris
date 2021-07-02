 <table>
                <thead>
                    <tr class="info">
                        <th>Fecha</th>
                        {{-- <th>Doctor</th> --}}
                        <th>Nombre</th>
                        <th>Apellido paterno</th>
                        <th>Apellido materno</th>
                        <th>N° prendas</th>
                        {{-- <th>Acción</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)
                    <tr>
                        <td>{{$venta->fecha}}</td>
                        <td>{{$venta->paciente ? $venta->paciente->nombre : ''}}</td>
                        <td>{{$venta->paciente ? $venta->paciente->paterno : ''}}</td>
                        <td>{{$venta->paciente ? $venta->paciente->materno : ''}}</td>
                        <td>{{$venta->cantidad_productos}}</td>
                        {{-- <td>{{App\Paciente::find($paciente_id)->doctor()->first()->nombre}}</td> --}}
                        {{-- <td>{{App\Paciente::find($paciente_id) ? App\Paciente::find($paciente_id)->nombre : ''}}</td>
                        <td>{{App\Paciente::find($paciente_id) ? App\Paciente::find($paciente_id)->paterno : ''}}</td>
                        <td>{{App\Paciente::find($paciente_id) ? App\Paciente::find($paciente_id)->materno : ''}}</td>
                        <td>{{
                                            $ventas->pluck('productos')
                                                ->flatten()
                                                ->pluck('pivot')
                                                ->flatten()
                                                ->pluck('cantidad')
                                                ->sum()
                                            }}</td> --}}
                    </tr>
                    @endforeach
                </tbody>
 </table>