@extends('principal')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-4">
                <h5>Mostrar Paciente</h5>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('pacientes.index') }}" class="btn btn-primary">
                    <i class="fa fa-bars"></i><strong> Lista de Pacientes</strong>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{route('pacientes.edit', ['paciente'=>$paciente])}}" class="btn btn-warning">
                    <i class="fas fa-edit"></i><strong> Editar Paciente</strong>
                </a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3 form-group">
                <label class="control-label">✱Nombre:</label>
                <input readonly value="{{$paciente->nombre}}" type="text" name="nombre" class="form-control" required="">
            </div>
            <div class="col-3 form-group">
                <label class="control-label">✱Apellido Paterno:</label>
                <input readonly value="{{$paciente->paterno}}" type="text" name="paterno" class="form-control" required="">
            </div>
            <div class="col-3 form-group">
                <label class="control-label">✱Apellido Materno:</label>
                <input readonly value="{{$paciente->materno}}" type="text" name="materno" class="form-control" required="">
            </div>
            <div class="col-3 form-group">
                <label class="control-label">✱Celular:</label>
                <input readonly value="{{$paciente->celular}}" type="number" name="celular" class="form-control" required="">
            </div>
        </div>
        <div class="row">
            <div class="col-3 form-group">
                <label class="control-label">✱Correo:</label>
                <input readonly value="{{$paciente->mail}}" type="email" name="mail" class="form-control" required="">
            </div>
            <div class="col-3 form-group">
                <label class="control-label">✱Fecha nacimiento:</label>
                <input readonly value="{{$paciente->nacimiento}}" type="date" name="nacimiento" class="form-control" required="">
            </div>
            <div class="col-3 form-group">
                <label class="control-label">✱RFC:</label>
                <input readonly value="{{$paciente->rfc}}" type="text" name="rfc" class="form-control" required="">
            </div>
            <div class="form-group col-3">
                <label for="nivel">Nivel:</label>
                @if ($paciente->nivel)
                     <input readonly value="{{$paciente->nivel->etiqueta}}/{{$paciente->nivel->nombre}}" type="text" name="nivel" class="form-control" required="">
                @else
                    <input readonly value="" type="text" name="nivel" class="form-control">
                @endif
               
            </div>
        </div>
        <div class="row">
            <div class="col-3 form-group">
                <label class="control-label">✱Teléfono:</label>
                <input readonly value="{{$paciente->telefono}}" type="text" name="telefono" class="form-control" required="">
            </div>
            @if(!empty($paciente->doctor))
            <div class="form-group col-3">
                <label for="doctor_id">Doctor que recomienda:</label>
                <input readonly value="{{$paciente->doctor->nombre}} {{$paciente->doctor->paterno}}" type="text" name="nivel" class="form-control" required="">
            </div>
            @else
            <div class="form-group col-3">
                <label for="doctor_id">Doctor que recomienda:</label>
                <input readonly value="{{$paciente->otro_doctor}}" type="text" name="nivel" class="form-control" required="">
            </div>
            @endif
            @if ($paciente->oficina_id)
                <div class="form-group col-3">
                    <label for="oficina_id">Oficina donde se registro:</label>
                    <input readonly value="{{$paciente->oficina->nombre}}" type="text" name="oficina" class="form-control" required="">
                </div>
            @endif
            {{-- <div class="form-group col-3">
                <label for="oficina_id">Oficina donde se registro:</label>
                <input readonly value="{{$paciente->oficina->nombre}}" type="text" name="oficina" class="form-control" required="">
            </div> --}}
        </div>
        <div class="row">
            @include('paciente.subnav')
        </div>
        @yield('submodulos')
    </div>
    <script>
        $(document).ready(function(){
            let submenu = $('#submenu').val();
            $('.nav-link.submenu').removeClass('active')
            $('#'+submenu).addClass('active')
        });
    </script>
    <script>
        $(function(){
      $('#mi-tabla').tablesorter(); 
    });
    </script>
</div>
@endsection