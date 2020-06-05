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
                                <th>Cambiar</th>
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

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal-{{$producto->id}}">
                                        <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-{{$producto->id}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Cambio de producto:
                                                        {{$producto->sku}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{route('ventas.cambio-fisico.store', ['venta' => $venta->id])}}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="" class="text-uppercase text-muted mt-2">SKU
                                                                    PRODUCTO DEVUELTO</label>
                                                                <input type="text" name="skuProductoRegresado"
                                                                    class="form-control" value="{{$producto->sku}}"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-12">
                                                                <label for="" class="text-uppercase text-muted mt-2">SKU
                                                                    PRODUCTO ENTREGADO</label>
                                                                <input type="text" class="form-control"
                                                                    name="skuProductoEntregado">
                                                            </div>
                                                            <div class="col-12">
                                                                <label for=""
                                                                    class="text-uppercase text-muted mt-2">DESCRIPCIÓN</label>
                                                                <textarea name="observaciones" id="" rows="5"
                                                                    class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">CAMBIAR</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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