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
    <form role="form" name="Form" onsubmit="return validateForm()" id="form-cliente" method="POST" action="{{ route('pacientes.store') }}" name="form">
        {{ csrf_field() }}
        <div class="card-body">
            @if (session('error'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger">
                            {{session('error')}}
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-3 form-group">
                    <label class="control-label">✱Nombre:</label>
                    <input type="text" name="nombre" class="form-control" required="" id="nombre">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱Apellido Paterno:</label>
                    <input type="text" name="paterno" class="form-control" required="" id="paterno">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱Apellido Materno:</label>
                    <input type="text" name="materno" class="form-control" required="" id="materno">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">Celular:</label>
                    <input type="number" name="celular" class="form-control">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">Correo:</label>
                    <input type="email" name="mail" class="form-control">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">Fecha nacimiento:</label>
                    <input type="date" name="nacimiento" class="form-control" required id="nacimiento">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱ RFC:</label>
                    <input type="text" name="rfc" class="form-control" required id="rfc">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">Homoclave:</label>
                    <input type="text" name="homoclave" class="form-control">
                </div>
                <div class="form-group col-3">
                    <label for="nivel_id">Nivel:</label>
                    <select class="form-control" name="nivel_id" id="nivel_id">
                        @foreach($niveles as $nivel)
                        <option value="{{$nivel->id}}">{{$nivel->etiqueta}}/{{$nivel->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-3 form-group">
                    <label class="control-label">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
                <div class="form-group col-3">
                    <label for="doctor_id">Doctor que recomienda:</label>
                    <select class="form-control" name="doctor_id" id="doctor_id" required>
                        <option value="">Buscar..</option>
                        <option value="otro">Otro..</option>
                        
                    </select>
                </div>
                <div class="col-3 form-group" id="sech_doctor">
                    <label class="control-label">Buscar:</label>
                    <input type="text" name="sech_doctor" id="sech_doctor11" class="form-control">
                </div>
                <div class="col-3 form-group" id="otro_doctor">
                    <label class="control-label">Otro doctor nombre:</label>
                    <input type="text" name="otro_doctor" class="form-control">
                </div>
            </div>
             </div>
                   <div class="col-3 form-group">
                   
                    <input type="hidden" name="empleado_id" class="form-control" value="{{Auth::user()->id}}">
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
{{-- <script type="text/javascript">
    function validateForm(){
        var a=document.forms["Form"]["nacimiento"].value;
        var b=document.forms["Form"]["rfc"].value;
        if((a==null & b==null) || (a=="" && b=="") || (a=="" && b==null) || (a==null && b=="") )
        {
            alert("Favor de llenar fecha de nacimiento o rfc");
            return false;
        }
        //alert("hola mundo");
    } 

</script> --}}

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>    
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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

$('input').change( function(){
    
    if( $('#nacimiento').val() ){
        var date = new Date( $('#nacimiento').val() );
        var dia = ("0" + date.getDate()).slice(-2);
        dia = parseInt(dia)+1;
        dia = dia.toString();
        if(parseInt(dia)<10){
            dia = ("0"+ dia.toString());
            console.log('Entre en el if');
        }

        const mes = ("0" + (date.getMonth() + 1)).slice(-2);
        const anio = date.getFullYear().toString().substr(-2);
        console.log(anio);
        console.log(mes);
        console.log(dia);
        const rfc_paterno = $('#paterno').val().substr(0,2);
        const rfc_materno = $('#materno').val().substr(0,1);
        const rfc_nombre = $('#nombre').val().substr(0,1);
        var rfc_completo = rfc_paterno+rfc_materno+rfc_nombre+anio+mes+dia;
        rfc_completo = rfc_completo.toUpperCase();
        $('#rfc').val( rfc_completo );

    }

    // alert($("#nacimiento").val());
} );

$('#otro_doctor').hide();
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