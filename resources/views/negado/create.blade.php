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
            <form role="form" id="form-cliente" method="POST" action="{{-- route('productos.store') --}}" name="form">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 form-group">
                            <label id="No_repetido" class="control-label">✱SKU:</label>
                            <input type="text" id="sku" name="sku" class="form-control" required="">
                        </div>
                        <div class="col-3 form-group">
                            <label id="No_repetido" class="control-label">✱Paciente:</label>
                            <input type="text" id="Paciente" name="Paciente" class="form-control" required="">
                        </div>
                        <div class="col-3 form-group">
                            <label for="actual">✱Fecha actual</label>
                            <input type="date" class="form-control" value="{{date('Y-m-d')}}"
                                readonly="">
                        </div>
                        <div class="col-3 form-group">
                            <label for="actual">Fecha posible entrega:</label>
                            <input type="date" class="form-control" value=""
                                readonly="">
                        </div>
                        <div class="col-3 form-group">
                            <label class="control-label">Producto que se entrego en lugar del negado:</label>
                            <input type="text" name="id_producto" class="form-control" required="" id="precio">
                        </div>
                        <div class="col-3 form-group">
                            <label class="control-label">Comentarios :</label>
                            <input type="number" name="stock" class="form-control" step="1" required="" id="stock">
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