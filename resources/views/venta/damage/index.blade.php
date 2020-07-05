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
                                <th>Damage</th>
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
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#damage-{{$producto->id}}">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="damage-{{$producto->id}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">NUEVO DAMAGE DE
                                                        PACIENTE</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{route('devolucion.damage')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id_venta" value="{{$venta->id}}">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12 mt-2">
                                                                <label for=""
                                                                    class="text-uppercase text-muted">FECHA</label>
                                                                <input value="{{ date('Y-m-d') }}" id="inputFecha"
                                                                    type="date" class="form-control" readonly>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <label for=""
                                                                    class="text-uppercase text-muted">RESPONSABLE</label>
                                                                <input value="{{ Auth::user()->name }}"
                                                                    id="inputResponsable" type="text"
                                                                    class="form-control" readonly>
                                                            </div>
                                                            {{--  --}}
                                                            <div class="col-12 mt-2">
                                                                <label for="" class="text-uppercase text-muted">SUCURSAL</label>
                                                                <input id="sucursal" type="text"
                                                                    class="form-control"
                                                                    value="{{\App\Oficina::where('id',session('oficina'))->value('nombre')}}" readonly>
                                                            </div> 
                                                        {{--  --}}
                                                        <div class="col-12 mt-2">
                                                            <label for="" class="text-uppercase text-muted">NOMBRE
                                                                DEL PRODUCTO</label>
                                                            <input id="inputNombreProducto" type="text"
                                                                class="form-control" value="{{$producto->descripcion}}"
                                                                readonly>
                                                        </div>
                                                        {{--  --}}
                                                        <div class="col-6 mt-2">
                                                            <label for="" class="text-uppercase text-muted">SKU
                                                                PRODUCTO DAÑADO</label>
                                                            <input id="inputSkuProductoDaniado" type="text" name="sku"
                                                                class="form-control inputSkuProductoDevuelto" value="{{$producto->sku}}"
                                                                productoId="{{$producto->id}}"
                                                                readonly>
                                                        </div>
                                                        <div class="col-6 mt-2">
                                                            <label for="" class="text-uppercase text-muted">SKU
                                                                PRODUCTO ENTREGADO</label>
                                                            <input type="text" class="form-control inputSkuProductoEntregado"
                                                                    name="skuProductoEntregado" productoId="{{$producto->id}}" ventaId="{{$venta->id}}">
                                                        </div>
                                                        <div class="col-12 col-md-12">
                                                                <label for="" class="text-uppercase text-muted mt-2">$
                                                                    PRODUCTO DAÑADO</label>
                                                                <input type="text" class="form-control inputPrecioProductoDevuelto"
                                                                    value="{{ $producto->pivot->precio }}" productoId="{{$producto->id}}" readonly>
                                                            </div>
                                                        <div class="col-12">
                                                                <label for="" class="text-uppercase text-muted mt-2">DIFERENCIA</label>
                                                                <input type="text" name="diferenciaPrecios" class="form-control inputPrecioDiferencia" productoId="{{$producto->id}}" readonly>
                                                            </div>

                                                        <div class="col-12 mt-2">
                                                            <label for="" class="text-uppercase text-muted">TIPO DAMAGE</label>
                                                            <select name="tipo" id="tipo" class="form-control" required>
                                                                <option value="fabrica">por fábrica</option>
                                                                <option value="paciente">por paciente</option>
                                                            </select>
                                                        </div>
                                                        {{--  --}}
                                                        <div class="col-12 mt-2">
                                                            <label for="" class="text-uppercase text-muted">DESCRIPCIÓN
                                                                DEL
                                                                DAÑO</label>
                                                            <textarea name="descripcion" id="" class="form-control"
                                                                rows="5"></textarea>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">GUARDAR</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                </div>


                {{-- <form action="{{route('devolucion.damage')}}" method="POST">
                @csrf
                <input type="hidden" name="id_venta" value="{{$venta->id}}">
                <input type="hidden" name="sku" value="{{$producto->sku}}">
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
                </form> --}}
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
    async function updatePrecioProductoEntregado( skuProducto, idProducto ){
        await $.ajax( {
            url: `/api/productos/sku/${skuProducto}`,
            success: function( response ){
                console.table( response )
                $(`.inputPrecioProductoEntregado[productoId=${idProducto}]`).val( response.precio_publico )
                // const precioProductoDevuelto = $(`.inputPrecioProductoDevuelto[productoId=${idProducto}]`).val()
                // $(`.inputPrecioDiferencia[productoId=${idProducto}]`).val( parseFloat( precioProductoDevuelto ) - parseFloat(response.precio_publico) )
            },
            error: function( e ){
                $(`.inputPrecioProductoEntregado[productoId=${idProducto}]`).val( 'N/E' )
            }
        } )
    }

    async function updateDiferenciaDePrecios( idProducto, ventaId, precioProductoDevuelto, skuProductoEntregado ){

        console.log('======================')

        console.table({
            ventaId,
            skuProductoDevuelto,
            skuProductoEntregado
        })

        await $.ajax( {
            url: `/api/ventas/calcular-diferencia`,
            data: {
                ventaId,
                precioProductoDevuelto,
                skuProductoEntregado
            },
            success: function( response ){
                console.log('RESPONSE')
                console.log( response )
                
                $(`.inputPrecioDiferencia[productoId=${idProducto}]`).val( parseFloat(response.diferencia).toFixed(2) )
            },
            error: function( e ){
                console.table(e)
                $(`.inputPrecioDiferencia[productoId=${idProducto}]`).val( 0 )
            }
        } )
    }

    $(document).ready(function() {
        $('#tablaHistorialCambios').DataTable();
    } );



    $(document).on('keyup', '.inputSkuProductoEntregado', async function(){
        skuProductoEntregado = $(this).val()
        idProducto = $(this).attr('productoId')
        skuProductoDevuelto = $(`.inputSkuProductoDevuelto[productoId=${idProducto}]`).val();
        precioProductoDevuelto = $(`.inputPrecioProductoDevuelto[productoId=${idProducto}]`).val();
        ventaId = $(this).attr('ventaId');

        await updatePrecioProductoEntregado( skuProductoEntregado, idProducto );
        await updateDiferenciaDePrecios( idProducto, ventaId, precioProductoDevuelto, skuProductoEntregado );

    });
</script>

@endsection