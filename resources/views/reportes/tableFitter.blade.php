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
                        <th>Cuota</th>
                        <th>Real</th>
                        <th>%</th>
                        <th>Cuota</th>
                        <th>Real</th>
                        <th>%</th>
                        <th>Cuota</th>
                        <th>Real</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
              {{dd($datosVentasMes)}}
                    @foreach($datosVentasMes as $key => $row)
                    <tr class="text-center">
                        <td>{{ intval($key) + 1 }}</td>
                           
                            
                         @if($row == 'totales' )
                        <td>${{ $row }}</td>
                        @endif
                        <td>${{ number_format($row->{'meta'}->{'montoVenta'} , 2) }}</td>
                        <td>${{ number_format($row->{0}->{'montoVenta'}->{'valor'}, 2) }}</td>
                        <td>{{ number_format($row->{0}->{'montoVenta'}->{'porcentaje'}, 2) }}%</td>

                        <td>{{ number_format($row->{'meta'}->{'pacientes'} , 2)  }}</td>
                        <td>{{ $row->{0}->{'pacientes'}->{'valor'} }}</td>
                        @if($row->{0}->{'pacientes'}->{'porcentaje'} != "-")
                        <td>{{ $row->{0}->{'pacientes'}->{'porcentaje'}  }}%</td>
                        @else
                        <td>{{ $row->{0}->{'pacientes'}->{'porcentaje'}  }}</td>
                        @endif
                        <td>{{ $row->{'metas'}->{'recompras'} }}</td>
                        <td>{{ $row->{0}->{'recompras'}->{'valor'} }}</td>
                        @if($row->{0}->{'recompras'}->{'porcentaje'}  != "-")
                        <td>{{ $row->{0}->{'recompras'}->{'porcentaje'} }}%</td>
                        @else
                        <td>{{ $row->{0}->{'recompras'}->{'porcentaje'} }}</td>
                        @endif

                    </tr>
                    @endforeach
                      {{dd($datosVentasMes->{'meta'}->{'montoVenta'})}}
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
                    
                   
                      
                </tbody>
            </table>