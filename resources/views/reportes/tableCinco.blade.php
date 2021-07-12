 <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="tabla">
                <thead>
                    <tr class="info">
                        <th rowspan="2" >mes</th>
                        @foreach ($anios as $anio)
                        <th colspan="2">{{$anio}}</th>

                        @endforeach     
                    </tr>
                    <tr>
                        @foreach ($anios as $anio)
                        <th>1ra vez</th>
                         <th>recompra</th>

                        @endforeach 
                    </tr>

                </thead>
                <tbody>

                    @foreach ($meses as $mes)
                    <tr>
                        <td>{{$mes}}</td>
                        @foreach($anios as $key => $anio)
                        
                      
                        <td rowspan="1">
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
                      
                        <td rowspan="1">  {{
                                App\Paciente::whereYear('created_at',$anio)
                                ->whereMonth('created_at',$mes)
                                ->has('ventas')
                                ->get()
                                ->filter( function($paciente){
                                    return $paciente->ventas->count() > 1;
                                } )->count()
                            }}
                        </td>
                      
                      

                        @endforeach
                    </tr>
                    @endforeach



                </tbody>
</table>