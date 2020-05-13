@extends('principal')
@section('content')
<style>
    .ques {
        color: darkslateblue;
    }
    .switch {
      position: relative;
      display: inline-block;
      width: 80px;
      height: 31px;
    }

    .switch input {display:none;}

    .slider {
      position: absolute;
      cursor: pointer;
      overflow: hidden;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #f2f2f2;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      z-index: 2;
      content: "";
      height: 28px;
      width: 30px;
      left: 2px;
      bottom: 2px;
      background-color: darkslategrey;
          -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.22);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.22);
      -webkit-transition: .4s;
      transition: all 0.4s ease-in-out;
    }
    .slider:after {
      position: absolute;
      left: -10px;
      z-index: 1;
      content: "YES";
        font-size: 16px;
        text-align: left !important;
        line-height: 30px;
      padding-left: 0;
        width: 152px;
        color: #fff;
        height: 30px;
        border-radius: 100px;
        background-color: #ff6418;
        -webkit-transform: translateX(-109px);
        -ms-transform: translateX(-109px);
        transform: translateX(-109px);
        transition: all 0.4s ease-in-out;
    }

    input:checked + .slider:after {
      -webkit-transform: translateX(0px);
      -ms-transform: translateX(0px);
      transform: translateX(0px);
      /*width: 235px;*/
      padding-left: 25px;
    }

    input:checked + .slider:before {
      background-color: #fff;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(47px);
      -ms-transform: translateX(47px);
      transform: translateX(47px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 100px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
    .absolute-no {
        position: absolute;
        left: 10px;
        color: darkslategrey;
        text-align: right !important;
        font-size: 16px;
        width: calc(100% - 25px);
        height: 50px;
        line-height: 30px;
        cursor: pointer;
    }
</style>
<div class="container">
	<div class="card">
        
        
            <div class="card-header">
                <h1>Nuevo Descuento</h1>
            </div>
            <div class="card-body">    
                
                <div class="row">
                    <div class="form-group col-2 text-center">
                    </div> 
                    <div class="form-group col-8 text-center">
                        <label class="" for="fin">Tipo de descuento: </label>
                        <select class="form-control" name="tipo" id="tipo"  required="">  
                                <option value="">Seleccionar ...</option>      
                                <option value="A">mayor / igual que ____  prendas</option>
                                <option value="B">mayor / igual que ____  dinero</option>
                                <option value="C">convenios</option>
                        </select>
                    </div>   
                </div>
                
                
                <div id="tipoA" style="display:none;">
                    <form class="" action="{{route('descuentos.store')}}" method="post">
                        {{ csrf_field() }}
                        <input type="text" class="form-control" name="tipoDes"  value="A" style="display:none;">
                        <input type="text" class="form-control" name="unidad_compra"  value="Prendas" style="display:none;">
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="nombre">Nombre *</label>
                                <input type="text" class="form-control" name="nombre" required="">
                            </div>
                            <div class="form-group col-4">
                                <label for="inicio">De: *</label>
                                <input type="date" class="form-control" name="inicio"  required="">
                            </div>
                            <div class="form-group col-4">
                                <label for="fin">A: *</label>
                                <input type="date" step="0.01" name="fin" class="form-control"required="">
                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Compra minima de prendas: </label>
                                    <input type="number" class="form-control" name="compra_min" id="compra_min">
                                </div>
                            </div>
                            <div class="col-4 pl-0">
                                <label>Unidad de descuento: </label>
                                <select class="form-control" name="unidad_descuento" id="unidad_descuento"  required="">        
                                        <option value="">Seleccionar ...</option>  
                                        <option value="Pesos">$</option>
                                        <option value="Procentaje1">% producto más bajo</option>
                                        <option value="Procentaje2">% del total de la compra</option>
                                        <option value="sigCompra">sigPesos en la siguiente compra</option>
                                        <option value="Pieza">Pieza Gratis</option>
                                </select>
                            </div>
                            <div class="col-4 pl-0" style="display:none;" id="Des">
                                <label style="display:none;" id="Des_Por" >Porcentaje de descuento: </label>
                                <label style="display:none;" id="Des_Pes">Pesos de descuento: </label>
                                <label style="display:none;" id="Des_sigCom" >sigPesos en la siguiente compra: </label>
                                <input type="number" class="form-control" name="descuento_de" id="descuento_de" value="0" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 pl-0">
                                <label>Acepta sigPesos: </label>
                                <select class="form-control" name="aceptSigPesos" id="aceptSigPesos"  required="">        
                                        <option value="">Seleccionar ...</option>  
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="nombre">Descripción *</label>
                                <input type="text" class="form-control" name="descripcion" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 pt-4 m-auto">
                                <button type="submit" class="btn btn-success btn-md btn-block">Agregar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="tipoB" style="display:none;">
                    <form class="" action="{{route('descuentos.store')}}" method="post">
                        {{ csrf_field() }}
                        <input type="text" class="form-control" name="tipoDes"  value="B" style="display:none;">
                        <input type="text" class="form-control" name="unidad_compra"  value="Pesos" style="display:none;">
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="nombre">Nombre *</label>
                                <input type="text" class="form-control" name="nombre" required="">
                            </div>
                            <div class="form-group col-4">
                                <label for="inicio">De: *</label>
                                <input type="date" class="form-control" name="inicio" required="">
                            </div>
                            <div class="form-group col-4">
                                <label for="fin">A: *</label>
                                <input type="date" step="0.01" name="fin" class="form-control" required="">
                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Monto minimo de compra: </label>
                                    <input type="number" class="form-control" name="compra_min" id="compra_minB">
                                </div>
                            </div>
                            <div class="col-4 pl-0">
                                <label>Unidad de descuento: </label>
                                <select class="form-control" name="unidad_descuento" id="unidad_descuentoB"  required="">        
                                        <option value="">Seleccionar ...</option>  
                                        <option value="Pesos">$</option>
                                        <option value="Procentaje1">% producto más bajo</option>
                                        <option value="Procentaje2">% del total de la compra</option>
                                        <option value="sigCompra">sigPesos en la siguiente compra</option>
                                        <option value="Pieza">Pieza Gratis</option>
                                </select>
                            </div>
                            <div class="col-4 pl-0" style="display:none;" id="DesB">
                                <label style="display:none;" id="Des_PorB" >Porcentaje de descuento: </label>
                                <label style="display:none;" id="Des_PesB">Pesos de descuento: </label>
                                <label style="display:none;" id="Des_sigComB" >sigPesos en la siguiente compra: </label>
                                <input type="number" class="form-control" name="descuento_de" id="descuento_deB" value="0" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 pl-0">
                                <label>Acepta sigPesos: </label>
                                <select class="form-control" name="aceptSigPesos" id="aceptSigPesos"  required="">        
                                        <option value="">Seleccionar ...</option>  
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="nombre">Descripción *</label>
                                <input type="text" class="form-control" name="descripcion" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 pt-4 m-auto">
                                <button type="submit" class="btn btn-success btn-md btn-block">Agregar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="tipoC" style="display:none;">
                    <form class="" action="{{route('descuentos.store')}}" method="post">
                        {{ csrf_field() }}
                        <input type="text" class="form-control" name="tipoDes"  value="C" style="display:none;">
                        <input type="number" class="form-control" name="compra_min"  value="0" style="display:none;">
                        <input type="text" class="form-control" name="unidad_compra"  value="0" style="display:none;">
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="nombre">Nombre *</label>
                                <input type="text" class="form-control" name="nombre" required="">
                            </div>
                            <div class="form-group col-4">
                                <label for="inicio">De: *</label>
                                <input type="date" class="form-control" name="inicio" required="">
                            </div>
                            <div class="form-group col-4">
                                <label for="fin">A: *</label>
                                <input type="date" step="0.01" name="fin" class="form-control" required="">
                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-2 pl-0">
                            </div>
                            <div class="col-4 pl-0">
                                <label>Unidad de descuento: </label>
                                <select class="form-control" name="unidad_descuento" id="unidad_descuentoC"  required="">        
                                        <option value="">Seleccionar ...</option>  
                                        <option value="Pesos">$</option>
                                        <option value="Procentaje">%</option>
                                        <option value="sigCompra">sigPesos en la siguiente compra</option>
                                        <option value="Pieza">Pieza Gratis</option>
                                </select>
                            </div>
                            <div class="col-4 pl-0" style="display:none;" id="DesC">
                                <label style="display:none;" id="Des_PorC" >Porcentaje de descuento: </label>
                                <label style="display:none;" id="Des_PesC">Pesos de descuento: </label>
                                <label style="display:none;" id="Des_sigComC" >sigPesos en la siguiente compra: </label>
                                <input type="number" class="form-control" name="descuento_de" id="descuento_deC" value="0" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 pl-0">
                                <label>Acepta sigPesos: </label>
                                <select class="form-control" name="aceptSigPesos" id="aceptSigPesos"  required="">        
                                        <option value="">Seleccionar ...</option>  
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="nombre">Descripción *</label>
                                <input type="text" class="form-control" name="descripcion" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 pt-4 m-auto text-center">
                                <button type="submit" class="btn btn-success btn-md btn-block">Agregar</button>
                            </div>
                        </div>
                    </form>
                </div>
                {{--<label>Tipo: </label>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="checkbox" name="tipoA" id="tipoA">
                            <label>Compra: </label>
                            <input type="number" class="form-control" name="compra_minA" id="compra_minA">
                            <label> Llevate: </label>
                            <input type="number" class="form-control" name="descuento_deA" id="descuento_deA">
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <input type="checkbox" name="tipoB" id="tipoB">
                            <label>Monto mínimo de compra: </label>
                            <input type="number" class="form-control" name="compra_minB" id="compra_minB" >
                            <label>$ por un descuento de: </label>
                            <div class="row">
                                <div class="col-6 pr-0">
                                    <input type="number" class="form-control" name="descuento_deB" id="descuento_deB" min="0">
                                </div>
                                <div class="col-6 pl-0">
                                    <select class="form-control" name="unidad_descuentoB" id="unidad_descuentoB"  required="">        
                                            <option value="$">$</option>
                                            <option value="%">%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <input type="checkbox" name="tipoC" id="tipoC">
                            <label>Descuento por cumpleaños </label>
                            <div class="row">
                                <div class="col-6 pr-0">
                                    <input type="number" class="form-control" name="descuento_deC" id="descuento_deC">
                                </div>
                                <div class="col-6 pl-0">
                                    <select class="form-control" name="unidad_descuentoC" id="unidad_descuentoC">
                                            <option value="$">$</option>
                                            <option value="%">%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <input type="checkbox" name="tipoD" id="tipoD">
                            <label>Monto mínimo de prendas: </label>
                            <input type="number" class="form-control" name="compra_minD" id="compra_minD">
                            <label> por un descuento de: </label>
                            <div class="row">
                                <div class="col-6 pr-0">
                                    <input type="number" class="form-control" name="descuento_deD" id="descuento_deD">
                                </div>
                                <div class="col-6 pl-0">
                                    <select class="form-control" name="unidad_descuentoD" id="unidad_descuentoD">                               
                                            <option value="$">$</option>
                                            <option value="%">%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <input type="checkbox" name="tipoE" id="tipoE">
                            <label>Monto mínimo de prendas: </label>
                            <input type="number" class="form-control" name="compra_minE" id="compra_minE">
                            <label> por: </label>
                            <input type="number" class="form-control" name="descuento_deE" id="descuento_deE">
                            <label>sigpesos</label>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <input type="checkbox" name="tipoF" id="tipoF">
                            <label>Descuento de empleado: </label>
                            <div class="row">
                                <div class="col-6 pr-0">
                                    <input type="number" class="form-control" name="descuento_deF" id="descuento_deF">
                                </div>
                                <div class="col-6 pl-0">
                                    <select class="form-control" name="unidad_descuentoF" id="unidad_descuentoF">
                                            <option value="$">$</option>
                                            <option value="%">%</option>                  
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <input type="checkbox" name="tipoH" id="tipoH">
                            <label>Descuento en siguiente compra: </label><br>
                            <label>Monto mínimo: </label>
                            <input type="number" class="form-control" name="compra_minH" id="compra_minH">
                            <label> por: </label>
                            <input type="number" class="form-control" name="descuento_deH" id="descuento_deH">
                            <label>sigpesos</label>
                        </div>
                    </div>
                    <div class="col-4">

                        <div class="form-group">
                            <label>Descuento en Productos: </label>
                            <div class="row">
                                <div class="col-sm-3 offset-sm-3">
                                    <label class="switch">
                                      <input type="checkbox" name="tipoG" id="tipoG">
                                      <span class="slider round"></span>
                                      <span class="absolute-no">NO</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <h5>Los campos con <b>*</b> son obligatorios </h5>
                <div  style="display: none;" id="div-prod">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Descuento en productos: </label>
                                <div class="row">
                                    <div class="col-6 pr-0">
                                        <input type="number" class="form-control" name="descuento_deG" id="descuento_deG" value="0">
                                    </div>
                                    <div class="col-6 pl-0">
                                        <select class="form-control" name="unidad_descuentoG" id="unidad_descuentoG">
                                                <option value="%">%</option>                  
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover display" id="table-productos">
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
                                    <input type="hidden" id="producto_a_agregar{{$loop->index}}" value="{{$producto}}">
                                    <td>{{$producto->sku}}</td>
                                    <td>{{$producto->descripcion}}</td>
                                    <td>{{$producto->line}}</td>
                                    <td>{{$producto->upc}}</td>
                                    <td>{{$producto->swiss_id}}</td>
                                    <td>${{$producto->precio_publico}}</td>
                                    <td>${{$producto->precio_publico_iva}}</td>
                                    <td>
                                        <button type="button" class="btn btn-success boton_agregar" onclick="agregarProducto('#producto_a_agregar{{$loop->index}}')"><i class="fas fa-plus"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                </div>
                <div class="row">
                        <h3>Productos Seleccionados</h3>
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio Unitario</th>
                                        <th>Precio Unitario + IVA</th>
                                        <th>Subtotal</th>
                                        <th>Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_productos">
                                    <div id="tbody_productos"></div> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-3 pt-4 m-auto">
                        <button type="submit" class="btn btn-success btn-md btn-block">Agregar</button>
                    </div>
                </div>--}}
            </div>
        
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
{{--<script type="text/javascript">
    $(document).ready(function(){
        $('#table-productos').DataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });

        $('#tipoA').change(function(){
            if(this.checked)
            {
                $('#compra_minA').prop('required',true);
                $('#descuento_deA').prop('required',true);
            }
            else
            {
                $('#compra_minA').prop('required',false);
                $('#descuento_deA').prop('required',false);
            }            
        });

        $('#tipoB').change(function(){
            if(this.checked)
            {
                $('#compra_minB').prop('required',true);
                $('#descuento_deB').prop('required',true);
            }
            else
            {
                $('#compra_minB').prop('required',false);
                $('#descuento_deB').prop('required',false);
            }            
        });

        $('#tipoC').change(function(){
            if(this.checked)
            {
                $('#descuento_deC').prop('required',true);
            }
            else
            {
                $('#descuento_deC').prop('required',false);
            }            
        });

        $('#tipoD').change(function(){
            if(this.checked)
            {
                $('#compra_minD').prop('required',true);
                $('#descuento_deD').prop('required',true);
            }
            else
            {
                $('#compra_minD').prop('required',false);
                $('#descuento_deD').prop('required',false);
            }            
        });

        $('#tipoE').change(function(){
            if(this.checked)
            {
                $('#compra_minE').prop('required',true);
                $('#descuento_deE').prop('required',true);
            }
            else
            {
                $('#compra_minE').prop('required',false);
                $('#descuento_deE').prop('required',false);
            }            
        });

        $('#tipoF').change(function(){
            if(this.checked)
            {
                $('#descuento_deF').prop('required',true);
            }
            else
            {
                $('#descuento_deF').prop('required',false);
            }            
        });

        $('#tipoG').change(function() {
            if ($(this).prop('checked'))
                $('#div-prod').prop('style', '');
            else
                $('#div-prod').prop('style', 'display: none;');
        });
    });

    function agregarProducto(p){
        let producto = JSON.parse($(p).val());
        // alert(producto);
        $('#tbody_productos')
        .append(`
        <tr id="producto_agregado${producto.id}">
            <td>
                <input class="form-control" type="hidden" name="producto_id[]" value="${producto.id}">
                ${producto.descripcion}
            </td>
            <td class="precio_individual">
                $${producto.precio_publico}
            </td>
            <td>$${producto.precio_publico_iva}</td>
            <td class="precio_total">
                $${producto.precio_publico}
            </td>
            <td>
                <button onclick="quitarProducto('#producto_agregado${producto.id}')" type="button" class="btn btn-danger boton_quitar">
                    <i class="fas fa-minus"></i>
                </button>
            </td>
        </tr>`);
        cambiarTotalVenta();
    }
    function quitarProducto(p){
        $(p).remove();
    }
</script>--}}
<script type="text/javascript">
        $(document).ready(function(){
            $('#tipo').change(function() {
                if ($('#tipo').val()=='A') {
                    $('#tipoA').show();
                    $('#tipoB').hide();
                    $('#tipoC').hide();
                }else if ($('#tipo').val()=='B') {
                    $('#tipoA').hide();
                    $('#tipoB').show();
                    $('#tipoC').hide();
                }else if ($('#tipo').val()=='C') {
                    $('#tipoA').hide();
                    $('#tipoB').hide();
                    $('#tipoC').show();
                }
            });
            $('#unidad_descuento').change(function() {
                if ($('#unidad_descuento').val()=='Procentaje'||$('#unidad_descuento').val()=='Procentaje1'||$('#unidad_descuento').val()=='Procentaje2') {
                    $('#Des').show();
                    $('#Des_Por').show();
                    $('#Des_Pes').hide();
                    $('#Des_sigCom').hide();                    
                }else if ($('#unidad_descuento').val()=='Pesos') {
                    $('#Des').show();
                    $('#Des_Por').hide();
                    $('#Des_Pes').show();
                    $('#Des_sigCom').hide();
                }else if ($('#unidad_descuento').val()=='sigCompra') {
                    $('#Des').show();
                    $('#Des_Por').hide();
                    $('#Des_Pes').hide();
                    $('#Des_sigCom').show();
                }else {
                    $('#Des').hide();
                    $('#Des_Por').hide();
                    $('#Des_Pes').hide();
                    $('#Des_sigCom').hide();
                    $('#descuento_de').val(0);
                }
            });
            $('#unidad_descuentoB').change(function() {
                if ($('#unidad_descuentoB').val()=='Procentaje'||$('#unidad_descuentoB').val()=='Procentaje1'||$('#unidad_descuentoB').val()=='Procentaje2') {
                    $('#DesB').show();
                    $('#Des_PorB').show();
                    $('#Des_PesB').hide();
                    $('#Des_sigComB').hide();                    
                }else if ($('#unidad_descuentoB').val()=='Pesos') {
                    $('#DesB').show();
                    $('#Des_PorB').hide();
                    $('#Des_PesB').show();
                    $('#Des_sigComB').hide();
                }else if ($('#unidad_descuentoB').val()=='sigCompra') {
                    $('#DesB').show();
                    $('#Des_PorB').hide();
                    $('#Des_PesB').hide();
                    $('#Des_sigComB').show();
                }else {
                    $('#DesB').hide();
                    $('#Des_PorB').hide();
                    $('#Des_PesB').hide();
                    $('#Des_sigComB').hide();
                    $('#descuento_deB').val(0);
                }
            });
            $('#unidad_descuentoC').change(function() {
                if ($('#unidad_descuentoC').val()=='Procentaje'||$('#unidad_descuentoC').val()=='Procentaje1'||$('#unidad_descuentoC').val()=='Procentaje2') {
                    $('#DesC').show();
                    $('#Des_PorC').show();
                    $('#Des_PesC').hide();
                    $('#Des_sigComC').hide();                    
                }else if ($('#unidad_descuentoC').val()=='Pesos') {
                    $('#DesC').show();
                    $('#Des_PorC').hide();
                    $('#Des_PesC').show();
                    $('#Des_sigComC').hide();
                }else if ($('#unidad_descuentoC').val()=='sigCompra') {
                    $('#DesC').show();
                    $('#Des_PorC').hide();
                    $('#Des_PesC').hide();
                    $('#Des_sigComC').show();
                }else {
                    $('#DesC').hide();
                    $('#Des_PorC').hide();
                    $('#Des_PesC').hide();
                    $('#Des_sigComC').hide();
                    $('#descuento_deC').val(0);
                    
                }
            });
        });
</script>
@endsection