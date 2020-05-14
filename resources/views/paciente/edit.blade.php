@extends('principal')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-4">
                <h4>Datos del Paciente:</h4>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('pacientes.index') }}" class="btn btn-primary">
                    <i class="fa fa-bars"></i><strong> Lista de Pacientes</strong>
                </a>
            </div>
        </div>
    </div>
    <form role="form" id="form-cliente" method="POST" action="{{ route('pacientes.update', ['paciente'=>$paciente]) }}" name="form">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="card-body">
            <div class="row">
                <div class="col-3 form-group">
                    <label class="control-label">✱Nombre:</label>
                    <input value="{{$paciente->nombre}}" type="text" name="nombre"  id="nombre"class="form-control" required="">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱Apellido Paterno:</label>
                    <input value="{{$paciente->paterno}}" type="text" name="paterno" id="paterno" class="form-control" required="">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱Apellido Materno:</label>
                    <input value="{{$paciente->materno}}" type="text" name="materno" id="materno" class="form-control" required="">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">Celular:</label>
                    <input value="{{$paciente->celular}}" type="number" name="celular" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-3 form-group">
                    <label class="control-label">Correo:</label>
                    <input value="{{$paciente->mail}}" type="email" name="mail" class="form-control">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱Fecha nacimiento:</label>
                    <input value="{{$paciente->nacimiento}}" type="date" name="nacimiento" class="form-control" required="" id="nacimiento">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱RFC:</label>
                    <input value="{{$paciente->rfc}}" type="text" name="rfc" class="form-control" required="">
                </div>
                <div class="form-group col-3">
                    <label for="nivel">Nivel:</label>
                    <select class="form-control" name="nivel_id" id="nivel">
                        @foreach($niveles as $nivel)
                            <option value="{{$nivel->id}}">{{$nivel->etiqueta}}/{{$nivel->nombre}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-3 form-group">
                    <label class="control-label">Teléfono:</label>
                    <input value="{{$paciente->telefono}}" type="text" name="telefono" class="form-control">
                </div>
                <div class="form-group col-3">
                    <label for="doctor_id">Doctor que recomienda:</label>
                    <select class="form-control" name="doctor_id" id="doctor_id" required>
                        @if(is_null($paciente->otro_doctor))
                            <option value="{{$paciente->doctor_id}}">{{$paciente->doctor->nombre}} {{$paciente->doctor->apellidopaterno}} {{$paciente->doctor->apellidomaterno}}</option disabled>
                        @endif
                        @if(!is_null($paciente->otro_doctor))
                            <option value="otro" selected>Otro..</option>
                        @else
                            <option value="otro">Otro..</option>
                        @endif
                        <option value="">Buscar..</option>
                        
                        
                    </select>
                </div>
                <div class="col-3 form-group" id="sech_doctor">
                    <label class="control-label">Buscar:</label>
                    <input type="text" name="sech_doctor" id="sech_doctor11" class="form-control">
                </div>
                <div class="col-3 form-group" id="otro_doctor">
                    <label class="control-label">Otro doctor nombre:</label>
                    <input type="text" name="otro_doctor" class="form-control" 
                    @if(!is_null($paciente->otro_doctor))
                        value="{{$paciente->otro_doctor}}"
                    @endif
                    >
                </div>
            </div>
            <div class="row">
                @include('paciente.subnav')
            </div>
        </div>
        {{-- Lista de doctores --}}
        <h6 class="text-center" id="tablaDocTitulo">LISTA PARA ASIGNAR DOCTOR</h6>
        <div class="row" id="tablaDoc">
            <div class="col-12 col-md-2"></div>
            <div class="col-12 col-md-8">
                    <div class="col-12">
                            <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                                    <thead>
                                        <tr class="info">
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Apellido paterno</th>
                                            <th>Apellido materno</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpTable">
                                    </tbody>
                                    
                                    </table>
                    </div>
            </div>
            <div class="col-12 col-md-2"></div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-4 offset-4 text-center">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Guardar
                    </button>
                </div>
                <div class="col-sm-4 text-right text-danger">
                    ✱Campos Requeridos.
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>    
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
@if(!is_null($paciente->otro_doctor))
$('#otro_doctor').show();
$('#sech_doctor').hide();
$('#doctor_id').attr('name', 'doctor_id_falsa');
@else
$('#otro_doctor').hide();
@endif
    $('#doctor_id').change(function () {
        if($(this).val() == 'otro'){
            $(this).attr('name', 'doctor_id_falsa');
            $('#otro_doctor').show();
            $('#otro_doctor').find('input').val('');
            $('#otro_doctor').find('input').attr('required', 'true');
        }else{
            $(this).attr('name', 'doctor_id');
            $('#otro_doctor').hide();
            $('#otro_doctor').find('input').val('');
            $('#otro_doctor').find('input').removeAttr('required');
        }
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /**$.ajax({
        url: "{{ url('/getDoctores') }}",
        type: "GET",
        dataType: "html",
    }).done(function (resultado) {
        $("#doctor_id").html(resultado);
    });**/
</script>
<script>
    $(document).ready(function() {
        //$('#listaEmpleados').DataTable();
        $('#sech_doctor11').change(function () {
            $.ajax({
                url: "/getDoctoresTable/",
                type: "GET",
                data: {"_token": $("meta[name='csrf-token']").attr("content"),
                               "nombre" : $('#sech_doctor11').val()
                        },
                dataType: "html",
            }).done(function (resultado) {
                $("#cuerpTable").html(resultado);
            });
        });
     
    });
</script>
<script>

$('#nacimiento').change( function(){
    
        var date = new Date( $('#nacimiento').val() );
        var dia = ("0" + date.getDate()).slice(-2);
        dia = parseInt(dia)+1;
        dia = dia.toString();
        const mes = ("0" + (date.getMonth() + 1)).slice(-2);
        const anio = date.getFullYear().toString().substr(-2);
        const rfc_paterno = $('#paterno').val().substr(0,2);
        const rfc_materno = $('#materno').val().substr(0,1);
        const rfc_nombre = $('#nombre').val().substr(0,1);
        var rfc_completo = rfc_paterno+rfc_materno+rfc_nombre+anio+mes+dia;
        rfc_completo = rfc_completo.toUpperCase();
        $('#rfc').val( rfc_completo );

    // alert($("#nacimiento").val());
} );
@if(!is_null($paciente->otro_doctor))
$('#otro_doctor').show();
$('#sech_doctor').hide();
$('#doctor_id').attr('name', 'doctor_id_falsa');
@else
$('#otro_doctor').hide();
@endif

    $('#doctor_id').change(function () {
        if($(this).val() == 'otro'){
            $(this).attr('name', 'doctor_id_falsa');
            $('#otro_doctor').show();
            
            $('#tablaDocTitulo').hide();
            $('#sech_doctor').hide();
            $('#tablaDoc').hide();
            $('#otro_doctor').find('input').val('');
            $('#otro_doctor').find('input').attr('required', 'true');
        }else{
            $(this).attr('name', 'doctor_id');
            $('#otro_doctor').hide();

            $('#tablaDocTitulo').show();
            $('#sech_doctor').show();
            $('#tablaDoc').show();
            $('#otro_doctor').find('input').val('');
            $('#otro_doctor').find('input').removeAttr('required');
        }
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /**$.ajax({
        url: "{{ url('/getDoctores') }}",
        type: "GET",
        dataType: "html",
    }).done(function (resultado) {
        $("#doctor_id").html(resultado);
    });**/



$(document).on('click', '.asignar', function(event) {
    const doctor_id = $(this).attr('id-doctor');
    const doctor_nombre = $(this).attr('nom');
    $('#doctor_id').append("<option value='"+doctor_id+"' >"+doctor_nombre+"</option>");
    $('#doctor_id').val(doctor_id);
    /* Act on the event */
});
</script>
@endsection