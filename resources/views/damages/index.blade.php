@extends('principal')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus" aria-hidden="true"></i>
                DAMAGE FÁBRICA
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">NUEVO DAMAGE DE FABRICA</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('productos.damage.store')}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <label for="" class="text-uppercase text-muted">SKU PRODUCTO DAÑADO</label>
                                        <input id="inputSkuProductoDaniado" type="text" name="sku" class="form-control" required>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label for="" class="text-uppercase text-muted">DESCRIPCIÓN DEL DAÑO</label>
                                        <select name="descripcion" id="" class="form-control" required>
                                            <option value="">Seleccionar</option>
                                            <option value="Defecto de fábrica">Defecto de fábrica</option>
                                            <option value="Hilo jalado">Hilo jalado</option>
                                            <option value="Hilos sueltos">Hilos sueltos</option>
                                            <option value="Roto">Roto</option>
                                            <option value="Zurcidos adicionales">Zurcidos adicionales</option>
                                            <option value="Silicón">Silicón</option>
                                            <option value="Producto no corresponde al código de caja">Producto no corresponde al código de caja</option>
                                            <option value="Puente dañado">Puente dañado</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label for="" class="text-uppercase text-muted">NOMBRE DEL PRODUCTO</label>
                                        <input id="inputNombreProducto" value="N/E" type="text" class="form-control" readonly>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label for="" class="text-uppercase text-muted">FECHA</label>
                                        <input value="{{ date('Y-m-d') }}" id="inputFecha" type="date" class="form-control" readonly>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label for="" class="text-uppercase text-muted">RESPONSABLE</label>
                                        <input value="{{ Auth::user()->name }}" id="inputResponsable" type="text" class="form-control" readonly>
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

            <table class="table table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">SKU</th>
                        <th scope="col">TIPO DAMAGE</th>
                        <th scope="col">RESPONSABLE</th>
                        <th scope="col">DESCRIPCION</th>
                        <th scope="col">FECHA </th>
                        <th scope="col">ACTUALIZACIÓN</th>
                        <th scope="col">Tienda</th>
                        <th scope="col">ACCIÓN </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productosDamage as $productoDamage)
                    <tr>
                        <th scope="row">{{$productoDamage->id}}</th>
                        <td>{{$productoDamage->producto->sku}}</td>
                        <td>{{$productoDamage->tipo_damage}}</td>
                        <td>{{$productoDamage->user ? $productoDamage->user->name : ''}}</td>
                        <td>{{$productoDamage->descripcion}}</td>
                        <td>{{$productoDamage->created_at}}</td>
                        <td>{{$productoDamage->created_at}}</td>
                        <td>{{\App\Oficina::where('id',$productoDamage->producto->oficina_id)->value('nombre') }}</td>
                        <td>

                            @if($productoDamage->tipo_damage=="fabrica")
                            <form action="{{route('productos.damage.reemplazo')}}" method="POST">
                                <input type="hidden" name="producto_id" value="{{$productoDamage->producto_id}}">
                                <input type="hidden" name="idDamag" value="{{$productoDamage->id}}">
                                <button type="submit" class="btn btn-primary">Envío de reemplazo</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready( function(){


    $(document).on('keyup', '#inputSkuProductoDaniado', function(){
        skuProducto = $(this).val()

        $.ajax( {
            url: `/api/productos/sku/${skuProducto}`,
            success: function( response ){
                console.table( response )
                $('#inputNombreProducto').val( response.descripcion )
            },
            error: function( e ){
                $('#inputNombreProducto').val( 'N/E' )
            }
        } )

    });



} );



</script>

@endsection