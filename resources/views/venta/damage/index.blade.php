@extends('principal')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <h4>Venta</h4>
                </div>
                <div class="col-4 text-center">
                    <a href="{{ route('ventas.index') }}" class="btn btn-primary">
                        <i class="fa fa-bars"></i><strong> Lista de Ventas</strong>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card-body">
                <div class="row">
                    <div class="col-3 form-group">
                        <label class="control-label">Fecha:</label>
                        <input type="text" class="form-control" value="{{$venta->fecha}}" readonly="">
                    </div>
                    <div class="col-3 form-group">
                        <label class="control-label">Cliente:</label>
                        <input type="text" class="form-control" value="{{$venta->paciente->fullname}}" readonly="">
                    </div>
                    <div class="col-3 form-group">
                        <label class="control-label">Folio:</label>
                        <input type="number" class="form-control" value="{{$venta->id}}" readonly="">
                    </div>
                    @if ($venta->oficina_id)
                    <div class="col-3 form-group">
                        <label class="control-label">Tienda:</label>
                        <input type="text" class="form-control" value="{{$venta->oficina->nombre}}" readonly="">
                    </div>
                    @endif
                    {{-- <div class="col-4 form-group">
                        <label class="control-label">Oficina:</label>
                        <input type="text" class="form-control" value="{{$venta->oficina->nombre}}" readonly="">
                </div> --}}
            </div>
            <div class="row">
                <div class="col-12">
                    <h5>Productos</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>sku</th>
                                <th>Nombre</th>
                                <th>Precio Individual</th>
                                <th>Cantidad</th>
                                <th>Precio total</th>
                                <th>Quitar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->productos as $producto)
                            <tr>
                                <td>{{$producto->sku}}</td>
                                <td>{{$producto->descripcion}}</td>
                                <td>{{$producto->precio_publico_iva}}</td>
                                <td>{{$producto->pivot->cantidad}}</td>
                                <td>{{$producto->precio_publico_iva * $producto->pivot->cantidad}}</td>
                                <td>
                                    <form action="{{route('devolucion.damage')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_venta" value="{{$venta->id}}">
                                        <input type="hidden" name="sku" value="{{$producto->sku}}">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-4 form-group">
                    <label class="control-label">Subtotal:</label>
                    <input type="number" class="form-control" value="{{$venta->subtotal}}" readonly="">
                </div>
                <div class="col-4 form-group">
                    <label class="control-label">Descuento:</label>
                    <input type="text" class="form-control"
                        value="{{round($venta->subtotal-$venta->total+($venta->subtotal*0.16))}}" readonly="">
                    {{-- @if ($venta->descuento)
                            @if ($venta->promocion->tipo=='E')
                                <input type="text" class="form-control" value="0" readonly="">
                            @else
                                <input type="text" class="form-control" value="{{ $venta->subtotal-$venta->total+($venta->subtotal*0.16) }}"
                    readonly="">
                    @endif

                    @else
                    <input type="text" class="form-control" value="0" readonly="">
                    @endif --}}

                </div>
                <div class="col-4 form-group">
                    <label class="control-label">Total:</label>
                    <input type="number" class="form-control" value="{{$venta->total}}" readonly="">
                </div>
            </div>

        </div>
        </form>

    </div>

</div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaHistorialCambios').DataTable();
    } );
</script>

@endsection