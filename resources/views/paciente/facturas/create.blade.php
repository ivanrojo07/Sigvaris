@extends('principal')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('facturas.index') }}" class="btn btn-primary rounded-0">
                <i class="fa fa-bars"></i><strong> Lista de facturas</strong>
            </a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label for="">📅 FECHA</label>
                            <input type="date" name="fecha" class="form-control" id="fecha">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{route('facturas.download')}}" method="POST" class="form-inline float-right">
                                @csrf
                                <input type="date" name="fecha" id="fechaFactura" style="display:none">
                                <button type="submit" class="btn btn-primary rounded-0">FACTURA</button>
                            </form>
                            <form action="{{route('datos_fiscales.download')}}" method="POST" class="form-inline">
                                @csrf
                                <input type="date" name="fecha" id="fechaDatosFiscales" style="display:none">
                                <button type="submit" class="btn btn-primary rounded-0">DATOS FISCALES</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript">
    $(document).on('change','#fecha',function(){
        $('#fechaFactura').val( $(this).val() );
        $('#fechaDatosFiscales').val( $(this).val() );
    });

    $(document).ready(function(){
        $('#paciente_id').change(function() {
                var id = $('#paciente_id').val();                
                //alert(id);
                $.ajax({
                    url: "{{ url('/ventas_from') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function(res){
                        $('#div_ventas').show();
                        $('#venta_id').html(res);
                    },
                    error: function (){
                        //$('#estados').html('');
                    }
                });

                $.ajax({
                    url:"{{url('/get_paciente')}}/"+id,
                    type:"GET",
                    dataType: "json",
                    success:function(res){
                        var paciente=res.paciente;
                        $('#nombre').prop('value',paciente.nombre+' '+paciente.paterno+' '+paciente.materno);
                        $('#correo').prop('value',paciente.mail);
                        $('#rfc').prop('value',paciente.rfc);
                    }
                });
            });

        $('#venta_id').change(function(){
            $('#formulario').show();
        });
    });
</script>

@endsection