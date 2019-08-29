@extends('principal')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-4">
                <h4>Baja de {{$empleado->nombre}}</h4>
            </div>
            
        </div>
    </div>
    <form role="form" id="form-cliente" method="POST" action="{{ url('empleados/'.$empleado->id.'/EmpledoBaja/store') }}" name="form">
        {{ csrf_field() }}
        <div class="card-body">
            <div class="row">
                 <div class="col-3 form-group">
                <label class="control-label">Fecha de baja :</label>
                <input value="{{date("Y-m-d")}}" readonly type="date" class="form-control">
            </div>
                <div class="col-3 form-group">
                    <label class="control-label">✱Motivo Baja:</label>
                    <input type="text" name="motivo" class="form-control" required="">
                </div>
                
            </div>                           
                <div class="col-3 form-group">
                    <label class="control-label">✱Comentario:</label>
                    <input type="text" name="comentario" class="form-control" required="">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-4 offset-4 text-center">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Dar de baja
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