@extends('principal')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-4">
                <h4>Datos del Doctor:</h4>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('doctores.index') }}" class="btn btn-primary">
                    <i class="fa fa-bars"></i><strong> Lista de Doctores</strong>
                </a>
            </div>
        </div>
    </div>
    <form role="form" id="form-cliente" method="POST" action="{{ route('doctores.store') }}" name="form">
        {{ csrf_field() }}
        <div class="card-body">
            <div class="row">
                <div class="col-3 form-group">
                    <label class="control-label">✱Nombre:</label>
                    <input type="text" name="nombre" class="form-control" required="">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱Apellido Paterno:</label>
                    <input type="text" name="apellidopaterno" class="form-control" required="">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">Apellido Materno:</label>
                    <input type="text" name="apellidomaterno" class="form-control">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">Celular:</label>
                    <input type="number" name="celular" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-3 form-group">
                    <label class="control-label">Correo:</label>
                    <input type="email" name="mail" class="form-control">
                </div>
                <div class="col-3 form-group">
                    <label class="control-label">Fecha nacimiento:</label>
                    <input type="date" name="nacimiento" class="form-control">
                </div>
                   <div class="col-3 form-group">
                   
                    <input type="hidden" name="empleado_id" class="form-control" value="{{Auth::user()->id}}">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-4 offset-4 text-center">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Guardar
                    </button>
                </div>
                <div class="col-4 text-right text-danger">
                    ✱Campos Requeridos.
                </div>
            </div>
        </div>
    </form>
</div>

@endsection