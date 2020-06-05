@extends('principal')
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}">
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h4>Crear Producto Negado</h4>
                </div>
                <div class="col-6 text-center">
                    <a href="{{ url('negado/show') }}" class="btn btn-primary">
                        <i class="fa fa-bars"></i><strong> Lista de Productos Negados</strong>
                    </a>
                </div>
            </div>

        </div>
        <div class="card-body">
            {{-- TABLA DE PACIENTES --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card rounded-0">
                        <div class="card-header rounded-0">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <h3>Pacientes</h3>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <label>Buscar:<input type="search" id="BuscarPaciente"
                                            onkeypress="return event.keyCode!=13">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body rounded-0">
                            <div class="table-responsive">
                                <table class="table" id="pacientes">
                                    <thead>
                                        <tr>
                                            <th>RFC</th>
                                            <th>Nombre</th>
                                            <th>Apellidos</th>
                                            <th>Teléfono</th>
                                            <th>Seleccionar</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <label>Buscar:<input type="text" id="BuscarProducto"
                                            onkeypress="return event.keyCode!=13">
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
                                            <th>Agregar</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <form role="form" id="form-cliente" method="POST" action="{{route('negado.create')}}" name="form">

                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="inputGenerarFolio">
                                        <label class="form-check-label" for="exampleCheck1">¿Generar folio?</label>
                                    </div>
                                </div>
                                <input type="hidden" name="oficina_id" class="form-control" step="1" id="inputOficinaId"
                                    value="{{$oficina_id}}" readonly>
                                <div class="form-group">
                                    <label class="control-label">Folio :</label>
                                    <input type="number" name="folio" class="form-control" step="1" id="folio" readonly>
                                </div>
                                <div class="form-group">
                                    <label id="No_repetido" class="control-label">✱ SKU producto devuelto:</label>
                                    <select class="form-control" name="producto_id" id="producto_id" required>
                                        <option value="">Buscar..</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">SKU producto entregado:</label>
                                    <input type="text" name="producto2" class="form-control" id="precio">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label id="No_repetido" class="control-label">✱Paciente:</label>
                                    <select class="form-control" name="paciente_id" id="paciente_id" required>
                                        <option value="">Buscar..</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><br>Comentarios :</label>
                                    <input type="text" name="comentarios" class="form-control" step="1"
                                        id="comentarios">
                                </div>
                                <input type="hidden" name="oficina_id" value="{{session('oficina')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="actual">✱Fecha actual</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha"
                                        value="{{date('Y-m-d')}}" readonly="" required="">
                                </div>
                                <div class="form-group">
                                    <label for="actual">Fecha posible entrega:</label>
                                    <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega"
                                        value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">

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
<script type="text/javascript">
    class Folio{

        static obtenerElUltimoFolio(){

            console.log('oficina_id', $('#inputOficinaId').val() );

            $.ajax('/api/productos/negados/folios/next', {
                data: {
                    oficina_id: $('#inputOficinaId').val()
                },
                dataType: 'json',
                success: function(response){
                    $('#folio').val(response.folio)
                },
                error: function(error){
                    console.log(error)
                }
            });

        }

        static eliminarFolio(){
            $('#folio').val('')
        }

    }

    $(document).ready(function(){
        $("#BuscarPaciente").on('keyup', function (e) {
          var keycode = e.keyCode || e.which;
            if (keycode == 13) {
                $("#pacientes").dataTable().fnDestroy();
            //console.log($(this).val());
            $('#pacientes').DataTable({
                "ajax":{
                    type: "POST",
                    url:"/getPacientes_nombre",
                    data: {"_token": $("meta[name='csrf-token']").attr("content"),
                           "nombre" : $(this).val()
                    }
                },
                "searching": false,
                pageLength : 3,
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

            }
        });
        
        $("#BuscarProducto").on('keyup', function (e) {
          var keycode = e.keyCode || e.which;
            if (keycode == 13) {
                $("#productos").dataTable().fnDestroy();
                //console.log($(this).val());
                $('#productos').DataTable({
                    "ajax":{
                        type: "POST",
                        url:"/getProductos_nombre",
                        data: {"_token": $("meta[name='csrf-token']").attr("content"),
                               "nombre" : $(this).val()
                        }
                    },
                    "searching": false,
                    pageLength : 3,
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
            }
        });      
    });
  
    
    $(document).on('click', '.botonSeleccionCliente', async function(){
        
        const paciente_id = $(this).attr('pacienteid');
        const paciente_nombre = $(this).attr('nombre');
        $('#paciente_id').append("<option value='"+paciente_id+"' >"+paciente_nombre+"</option>");
        $('#paciente_id').val(paciente_id);
    /* Act on the event */
    });

    $(document).on('click', '#inputGenerarFolio', function(){
        generarFolio = $(this).is(':checked')

        if( generarFolio ){
            Folio.obtenerElUltimoFolio()
            return;
        }

        Folio.eliminarFolio()



    });

    function agregarProducto(p){
        let producto = JSON.parse($(p).val());
        // alert(producto);


        $('#producto_id').append("<option value='"+producto.id+"' >"+producto.descripcion+"</option>");
        $('#producto_id').val(producto.id);

    }
</script>
@endsection