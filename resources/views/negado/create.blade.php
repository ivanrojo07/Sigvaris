@extends('principal')
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}"> 
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <h4>Crear Producto Negado</h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form role="form" id="form-cliente" method="POST" action="{{route('negado.create')}}" name="form">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 form-group">
                            <label id="No_repetido" class="control-label">✱SKU:</label>
                            <input type="text" id="producto_id" name="producto_id" class="form-control" required="">
                        </div>
                        <div class="col-3 form-group">
                            <label id="No_repetido" class="control-label">✱Paciente:</label>
                            <input type="text" id="paciente_id" name="paciente_id" class="form-control" required="">
                        </div>
                        <div class="col-3 form-group">
                            <label for="actual">✱Fecha actual</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{date('Y-m-d')}}"
                                readonly="" required="">
                        </div>
                        <div class="col-3 form-group">
                            <label for="actual">Fecha posible entrega:</label>
                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" value=""
                                >
                        </div>
                        <div class="col-3 form-group">
                            <label class="control-label">Producto que se entrego en lugar del negado:</label>
                            <input type="text" name="producto2" class="form-control"  id="precio">
                        </div>
                        <div class="col-3 form-group">
                            <label class="control-label"><br>Comentarios :</label>
                            <input type="text" name="comentarios" class="form-control" step="1"  id="comentarios">
                        </div>
                        
                        <input type="hidden" name="oficina_id" value="{{session('oficina')}}">
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
    </div>
</div>

@endsection