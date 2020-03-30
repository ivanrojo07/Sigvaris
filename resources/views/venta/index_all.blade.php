@extends('principal')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@section('content')

<div class="container">

    
    <div class="card">
        <div class="card-header">
            <h4>Historial Ventas</h4>
        </div>
            
        <div class="card-body">
            <div class="row">
                <div class="input-group m-auto col-sm-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon3">Desde</span>              
                        <input type="date" class="form-control" id="desde" aria-describedby="basic-addon3">
                    </div>            
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon3">Hasta</span>
                        <input type="date" class="form-control" id="hasta" aria-describedby="basic-addon3">
                    </div>                
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-sm-2 m-auto">
                    <button class="btn btn-outline-secondary" type="button" id="reporte">Buscar</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Total (sin IVA)</th>
                            <th>Descuento</th>
                            <th>Fecha</th>
                            <th>Operación</th>
                            <th>Damage</th>
                            <th>Cambio físico</th>
                            <th>Damage OOT</th>
                            <th>Devolución</th>
                        </tr>
                    </thead>
                    <tbody id="ventas">
                        @if(!$ventas)
                        <h3>No hay ventas registrados</h3>
                        @else
                        @foreach($ventas as $venta)
                            <tr>
                                <td nowrap>{{$venta->id}}</td>
                                <td nowrap>{{$venta->paciente['nombre']." ".$venta->paciente['paterno']." ".$venta->paciente['materno']}}</td>
                                <td nowrap>${{$venta->subtotal}}</td>
                                @if($venta->descuento)
                                    <td nowrap>{{$venta->descuento->nombre}}</td>
                                @else
                                    <td nowrap></td>
                                @endif
                                <td nowrap>{{$venta->fecha}}</td>
                                <td nowrap>
                                    <div class="row">
                                        <div class="col-auto pr-2">
                                            <a href="{{route('ventas.show', ['venta'=>$venta])}}"
                                                class="btn btn-primary"><i class="fas fa-eye"></i><strong> Ver</strong></a>
                                        </div>
                                    </div>
                                </td>
                                @if(\Carbon\Carbon::parse($venta->fecha)->diffInDays(\Carbon\Carbon::parse(date('Y-m-d')))<31)
                                    <td nowrap>
                                        <div class="row">
                                            <div class="col-auto pr-2">
                                                <a href="{{url('ventas/'.$venta->id.'/damage')}}"
                                                    class="btn btn-primary"><i class="fas fa-eye"></i><strong> Damage</strong></a>
                                            </div>
                                        </div>
                                    </td>
                                     @else
                                      <td nowrap>
                                        <div class="row">
                                            <div class="col-auto pr-2">
                                                
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td nowrap>
                                    <a href="{{route('ventas.cambio-fisico.create',['venta'=>$venta])}}" class="btn btn-warning">Cambio fisico</a>
                                </td>
                                <td nowrap>
                                    <a href="{{route('ventas.damage-oot.create',['venta'=>$venta])}}" class="btn btn-info">Damage OOT</a>
                                </td>
                                <td nowrap>
                                    <a href="{{route('ventas.devoluciones.create',['venta'=>$venta])}}" class="btn btn-info">Devolución</a>
                                </td>
                            </tr>
                        @endforeach
                        {{-- <thead>
                            <tr>
                                <th>Total ventas</th>
                                <th>Total clientes</th>
                                <th>Total $</th>
                            </tr>
                        </thead>
                        <tbody id="resultados">
                            <tr>
                                <td>{{count($ventas)}}</td>
                                <td>{{count($sumatoria_pacientes)}}</td>
                                <td>${{$sumatoria_ventas}}</td>
                            </tr>
                        </tbody>                           --}}
                        @endif
                    </tbody>
                </table>
            </div>

            {{--$ventas->links()--}}
            
            {{--<div class="row m-3">
                <div class="col-sm-9 offset-sm-2">
                    <h3 style="display: none;" id="tituloP">Prendas Vendidas</h3>
                    <table class="table table-hover" id="PrendasVen">
                    </table>
                </div>
            </div>--}}
        </div>
        {{-- @include('venta.rep_clientes') --}}
        {{-- @include('venta.rep_medicos') --}}
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#hventas').DataTable({
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

        $('#medicos').DataTable({
            'language':{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Médicos _START_ al _END_ de un total de _TOTAL_ ",
                "sInfoEmpty":      "Médicos 0 de un total de 0 ",
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

    $('#Checkbox1').click(function(event) {
        $('#Checkbox2').prop('checked', false);
        $('#Checkbox2').prop('value', '');
        if($('#Checkbox1').prop('checked'))
            $('#Checkbox1').prop('value', 'mas');
        else
            $('#Checkbox1').prop('value', '');

    });

    $('#Checkbox2').click(function(event) {
        $('#Checkbox1').prop('checked', false);
        $('#Checkbox1').prop('value', '');
        if($('#Checkbox2').prop('checked'))
            $('#Checkbox2').prop('value', 'menos');
        else
            $('#Checkbox2').prop('value', '');
    });

    $('#reporte').click(function(){        
        $.ajax({
            url:"{{ url('/get_ventas') }}",
            type: "POST",
            data:{
                "_token": "{{CSRF_TOKEN()}}",
                "desde":$('#desde').val(),
                "hasta":$('#hasta').val()
            },
            dataType:"json",
            success:function(res){
                //console.log(res);
                $('#ventas').find("tr").remove();
                var ventas_total=0;
                var total_realizadas=0;
                var total_clientes=[];
                var val=1;
                let tbody = '';
                $.each(res.ventas,function(i,item){
                    //console.log(item.id);
                    val=1;
                    var textapp = "";
                    textapp += "<tr>";
                    textapp +="<td>"+item.id+"</td>";
                    textapp +="<td>"+item.paciente.nombre+` `+ item.paciente.paterno+` `+item.paciente.materno+"</td>";
                    textapp +="<td>"+item.total+"</td>";
                    if (item.descuento!=null) {
                        textapp +="<td>"+item.descuento.descripcion+"</td>";
                    }else{
                        textapp +="<td></td>";
                    }
                    textapp +="<td>"+item.fecha+"</td>";
                    textapp +=`<td> <div class="row"> <div class="col-auto pr-2"> <a href="{{ url('/ventas') }}/`+item.id+`" class="btn btn-primary"><i class="fas fa-eye"></i><strong> Ver</strong></a> </div>   </div></td>`;
                    if ((new Date()).setDate((new Date()).getDate() + 15)> new Date(item.fecha)) {
                        textapp +=`<td nowrap>
                            <div class="row">
                                <div class="col-auto pr-2">
                                    <a href="{{url('ventas/')}}`+item->id+`'/damage"
                                        class="btn btn-primary"><i class="fas fa-eye"></i><strong> Damage</strong></a>
                                </div>
                            </div>
                        </td>`;
                    }else{
                        textapp +="<td></td>";
                    }
                    
                    textapp +="<td></td>";
                    textapp +="<td></td>";
                    textapp +="<td></td>";
                    textapp +="</tr>";  
                    $('#ventas').append(textapp);    
                    /*$('#ventas').append(`
                    <tr>
                        <td>`+item.id+`</td>
                        <td>`+item.paciente.nombre+` `+ item.paciente.paterno+` `+item.paciente.materno+`</td>
                        <td>$`+item.subtotal+`</td>
                        <td>`+(item.descuento_id?item.descuento.nombre:``)+`</td>
                        <td>`+item.fecha+`</td>
                        <td>
                            <div class="row">
                                <div class="col-auto pr-2">
                                    <a href="{{ url('/ventas') }}/`+item.id+`"
                                        class="btn btn-primary"><i class="fas fa-eye"></i><strong> Ver</strong></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    `); */
                    ventas_total+=parseFloat(item.total);
                    total_realizadas++;
                    $.each(total_clientes,function(e,element){
                        if(element==item.paciente_id)
                        {
                            val=0;
                        }
                    });
                    if(val)
                    {
                        total_clientes.push(item.paciente_id);
                    }
                });
                $('#resultados').append(`
                    <tr>
                        <td>`+total_realizadas+`</td>
                        <td>`+total_clientes.length+`</td>
                        <td>$`+ventas_total+`</td>
                    </tr>
                    `);
                /**$.each(res.consulta, function(index, el) {
                    tbody += `<tr>
                        <td>${el[0].sku}</td>
                        <td>${el[0].descripcion}</td>
                        <td>${el[1]}</td>
                    </tr>`;
                });**/
                let tabla = `<thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                        <tbody>` + tbody + `</tbody>`;
                //$('#PrendasVen').text('');
                //$('#PrendasVen').append(tabla);
                
                $('#tituloP').prop('style', '');
            },
            error:function(error){
                console.log("error");
            }
        });
    });

    $('#reporteClientes').click(function(event) {
        let tipo = $('input:radio[name=tipoCliente]:checked').val();
        $.ajax({
            url:"{{ url('/get-ventas-clientes') }}",
            type: "POST",
            data:{
                "_token": "{{CSRF_TOKEN()}}",
                "desde":$('#desdeC').val(),
                "hasta":$('#hastaC').val(),
                "tipo": tipo,
            },
            dataType:"json",
            success:function(res){
                console.log(res);
                let cuerpo = '';
                let totales = `<thead>
                                <tr>
                                    <th>Total ventas</th>
                                    <th>Total clientes</th>
                                    <th>Total $</th>
                                </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>${res.ventas.length}</td>
                                <td>${res.suma_pacientes}</td>
                                <td>$${res.total}</td>
                               </tr>
                            </tbody>`;
                $.each(res.ventas,function(index, el) {
                    cuerpo += `<tr>
                                    <td>${el.venta.id}</td>
                                    <td>${el.venta.paciente.nombre} ${el.venta.paciente.paterno} ${el.venta.paciente.materno}</td>
                                    <td>${el.venta.fecha}</td>
                                    <td>${el.venta.subtotal}</td>
                                    <td>${el.venta.total}</td>
                                    <td>${el.cantidad}</td>
                            </tr>`;
                });
                $('#clientes').empty();
                $('#clientes').append(cuerpo);
                $('#clientes').append(totales);
                console.log( $('#clientes'));
            },
            error:function(error){
                console.log("error");
            }
        });
    });
</script>
@endsection