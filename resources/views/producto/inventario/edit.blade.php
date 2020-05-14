@extends('principal')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-5">
                {{-- ENCABEZADO DE LA SECCION --}}
                <div class="card-header text-center"><h4>ACTUALIZAR INVENTARIO</h4></div>
                {{-- FORMULARIO DEL PRODUCTO ELEGIDO --}}
                <div class="card-body">
                    <form action="{{route('producto.inventario.update')}}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- INPUT FECHA --}}
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">FECHA</label>
                                    <input type="text" name="fecha" value="{{$producto->updated_at}}" class="form-control" readonly>
                                </div>
                            </div>
                            {{-- INPUT SKU --}}
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">SKU</label>
                                    <input type="text" name="sku" value="{{$producto->sku}}" class="form-control">
                                </div>
                            </div>
                            {{-- INPUT SWISS ID --}}
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">SWISS ID</label>
                                    <input type="text" name="swissId" value="{{$producto->swiss_id}}" class="form-control">
                                </div>
                            </div>
                            {{-- INPUT UPC --}}
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">UPC</label>
                                    <input type="text" name="upc" value="{{$producto->upc}}" class="form-control">
                                </div>
                            </div>
                            {{-- INPUT STOCK --}}
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">STOCK</label>
                                    <input type="number" name="stock" value="{{$producto->stock}}" class="form-control">
                                </div>
                            </div>
                            {{-- INPUT CANTIDAD DE PRODUCTOS A DAR DE BAJA --}}
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">PRODUCTOS A DAR DE BAJA</label>
                                    <input type="number" name="numProductosBaja" value="0" class="form-control" required>
                                </div>
                            </div>
                            {{-- INPUT CANTIDAD DE PRODUCTOS A DAR DE ALTA --}}
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">PRODUCTOS A DAR DE ALTA</label>
                                    <input type="number" name="numProductosAlta" value="0" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            {{-- INPUT DESCRIPCION --}}
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">DESCRIPCIÓN DEL PRODUCTO</label>
                                    <textarea name="descripcion"value="{{$producto->descripcion}}" class="form-control"></textarea>
                                </div>
                            </div>
                            {{-- INPUT MOTIVO BAJA --}}
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">MOTIVO BAJA</label>
                                    <textarea name="motivoBaja" value="" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- INPUT USUARIO RESPONSABLE --}}
                            <input type="hidden" name="productoId" value="{{$producto->id}}" class="form-control" readonly>
                            <input type="hidden" name="responsable" value="{{$usuario->id}}" class="form-control" readonly>
                            {{-- BOTON PARA GUARDAS LOS DATOS --}}
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success mt-4">GUARDAR</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                {{-- TABLA DEL HISTORIAL --}}
                <div class="card-body">
                    @include('componentes.productos.inventario.historial.tabla',['historialModificacionesInventario' => $producto->Historial])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection