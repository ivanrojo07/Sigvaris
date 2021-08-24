@extends('principal')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <h4>Retex</h4>
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
                                <th>Garex/Retex Folio</th>
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
                                
                                
                                 <td>@foreach($garex as $ga)
                                    @if($ga->SKU == $producto->sku )
                                    {{$ga->folio}}
                                 @endif
                                  @endforeach
                             </td>
                                
                                
                                <td>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal-{{$producto->id}}">
                                        <i class="fa fa-registered" aria-hidden="true"></i>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-{{$producto->id}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">RETEX DEL PRODUCTO:
                                                        {{$producto->sku}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form
                                                    action="{{route('Retex.store', ['venta' => $venta])}}"
                                                    method="POST">
                                                    @csrf

                                                    <div class="modal-body">
                                                            
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label for="" class="text-uppercase text-muted mt-2">GAREXT FOLIO
                                                                    </label>
                                                                <input type="text" name="garex_folio"
                                                                    class="form-control garex_folio" value=""
                                                                    readonly>
                                                            </div>
                                                             <div class="col-6">
                                                                <label for="" class="text-uppercase text-muted mt-2">RETEX FOLIO
                                                                    </label>
                                                                <input type="text" name="retex_folio"
                                                                    class="form-control retex_folio" value="" 
                                                                    readonly>
                                                            </div>
                                                             <div class="col-12">
                                                                <label for="" class="text-uppercase text-muted mt-2">VALIDO HASTA:
                                                                    </label>
                                                                <input type="text" name="garex_fin"
                                                                    class="form-control garex_fin" value="" 
                                                                    readonly>
                                                            </div>
                                                            <input type="hidden" name="venta_id" id="venta_id" value="{{$venta->id}}">
                                                            <div class="col-12 col-md-6">
                                                                <label for="" class="text-uppercase text-muted mt-2">SKU
                                                                    PRODUCTO ORIGINAL</label>
                                                                <input type="text" name="skuProductoRegresado"
                                                                    class="form-control inputSkuProductoDevuelto" value="{{$producto->sku}}" productoId="{{$producto->id}}"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <label for="" class="text-uppercase text-muted mt-2">SKU
                                                                    PRODUCTO ENTREGADO</label>
                                                                <input type="text" class="form-control inputSkuProductoEntregado"
                                                                    name="skuProductoEntregado" productoId="{{$producto->id}}" ventaId="{{$venta->id}}" onkeypress="return event.keyCode!=13">
                                                            </div>

                                                            <div class="col-12 col-md-6">
                                                                <label for="" class="text-uppercase text-muted mt-2">$
                                                                    PRODUCTO DEVUELTO</label>
                                                                <input type="text" class="form-control inputPrecioProductoDevuelto"
                                                                    value="{{ $producto->precio_publico_iva }}" productoId="{{$producto->id}}" readonly>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <label for="" class="text-uppercase text-muted mt-2">$
                                                                    PRODUCTO ENTREGADO</label>
                                                                <input type="text" class="form-control inputPrecioProductoEntregado" productoId="{{$producto->id}}" readonly>
                                                            </div>
                                                            <div class="col-12">
                                                                <label for="" class="text-uppercase text-muted mt-2">TOTAL A PAGAR CON 75% DE DESCUENTO</label>
                                                                <input type="text" name="diferenciaPrecios" class="form-control inputPrecioDiferencia" productoId="{{$producto->id}}" readonly>
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

    async function updatePrecioProductoEntregado( skuProducto, idProducto ){
        await $.ajax( {
            url: `/api/productos/sku/${skuProducto}`,
            success: function( response ){
                console.table( response )
                $(`.inputPrecioProductoEntregado[productoId=${idProducto}]`).val( response.precio_publico_iva )
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
        // skuProductoRegresado
        
        console.table({
            ventaId,
            skuProductoDevuelto,
            skuProductoEntregado
        })

        await $.ajax( {
            url: `/api/ventas/calcular-diferencia-retex`,
            data: {
                ventaId,
                precioProductoDevuelto,
                skuProductoEntregado,
                skuProductoDevuelto
               
            },
            success: function( response ){
                console.log('RESPONSE')
                console.log( response )
                
                $(`.inputPrecioDiferencia[productoId=${idProducto}]`).val( parseFloat(response.diferencia).toFixed(2) );
               //    var original =  parseInt(response.precio_original);
               $(`.garex_folio`).val( response.folio_garex);
                $(`.retex_folio`).val( response.folio_retex);
                $(`.garex_fin`).val( response.fecha_termino);
               // $(`.precioNew`).val( parseInt(response.precio_nueva.precio_publico_iva) );
               // console.log('PRecio_original',response.precio_original);
               //  console.log('PRecio_nueva',response.precio_nueva.precio_publico_iva);
               //  console.log('venta',response.venta);
               //      console.log('promo',response.promo);
               //      console.log('cinco',response.cinco);
               //      console.log('dos',response.dos);
               //      console.log('uno',response.uno);
                    console.log('Diferencia',response.diferencia);
               //      console.log('Cuatro',response.cuatro);
               //      console.log('Tres',response.tres);
               // $(`.preciouno`).val( response.uno);
               // $(`.preciodos`).val( response.dos );
               // $(`.preciotres`).val( response.cinco );
               //  $(`.prec_des`).val( response.promo);
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