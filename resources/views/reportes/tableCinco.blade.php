 <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="tabla">
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
</table>