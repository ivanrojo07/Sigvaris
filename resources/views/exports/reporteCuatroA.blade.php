<table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                    <thead>
                        <tr class="info">
                            <th>Paciente</th>
                            <th># prendas</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pacientesConCompra as  $paciente)
                                <tr>
                                    <td>{{$paciente->nombre . " " . $paciente->paterno . " " . $paciente->materno}}</td>
                                    <td>
                                    </td>
                                    <td>
                                       
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>    
</table>