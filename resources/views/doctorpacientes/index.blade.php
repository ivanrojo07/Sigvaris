@extends('doctor.show')
@section('submodulos')
    <form role="form" id="form-cliente" method="GET" action="{{url('doctores.pacientes', ['doctor' => $doctor] )}}" name="form">
        {{ csrf_field() }}
        <label for="actual">Identificador de doctor que adquiere los pacientes</label>
        <input type="text" class="form-control" id="id" name="id"  readonly="" required="">
        <div class="col-4 offset-4 text-center">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i> Reasignar
            </button>
        </div>
    </form>
    <div class="row my-5">
        <div class="col-4 px-5"><h4>Pacientes</h4></div>
        <input id="submenu" type="hidden" name="submenu" value="nav-pacientes">
    </div>
    <div class="row">
         <div class="col-12 form-group">
        <table class="table table-striped table-bordered table-hover" id="tablePacientes" style="color:rgb(51,51,51); border-collapse: collapse; margin-bottom: 0px;">
            <thead>
                <tr class="info">
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                </tr>
            </thead>
            <tbody>                          
            @foreach ($doctor->pacientes as $paciente)
                <tr>
                    <td>{{$paciente->nombre}}</td>
                    <td>{{$paciente->paterno}}</td>
                    <td>{{$paciente->materno}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tablePacientes').DataTable({
                'language':{
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Productos _START_ al _END_ de un total de _TOTAL_ ",
                    "sInfoEmpty":      "Productos 0 de un total de 0 ",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",

                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
    });
     
</script>
@endsection