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
                                    <td>{{

                                        $paciente->nombre . " " . $paciente->paterno . " " . $paciente->materno}}</td>
                                    <td>
                                        {{  

                                            collect($paciente->ventas)->pluck('productos')->flatten()->pluck('pivot')->flatten()->pluck('cantidad')->sum()
                                        }}</td>
                                    <td>
                                        {{ round(collect($paciente
                                                                                    ->ventas)
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
                    <div class="col-3">
                        <label for="totalCompras" class="text-uppercase"><strong>total pacientes</strong></label>
                        <input type="text" readonly value="{{$pacientesConCompra ? $pacientesConCompra->count() : 0}}" class="form-control">
                    </div>
                    <div class="col-3">
                        <label for="totalCompras" class="text-uppercase"><strong>total prendas</strong></label>
                        <input type="text" readonly value="{{$totalProductosCompras}}" class="form-control">
                    </div>
                </div>
            </div>

 @endif