@extends('principal')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
        <div class="row">
            <div class="col-4">
                <h4>Productos</h4>
            </div>
            <div class="col-4">
                <a href="{{ route('productos.create') }}" class="btn btn-success">
					<i class="fa fa-plus"></i><strong> Agregar Producto</strong>
				</a>
            </div>
        </div>
        </div>
        <div class="card-body">
            <table class="table table-responsive table-hover" id="productos">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Descripción</th>
                        <th>Line</th>
                        <th>UPC</th>
                        <th>Swiss id</th>
                        <th>Precio Público S/IVA</th>
                        <th>Precio Público C/IVA</th>
                        <th>Operación</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$productos)
                    <h3>No hay productos registrados</h3>
                    @else
                    @foreach($productos as $producto)
                    <tr>
                        <td>{{$producto->sku}}</td>
                        <td>{{$producto->descripcion}}</td>
                        <td>{{$producto->line}}</td>
                        <td>{{$producto->upc}}</td>
                        <td>{{$producto->swiss_id}}</td>
                        <td>${{$producto->precio_publico}}</td>
                        <td>${{$producto->precio_publico_iva}}</td>
                        <td>
                            <a href="{{route('productos.show', ['producto'=>$producto])}}"
                                class="btn btn-primary btn-sm"><i class="fas fa-eye"></i><strong> Ver</strong></a>
                            <a href="{{route('productos.edit', ['producto'=>$producto])}}"
                                class="btn btn-warning btn-sm"><i class="fas fa-edit"></i><strong> Editar</strong></a>
                            <form role="form" name="productoborrar" id="form-doctor" method="POST"
                                action="{{ route('productos.destroy', ['producto'=>$producto]) }}" name="form">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                        <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i><strong> Borrar</strong></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#productos').DataTable({
            'language':{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Productos _START_ al _END_ de un total de _TOTAL_ ",
                "sInfoEmpty":      "Productos 0 de un total de 0 ",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    } );
</script>

@endsection
