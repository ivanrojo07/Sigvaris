@extends('principal')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@section('content')
{{-- {{ dd($productos) }} --}}


<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        {{$errors->first()}}
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            {{-- CABECERA DE LA SECCIÓN --}}
            <div class="row">
                <div class="col-4">
                    <h4>Punto de venta</h4>
                </div>
                <div class="col-4 text-center">
                    <a href="{{ route('ventas.index') }}" class="btn btn-primary">
                        <i class="fa fa-bars"></i><strong>Lista de ventas</strong>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card-body">
                <form role="form" id="form-cliente" method="POST" action="{{ route('ventas.store') }}" name="form">
                    {{ csrf_field() }}
                    <input type="hidden" name="oficina_id" value="{{session('oficina')}}">
                    <input type="hidden" name="cumpleDes" id="cumpleDes" value="0">
                    <div class="row">
                        <div class="col-4 form-group">
                            <label class="control-label">Fitter:</label>
                            @if (Auth::user()->id == 1 || Auth::user()->empleado->puesto->nombre != "fitter")
                            <select name="empleado_id" id="empleado_id" class="form-control" required>
                                <option value="">Seleccionar</option>
                                @foreach ($empleadosFitter as $empleadoFitter)
                                <option value="{{$empleadoFitter->id}}">
                                    {{$empleadoFitter->nombre}} {{$empleadoFitter->appaterno}}
                                    {{$empleadoFitter->apmaterno}}
                                </option>
                                @endforeach
                            </select>
                            @else
                            <input type="text" class="form-control" id="empleado_id" required readonly
                                value="{{Auth::user()->empleado->id}}" style="display: none;">
                            <input type="text" class="form-control" required readonly
                                value=" {{Auth::user()->empleado->nombre}} {{Auth::user()->empleado->appaterno}} {{Auth::user()->empleado->apmaterno}}">
                            @endif
                        </div>
                    </div>

                    <hr>

                    {{-- TABLA DE PACIENTES --}}
                    @if (!isset($paciente))
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-header rounded-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <h3>Pacientes</h3>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label>Buscar:<input type="search" id="BuscarPaciente" onkeypress="return event.keyCode!=13">
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



                    {{-- DETALLES DE LA COMPRA --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-header">
                                    <h3>Detalles de la compra</h3>
                                </div>
                                {{-- TABLA DE PRODUCTOS SELECCIONADOS --}}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Cantidad</th>
                                                        <th>Producto</th>
                                                        <th>Precio Unitario</th>
                                                        <th>Precio Unitario + IVA</th>
                                                        <th>Subtotal</th>
                                                        <th>Quitar</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_productos">
                                                    {{-- <div id="tbody_productos"></div> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>

                                    {{-- PROMOCIONES Y DESCUENTOS --}}
                                    <div class="row"id="PromocionDescuento" >
                                        {{-- INPUT DESCUENTO --}}
                                        <div class="col-12 col-sm-6 col-md-4 form-group">
                                            <label for="descuento_id"
                                                class="text-uppercase text-muted">Descuento</label>
                                            <select class="form-control" name="descuento_id" id="descuento_id">
                                                <option value="">Selecciona...</option>
                                                @foreach ($descuentos as $descuento)
                                                <option value="{{$descuento->id}}">{{$descuento->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- INPUT PROMOCIÓN --}}
                                        <div class="col-12 col-sm-6 col-md-4 form-group">
                                            <label for="promocion_id"
                                                class="text-uppercase text-muted">Descripción</label>
                                            <select class="form-control" name="promocion_id" id="promocion_id">
                                                <option value="">Selecciona...</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4 form-group">

                                            <label for="" class="text-uppercase text-muted">Sigpesos a usar: </label>

                                            <input type="number" class="form-control" name="sigpesos_usar"
                                                id="sigpesos_usar" value="0" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 text-center">
                                            <div class="alert alert-danger" id="ErrorInapam" style="display: none;">
                                                El INAPAM no esta cargado 
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="INAPAM">
                                                <label class="form-check-label" for="exampleCheck1">INAPAM</label>
                                            </div>
                                       </div>
                                    </div>
                                    
                                    {{-- Pagos Y tarjeta --}}
                                    <div class="row">
                                        {{-- INPUT Tipo de pago --}}
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <label for="tipoPago" class="text-uppercase text-muted">Tipo de pago</label>
                                            <select class="form-control" name="tipoPago" id="tipoPago">
                                                <option value="0">Selecciona...</option>
                                                <option value="1">Efectivo</option>
                                                <option value="2">Tajeta</option>
                                                <option value="3">Combinado</option>
                                                <option value="4">Sigpesos</option>
                                            </select>
                                        </div>
                                        {{-- INPUT tarjeta --}}

                                        <div id="tar1" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="banco" class="text-uppercase text-muted">Banco</label>
                                            <select class="form-control" name="banco" id="banco">
                                                <option value="">Selecciona...</option>
                                                <option value="SANTANDER">Banco</option>
                                                <option value="AMEX">Amex</option>
                                            </select>
                                        </div>
                                        {{-- INPUT numeros de  tarjeta --}}
                                        <div id="tar2" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Ultimos 4 digitos de
                                                tarjeta</label>
                                            <input type="text" class="form-control" id="digitos_targeta"
                                                name="digitos_targeta">
                                        </div>

                                    </div>
                                    {{-- P --}}
                                    <div class="row">
                                        {{-- INPUT numeros de  tarjeta --}}
                                        <div id="tar4" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Monto de pago en
                                                efectivo</label>
                                            <input type="text" class="form-control" id="PagoEfectivo"
                                                name="PagoEfectivo">
                                        </div>
                                        <div id="tar5" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Monto de pago con
                                                tarjeta</label>
                                            <input type="text" class="form-control" id="PagoTarjeta" name="PagoTarjeta">
                                        </div>
                                        <div id="tar10" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="banco" class="text-uppercase text-muted">Pago a meses</label>
                                            <select class="form-control" name="mesesPago" id="banco">
                                                <option value="0">Selecciona...</option>
                                                <option value="3">3 meses</option>
                                                <option value="6">6 meses</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="paciente_id" id="paciente_id" required>
                                    <div class="row">
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Paciente: </label>
                                            <input type="text" class="form-control" id="inputNombrePaciente" required
                                                readonly>
                                        </div>
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Fecha: </label>
                                            <input type="date" name="fecha" class="form-control" readonly=""
                                                value="{{date('Y-m-d')}}" required="">
                                        </div>
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Folio: </label>
                                            <input type="number" name="precio" class="form-control" readonly=""
                                                value="{{$folio}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- INPUT SIGPESOS GANADOS --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Sigpesos ganados: </label>

                                            <input type="number" class="form-control" name="sigpesos" id="sigpesos"
                                                value="0" min="0" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT SIGPESOS A USAR --}}

                                        {{-- INPUT SUBTOTAL --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Subtotal: $</label>

                                            <input type="number" required="" class="form-control" name="subtotal"
                                                id="subtotal" value="0" min="1" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT DESCUENTO --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Descuento: $</label>

                                            <input type="number" required="" class="form-control" name="descuento"
                                                id="descuento" value="0" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT IVA --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Iva: $</label>

                                            <input type="number" required="" class="form-control" name="iva" id="iva"
                                                value="0" min="1" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT TOTAL --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Total: $ </label>

                                            <input type="number" required="" class="form-control" name="total"
                                                id="total" value="0" min="1" step="0.01" readonly>
                                        </div>
                                        {{-- INPUT DESCUENTO --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Descuento de cumpleaños: $</label>

                                            <input type="number" required="" class="form-control" name="descuentoCum"
                                                id="descuentoCumple" value="0" step="0.01" readonly="">
                                        </div>
                                    </div>
                                    {{-- Comentario --}}
                                    <div class="row">
                                        
                                        <div  class="col-12 col-sm-12 col-md-12 form-group">
                                            <label for="" class="text-uppercase text-muted">Comentario</label>
                                            <input type="text" class="form-control" id="comentario"
                                                name="comentario">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-12">
                            <p>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1"
                                        data-toggle="collapse" href="#collapseExample" role="button"
                                        aria-expanded="false" aria-controls="collapseExample" name="facturar" value="1">
                                    <label class="custom-control-label" for="customCheck1">FACTURAR</label>
                                </div>
                            </p>
                            <div class="collapse" id="collapseExample">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">TIPO PERSONA</label>
                                            <input type="text" class="form-control" id="tipoPersona" name="tipo_persona">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">NOMBRE / RAZÓN
                                                SOCIAL</label>
                                            <input type="text" class="form-control" id="nombreORazonSocial" name="nombre_o_razon_social">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">RÉGIMEN FISCAL
                                                SOCIAL</label>
                                            <input type="text" class="form-control" id="regimeFiscal" name="regimen_fiscal">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">CORREO</label>
                                            <input type="text" class="form-control" id="correo" name="correo">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">RFC</label>
                                            <input type="text" class="form-control" id="rfc" name="rfc">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">CALLE</label>
                                            <input type="text" class="form-control" id="calle" name="calle">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">NUM. EXT</label>
                                            <input type="text" class="form-control" id="num_ext" name="num_ext">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">NUM. INT</label>
                                            <input type="text" class="form-control" id="num_int" name="num_int">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">CP</label>
                                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">Ciudad</label>
                                            <input type="text" class="form-control" id="ciudad" name="ciudad">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">Delegación o municipio</label>
                                            <input type="text" class="form-control" id="alcaldia_o_municipio" name="alcaldia_o_municipio">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">Uso cfdi</label>
                                            <select name="uso_cfdi" class="form-control" id="uso_cfdi">
                                                <option value="">Seleccionar</option>
                                                <option value="D01 - Honorarios médicos, dentales y gastos hospitalarios">D01 - Honorarios médicos, dentales y gastos hospitalarios</option>
                                                <option value="D02 - Gastos médicos por incapacidad o discapacidad">D02 - Gastos médicos por incapacidad o discapacidad</option>
                                                <option value="D03 - Gastos funerales">D03 - Gastos funerales</option>
                                                <option value="D04 - Donativos">D04 - Donativos</option>
                                                <option value="D05 - Interéses reales efectivamente pagados por créditos hipotecarios (casa habitación)">D05 - Interéses reales efectivamente pagados por créditos hipotecarios (casa habitación)</option>
                                                <option value="D06 - Aportaciones voluntarias al SAR">D06 - Aportaciones voluntarias al SAR</option>
                                                <option value="D08 - Gastos de transportación escolar obligatoria">D08 - Gastos de transportación escolar obligatoria</option>
                                                <option value="D09 - Depositos en cuentas para el ahorro, primas que tengan como base planes de pensión">D09 - Depositos en cuentas para el ahorro, primas que tengan como base planes de pensión</option>
                                                <option value="D10 - Pagos por servicios educativos (colegiaturas)">D10 - Pagos por servicios educativos (colegiaturas)</option>
                                                <option value="G01 - Adquisición de mercancias">G01 - Adquisición de mercancias</option>
                                                <option value="G02 - Devoluciones, descuentos o bonificaciones">G02 - Devoluciones, descuentos o bonificaciones</option>
                                                <option value="G03 - Gastos en general">G03 - Gastos en general</option>
                                                <option value="I01 - Construcciones">I01 - Construcciones</option>
                                                <option value="I02 - Moviliario y equipo de oficina por inversiones">I02 - Moviliario y equipo de oficina por inversiones</option>
                                                <option value="I03 - Equipo de transporte">I03 - Equipo de transporte</option>
                                                <option value="I04 - Equipo de cómputo y accesorios">I04 - Equipo de cómputo y accesorios</option>
                                                <option value="I05 - Dados, troqueles, moldes, matrices y herramental">I05 - Dados, troqueles, moldes, matrices y herramental</option>
                                                <option value="I06 - Comunicaciones telefónicas">I06 - Comunicaciones telefónicas</option>
                                                <option value="I07 - Comunicaciones satelitales">I07 - Comunicaciones satelitales</option>
                                                <option value="I08 - Otra maquinaria y equipo">I08 - Otra maquinaria y equipo</option>
                                                <option value="P01 - Por definir">P01 - Por definir</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </form>
                    {{-- BOTON GUARDAR --}}
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success rounded-0" onclick="javascript:sendFormValidador();">
                                <i class="fa fa-check"></i> Finalizar comprar
                            </button>
                        </div>
                    </div>

            </div>



            <div class="card-footer">
                <div class="row">
                    <div class="col-4 text-right text-danger">
                        ✱Campos Requeridos.
                    </div>
                </div>
            </div>

            
            <div class="col-4 offset-4 text-center">
                {{--                 <form action="{{ route('pembayaran.print') }}" method="POST">
                <input type="hidden" name="_token" class="form-control" value="{!! csrf_token() !!}"> --}}
                {{-- <button type="submit" name="submit" class="btn btn-info">Imprimir</button> --}}
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function sendFormValidador() {
        console.log("empleado",$('#empleado_id').val());
    if ($('#empleado_id').val()!="") {
    if ($('#total').val()==($('#PagoTarjeta').val()+$('#PagoEfectivo').val())) {
        document.getElementById("form-cliente").submit();
      } else {
        alert("Valida los campos de forma de pago");
        return false;
      }

    }else{
        alert("Valida el campo de empleado");
        return false;
    }
      
    }

    function on(){
         $('#descuento').val(0);
         $('#descuento').val(parseFloat(parseFloat($('#subtotal').val())*.05).toFixed(2));
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        var getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        
        // console.log(des);
        console.log('SUBTOTAL', subtotal);
        console.log('iva', iva);
        console.log('des', des);
        console.log('sigpesos', sigpesos);  
        console.log('TOTAL ACTUALIZADO',subtotal+iva-des-sigpesos-desCumple);
        var aux=subtotal+iva-des-sigpesos-desCumple;
        $('#total').val(aux.toFixed(2));
        
        $('#PromocionDescuento').hide();

    }

    function off(){
        $('#descuento').val(0);
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        var getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        // console.log(des);
        console.log('SUBTOTAL', subtotal);
        console.log('iva', iva);
        console.log('des', des);
        console.log('sigpesos', sigpesos);  
        console.log('TOTAL ACTUALIZADO',subtotal+iva-des-sigpesos-desCumple);
        var aux=subtotal+iva-des-sigpesos-desCumple;
        if (aux.toFixed(2)!=$('#total').val()) {
            $('#total').val(aux.toFixed(2));
        }
        
        $('#PromocionDescuento').show();
    }

    var checkbox = document.getElementById('INAPAM');

    checkbox.addEventListener("change", comprueba, false);

    function comprueba(){
        var pacienteId=$('#paciente_id').val();
        if(checkbox.checked){
            $.ajax({
                url:`{{ url('/api/pacientes/${pacienteId}/inapam') }}`,
                type: 'GET',
                success: function(inapam){
                    if (inapam=="1") {
                        var opcion = confirm("Se cargar la INAPAM despues ");
                        if (opcion == true) {
                            on();
                        } else {
                           $("#INAPAM").prop("checked", false); 
                           $('#PromocionDescuento').show();
                        }
                    }else{
                        on();
                    }
                }
            });
            
            
        }else{
            off();
        }
    }

</script>
<script>
    
    function agregarProducto(p){
        let producto = JSON.parse($(p).val());
        // alert(producto);
        if (producto.stock>0) {
            $('#tbody_productos')
        .append(`
        <tr id="producto_agregado${producto.id}">
            <td>

                <input class="form-control cantidad" min="1" onchange="cambiarTotal(this, '#producto_agregado${producto.id}')" type="number" name="cantidad[]" value="1" stock="${producto.stock}" iva=${producto.precio_publico_iva}>
                <input class="form-control" type="hidden" name="producto_id[]" value="${producto.id}" iva=${producto.precio_publico_iva}>

            </td>
            <td>
                ${producto.descripcion}
            </td>
            <td class="precio_individual">
                ${producto.precio_publico}
            </td>
            <td class="precio_individual_iva">${producto.precio_publico_iva}</td>
            <td class="precio_total">
                ${producto.precio_publico}
            </td>
            <td>
                <button onclick="quitarProducto('#producto_agregado${producto.id}')" type="button" class="btn btn-danger boton_quitar">
                    <i class="fas fa-minus"></i>
                </button>
            </td>
        </tr>`);
        cambiarTotalVenta();
        $('#BuscarProducto').val("");
    }else{
        alert('Producto sin stock');
    }
        
    }

    function quitarProducto(p){
        $(p).remove();
        cambiarTotalVenta();
    }

    function cambiarTotalVenta(){
        let precios_total = $('td.precio_total').toArray();
        let total = 0;
        precios_total.forEach(e => {
            total += parseFloat(e.innerText);
            console.log(total);
        });
        $('#promocion_id option:eq(0)').prop('selected',true);
        $('#descuento').val(0);
        $('#PromocionDescuento').show();
        $("#INAPAM").prop("checked", false);
        $('#sigpesos').val(0);
        $('#subtotal').val(total.toFixed(2));
        let getIva = ($('#subtotal').val()*0.16);
        $('#iva').val(getIva.toFixed(2));
        //console.log(getIva.toFixed(2));
        if ($('#sigpesos_usar').val()==null) {
             $.ajax({
                url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
                type:'GET',
                dataType:'json',
                success: function(res){
                   if (!isNaN(res.sigpesos)&&res.sigpesos!="") {
                        var sigpesos=$('#sigpesos_usar').val(parseInt(res.sigpesos));
                        console.log('sigpesos peticione4444',res.sigpesos);
                    }else{             
                        res.sigpesos=0;       
                        var sigpesos=$('#sigpesos_usar').val(parseInt(res.sigpesos));
                        console.log('sigpesos peticion5555',res.sigpesos);
                    }   
                    
                    $('#descuentoCumple').val(parseInt(res.cumple));
                    if (res.cumple>0) {
                        $('#cumpleDes').val(1);
                    }
                }
            });
             console.log('sigpesos3rff', sigpesos);
        }
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());

        getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        // console.log(des);
        console.log('SUBTOTAL', subtotal);
        console.log('iva', iva);
        console.log('des', des);
        console.log('sigpesos', sigpesos);  
        console.log('TOTAL ACTUALIZADO',subtotal+iva-des-sigpesos-desCumple);
        var aux=subtotal+iva-des-sigpesos-desCumple;
        $('#total').val(aux.toFixed(2));
        // $('#total').val('ola');
    }

    function cambiarTotal(a, p){

        let cant = parseFloat(a.value);
        if (a.getAttribute("stock")>cant) {
            let cantiva = parseFloat(a.getAttribute("iva"));
            let ind = parseFloat($(p).find('.precio_individual').first().text());
            let total = cant*ind;
            let totaliva = cantiva*cant;
            console.log('----------',ind);
            $(p).find('.precio_total').text(total);
            $(p).find('.precio_individual_iva').text(parseFloat(totaliva).toFixed(2));
            cambiarTotalVenta();
        }else{
        alert('Producto sin stock necesario');
    }
        
    }

    $(document).ready(function () {
        $('#sigpesos_usar').change(function(){
            var subtotal=parseFloat($('#subtotal').val());
            var des=parseFloat($('#descuento').val());
            var sigpesos=parseInt($('#sigpesos_usar').val());
            var desCumple=parseFloat($('#descuentoCumple').val());
            let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
            var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
            var aux=subtotal+iva-des-sigpesos-desCumple;

            $('#total').val(aux.toFixed(2));
            console.log('TOTAL ACTUALIZADO',$('#total').val());
         });

        $('#tipoPago').change(function(){  
            console.log('Entra');
            if ($('#tipoPago').val()==2){
                $('#tar1').show();
                $('#tar2').show();
                $('#tar5').show();
                $('#tar10').show();
                $('#tar4').hide();
                $('#digitos_targeta').required;

            }else if ($('#tipoPago').val()==3) {
                $('#tar1').show();
                $('#tar2').show();
                $('#tar4').show();
                $('#tar5').show();
                $('#tar10').show();
                
                $('#digitos_targeta').required;
            }else if ($('#tipoPago').val()==1) {
               $('#banco').val(null);
                $('#digitos_targeta').val(null);
                $('#tar1').hide();
                $('#tar2').hide();
                $('#tar4').show();
                $('#tar5').hide();
                $('#tar10').hide();
            }else{
                $('#banco').val(null);
                $('#digitos_targeta').val(null);
                $('#tar1').hide();
                $('#tar2').hide();
                $('#tar4').hide();
                $('#tar5').hide();
                $('#tar10').hide();
            }

        });
        /*$('#pacientes').DataTable({
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
        });*/
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
      

        $('#descuento_id').change(function(){            
            var id=$('#descuento_id').val();
            $('#descuento').val(0);
            $("#INAPAM").prop("checked", false);
            $('#sigpesos').val(0);
            var subtotal=parseFloat($('#subtotal').val());
            var des=parseFloat($('#descuento').val());
            var sigpesos=parseInt($('#sigpesos_usar').val());
            var desCumple=parseFloat($('#descuentoCumple').val());
            let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
            var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
            var aux=subtotal+iva-des-sigpesos-desCumple;
            $('#total').val(aux.toFixed(2));
            $.ajax({
                url:"{{ url('/get_promos') }}/"+id,
                type:'GET',
                dataType:'html',
                success: function(res){
                    $('#promocion_id').html(res);

                    $('#sigpesos_usar').prop("disabled", false);
                }
            });
        });

        $('#promocion_id').change(function(){
            var id=$('#promocion_id').val();

            // SI NO HAY PROMOCION QUITAMOS EL DESCUENTO
            if(!id)
            {
                $('#descuento').val(0);
                $('#sigpesos').val(0);
            }

            // OBTENEMOS DATOS DE LA COMPRA
            var paciente_id=$('#paciente_id').val();
            var total_productos=parseInt(0);
            var subtotal=parseFloat($('#subtotal').val());
            var des=parseFloat($('#descuento').val());
            var sigpesos=parseInt($('#sigpesos_usar').val());
            var desCumple=parseFloat($('#descuentoCumple').val());
            let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
            var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
            var aux=subtotal+iva-des-sigpesos-desCumple;
            $('#total').val(aux.toFixed(2));
            var productos_id=[];
            var cantidad_id=[];

            // OBTENEMOS LA SUMA DE TODAS LAS CANTIDADES DE TODOS PRODUCTOS
            $('[name="cantidad[]"]').each(function(){
                total_productos+=parseInt($(this).val());
                cantidad_id.push($(this).val());
            });

            // OBTENEMOS EL ID DE LOS PRODUCTOS DE LA POSIBLE COMPRA
            $('[name="producto_id[]"]').each(function(){
                productos_id.push($(this).val());
            }); 
            $.ajax({
                url:"{{ url('/calcular_descuento') }}/"+id,
                type:'POST',
                data: {"_token": "{{CSRF_TOKEN()}}",
                    "subtotal":$("#subtotal").val(),
                    "paciente_id":paciente_id,
                    "total_productos":total_productos,
                    "productos_id":productos_id,
                    "cantidad_id":cantidad_id
                },
                dataType:'json',
                success: function(res){
                    //alert(res.sigpesos);                  
                    if(res.status){
                        if (res.status==1) {                       
                            $('#descuento').val(res.total);
                            $('#sigpesos').val(res.sigpesos);
                            des=parseFloat($('#descuento').val());
                            let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                            var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                            var aux=subtotal+iva-des-sigpesos-desCumple;
                            $('#total').val(aux.toFixed(2));
                            if($('#total').val()<0)
                            {
                                $('#total').val(0);
                            }
                        }else{
                            $('#descuento').val(res.total);
                            $('#sigpesos').val(res.sigpesos);
                            des=parseFloat($('#descuento').val());
                            let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                            var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                            var aux=subtotal+iva-des-sigpesos-desCumple;
                            $('#total').val(aux.toFixed(2));
                            if($('#total').val()<0)
                            {
                                $('#total').val(0);
                            }
                        }

                        if (res.aceptsp==0) {
                            $('#sigpesos_usar').val(0);
                            $('#sigpesos_usar').prop("disabled", true);
                            
                        }else if (res.aceptsp==1) {
                            $('#sigpesos_usar').prop("disabled", false);
                        }
                        //$('#total').val()
                    }
                    else
                    {
                        swal("No aplica el descuento");
                        $('#promocion_id option:eq(0)').prop('selected',true);
                        $('#sigpesos_usar').prop("disabled", false);
                    }
                },
                error: function(e){
                    alert('No se aplicó la promoción');
                    console.log(e);
                    $('#sigpesos_usar').prop("disabled", false);
                }

            });
        });
       
        $('#paciente_id').change( async function(){
            var pacienteId=$(this).val();
            
            
        });
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
        /*$('#BuscarPaciente').change( function() {
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
        });*/
        $("#BuscarProducto").on('keyup', function (e) {
          var keycode = e.keyCode || e.which;
            if (keycode == 13) {
                $("#productos").dataTable().fnDestroy();
                //console.log($(this).val());
                $('#productos').DataTable({
                    "ajax":{
                        type: "POST",
                        url:"getProductos_nombre",
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
        /*$('#BuscarProducto').change( function() {
            $("#productos").dataTable().fnDestroy();
           //console.log($(this).val());
            $('#productos').DataTable({
                "ajax":{
                    type: "POST",
                    url:"getProductos_nombre",
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
        });*/
    });

   @if(!isset($paciente))
    $(document).on('click', '.botonSeleccionCliente', async function(){
        
                
        const pacienteId = $(this).attr('pacienteid');

        $.ajax({
            url:`{{ url('/api/pacientes/${pacienteId}/datos_fiscales') }}`,
            type: 'GET',
            success: function(datos_fiscales){
                console.log({
                    pacienteId,
                    datos_fiscales
                });
                $('#tipoPersona').val(datos_fiscales.tipo_persona);
                $('#nombreORazonSocial').val(datos_fiscales.nombre_o_razon_social);
                $('#regimeFiscal').val(datos_fiscales.regimen_fiscal);
                $('#correo').val(datos_fiscales.correo);
                $('#rfc').val(datos_fiscales.rfc);
                $('#calle').val(datos_fiscales.calle);
                $('#num_ext').val(datos_fiscales.num_ext);
                $('#num_int').val(datos_fiscales.num_int);
                $('#codigo_postal').val(datos_fiscales.codigo_postal);
                $('#ciudad').val(datos_fiscales.ciudad);
                $('#alcaldia_o_municipio').val(datos_fiscales.alcaldia_o_municipio);
                $('#uso_cfdi').val(datos_fiscales.uso_cfdi);
            }
        });

        $.ajax({
            url:`{{ url('/api/pacientes/${pacienteId}/inapam') }}`,
            type: 'GET',
            success: function(inapam){
                if (inapam=="1") {
                    $('#ErrorInapam').show();
                }else{
                    $('#ErrorInapam').hide();
                }                
            }
        });
        const nombrePaciente = $(`.nombrePaciente[pacienteId=${pacienteId}]`).html();
        const apellidosPaciente = $(`.apellidosPaciente[pacienteId=${pacienteId}]`).html();

        console.log('datosPAciente: ',nombrePaciente,apellidosPaciente);

        $('#inputNombrePaciente').val( nombrePaciente + " " + apellidosPaciente );

        $('#paciente_id').val(pacienteId);
        console.log( 'Cliente seleccionado: ', pacienteId );
        $('#promocion_id option:eq(0)').prop('selected',true);
        $('#descuento').val(0);
        $('#sigpesos').val(0);
        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        await $.ajax({
            url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
            type:'GET',
            dataType:'json',
            success: function(res){
                 console.log('sigpesos peticion198711',res);
                 console.log('sigpesos peticion198712',res.cumple);

                if (!isNaN(res.sigpesos)&&res.sigpesos!="") {
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res.sigpesos));
                    console.log('sigpesos peticion00',res);
                }else{             
                    res.sigpesos=0;       
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res.sigpesos));
                    console.log('sigpesos peticion111',res.sigpesos);
                }
                console.log('sigpesos peticion198712',res.cumple);
                $('#descuentoCumple').val(parseInt(res.cumple));
                if (res.cumple>0) {
                        $('#cumpleDes').val(1);
                    }
                var desCumple=parseFloat($('#descuentoCumple').val());
                let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                var iva=parseFloat($('#iva').val(getIva.toFixed(2)));




            }

        });
        
        if((subtotal+iva-des-desCumple)<$('#sigpesos_usar').val())
        {
            $('#total').val(0);
        }
        else
        {
            var aux=subtotal+iva-des-desCumple-$('#sigpesos_usar').val();
            $('#total').val(aux.toFixed(2));
            console.log('total',$('#sigpesos_usar').val())
        }
    });
   @endif
    $(document).on('change', '#paciente_id', function(){
        const pacienteId = $(this).val();
        console.log('aqui');
    });

</script>
@if(isset($paciente))

<script type="text/javascript">
    $(document).ready(function(){
            <?php
            if ($paciente->expediente()->first()!=null) {
                if ($paciente->expediente()->first()->inapam==null) {
                    echo "$('#ErrorInapam').show();";
                }
            } else {
                echo "$('#ErrorInapam').show();";
            }
            //dd($paciente->expediente()->first()->inapam);
            ?>
        
        const pacienteId = {{$paciente->id}};

        const nombrePaciente = "{{ $paciente->nombre }}";
        const apellidosPaciente = "{{ $paciente->paterno.' '.$paciente->materno }}";

        console.log('datosPAciente: ',nombrePaciente,apellidosPaciente);

        $('#inputNombrePaciente').val( nombrePaciente + " " + apellidosPaciente );

        $('#paciente_id').val(pacienteId);
        console.log( 'Cliente seleccionado: ', pacienteId );
        $('#promocion_id option:eq(0)').prop('selected',true);
        $('#descuento').val(0);
        $('#sigpesos').val(0);
        var subtotal=parseFloat($('#subtotal').val());
        //var iva=parseFloat($('#iva').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
         $.ajax({
            url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
            type:'GET',
            dataType:'json',
            success: function(res34){   
                if (!isNaN(res34.sigpesos)&&res34.sigpesos!="") {
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res34.sigpesos));
                    console.log('sigpesos peticion00',res34.sigpesos);
                }else{             
                    res34=0;       
                    var sigpesos=$('#sigpesos_usar').val(0);
                    console.log('sigpesos peticion1199',0);
                }
                $('#descuentoCumple').val(parseInt(res34.cumple));
                if (res34.cumple>0) {
                    $('#cumpleDes').val(1);
                }
                var desCumple=parseFloat($('#descuentoCumple').val());
                let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                var iva=parseFloat($('#iva').val(getIva.toFixed(2)));

            }

        });
        if((subtotal+iva-des-desCumple)<$('#sigpesos_usar').val())
        {
            $('#total').val(0);
        }
        else
        {
            var aux=subtotal+iva-des-desCumple-$('#sigpesos_usar').val();
            $('#total').val(aux.toFixed(2));
            console.log('total',$('#sigpesos_usar').val())
        }
    });

   

</script>
@endif
@endsection