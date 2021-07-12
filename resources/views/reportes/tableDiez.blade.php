<table class="table">
                    <thead>
                        <tr class="info">
                            <th rowspan="2">Doctor</th>

                            @foreach ($mesesSolicitados as $mes)
                                <th colspan="2">{{$mesesString[$mes]}}</th>
                            @endforeach

                            <th colspan="2">Total</th>
                        </tr>
                        <tr>
                            @foreach ($mesesSolicitados as $mes)
                                <th>1° vez</th>
                                <th>Recompra</th>
                            @endforeach
                            <th>1° vez</th>
                            <th>Recompra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctores as $key => $doctor)
                        {{-- {{dd($doctores)}} --}}
                            <tr>
                                <td>{{$doctor->nombre}}</td>
                                @foreach ($mesesSolicitados as $mes)
                                    <th>
                                        {{-- {{dd($mesesSolicitados)}} --}}
                                        {{
                                            $doctor
                                                ->pacientes()
                                                ->withCount("ventas")
                                                // SOLO PACIENTES CON VENTAS EN EL RANGO DE TIEMPO
                                                ->whereHas('ventas', function(\Illuminate\Database\Eloquent\Builder $query) use($mes){
                                                    $query->where('fecha','>=',$año_ini.'-'.$mes.'-01')
                                                        ->where('fecha', '<=', $año_fin.'-'.$mes.'-31');
                                                })
                                                // SOLO PACIENTES CON MENOS DE UNA VENTA
                                                ->having('ventas_count', '<=', 1)
                                                ->get()
                                                ->count()
                                        }}
                                    </th>
                                    <th>{{
                                        $doctor
                                                ->pacientes()
                                                ->withCount("ventas")
                                                // SOLO PACIENTES CON VENTAS EN EL RANGO DE TIEMPO
                                                ->whereHas('ventas', function(\Illuminate\Database\Eloquent\Builder $query) use($mes){
                                                    $query->where('fecha','>=',$año_ini.'-'.$mes.'-01')
                                                        ->where('fecha', '<=', $año_fin.'-'.$mes.'-31');
                                                })
                                                // SOLO PACIENTES CON MENOS DE UNA VENTA
                                                ->having('ventas_count', '>', 1)
                                                ->get()
                                                ->count()
                                        }}</th>
                                @endforeach
                                <td>
                                    {{
                                        $doctor
                                            ->pacientes()
                                            ->withCount("ventas")
                                            // SOLO PACIENTES CON VENTAS EN EL RANGO DE TIEMPO
                                            ->whereHas('ventas', function(\Illuminate\Database\Eloquent\Builder $query) use($mesesSolicitados){
                                                $query->where('fecha','>=',$año_ini.'-'.$mesesSolicitados[0].'-01')
                                                    ->where('fecha', '<=', $año_fin.'-'.end($mesesSolicitados).'-31');
                                            })
                                            // SOLO PACIENTES CON MENOS DE UNA VENTA
                                            ->having('ventas_count', '<=', 1)
                                            ->get()
                                            ->count()
                                    }}
                                </td>
                                <td>{{
                                        $doctor
                                            ->pacientes()
                                            ->withCount("ventas")
                                            // SOLO PACIENTES CON VENTAS EN EL RANGO DE TIEMPO
                                            ->whereHas('ventas', function(\Illuminate\Database\Eloquent\Builder $query) use($mesesSolicitados){
                                                $query->where('fecha','>=',$año_ini.'-'.$mesesSolicitados[0].'-01')
                                                    ->where('fecha', '<=', $año_fin.'-'.end($mesesSolicitados).'-31');
                                            })
                                            // SOLO PACIENTES CON MENOS DE UNA VENTA
                                            ->having('ventas_count', '>', 1)
                                            ->get()
                                            ->count()
                                    }}</td>
                            </tr>
                        @endforeach
                    </tbody>    
                </table>