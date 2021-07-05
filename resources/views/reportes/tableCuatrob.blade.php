@if ( isset($skusConVentas)  )
        {{-- TABLA --}}
        <div class="card-body">
            <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                <thead>
                    <tr class="info">
                        <th>SKU</th>
                        <th>NUM. PACIENTES</th>
                        <th>NUM. PRENDAS</th>
                        <th>% DE VENTA</th>
                    </tr>
                </thead>
                <tbody>
                   
                    @foreach(json_decode($skusConVentas) as $key => $sku)
                    <tr>
                        <td>{{$key}}</td>
                        <td>
                            {{
                                    collect($sku)->pluck('ventas')
                                        ->flatten()
                                        ->pluck('paciente_id')
                                        ->flatten()
                                        ->unique()
                                        ->count()    
                                }}
                        </td>
                        <td>{{
                                collect($sku)->pluck('ventas')
                                    ->flatten()
                                    ->pluck('pivot')
                                    ->flatten()
                                    ->pluck('cantidad')
                                    ->sum()
                                }}
                        </td>
                        <td>
                            {{
                                    round(collect($sku)->pluck('ventas')
                                    ->flatten()
                                    ->pluck('pivot')
                                    ->flatten()
                                    ->pluck('cantidad')
                                    ->sum()/$totalPrendasVendidas*100,2)    
                                }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                
 </table>

 @endif

                  