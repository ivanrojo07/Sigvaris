@extends('principal')
@section('content')

<div class="container">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    {{-- TABLA DE PRODUCTOS --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card rounded-0">
                <div class="card-header rounded-0">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <h3>Productos</h3>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label>Buscar:<input type="text" id="BuscarProducto" onkeypress="return event.keyCode!=13">
                            </label>
                        </div>
                    </div>
                </div>


                <div class="card-body rounded-0">
                    <div class="table-responsive">
                        <table class="table" id="productos">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>UPC</th>
                                    <th>swiss ID</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Precio con iva</th>
                                </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        var t = $('#productos').DataTable();

        $("#BuscarProducto").on('keyup', function (e) {
          var keycode = e.keyCode || e.which;
            if (keycode == 13) {
                //console.log($(this).val());
                $.ajax({
                    url:"{{ url('productos/getProductoExistsDesc') }}",
                    type:'POST',
                    data: {"_token": $("meta[name='csrf-token']").attr("content"),
                               "sku" : $("#BuscarProducto").val()
                        },
                    success: function(res){
                        
                        if (res!=0) {
                            console.log(res);
                            t.row.add([
                                res.id,
                                res.upc,
                                res.swiss_id,
                                res.descripcion,
                                res.precio_publico,
                                res.precio_publico_iva
                            ]).draw();
                        }
                    }

                });
            }
        });
    } );
</script>

@endsection