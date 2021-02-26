@extends('principal')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{route('devolucion.cargarDevolucion')}}" method="POST" id="miForm" name="fvalida"  onsubmit="return validar()">
                @csrf
                <input type="hidden" name="venta_id" id="venta_id" value="{{$venta->id}}">
            <div class="row">
                <input type="hidden" name="sigpesos_d" id="sigpesos_d" value="{{$sigpesos_d}}">
            <div class="row">
                <input type="hidden" name="saldo_d" id="saldo_d" value="{{$saldo_d}}">
            <div class="row">

                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">Paciente: </label>
                    <input type="text" class="form-control" id="nombre" value="{{$venta->paciente->nombre." ".$venta->paciente->materno." ".$venta->paciente->paterno}}" required readonly>
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">Monto: </label>
                    <input type="text" class="form-control" id="MONTO" name="MONTO" value="{{$MONTO}}" required readonly>
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">Fecha: </label>
                    <input type="text" class="form-control" id="fecha" name="fecha" value="{{date('Y-m-d')}}" required readonly>
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">NUMERO DE CUENTA: </label>
                    <input type="text" class="form-control" id="cuenta" name="cuenta" value="" required >
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">BENEFICIARIO: </label>
                    <input type="text" class="form-control" id="beneficiario" name="beneficiario" required >
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">REFERENCIA: </label>
                    <input type="text" class="form-control" id="referencia" name="referencia" required >
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">CLABE INTERBANCARIA: </label>
        <input type="number" class="form-control" id="clave" name="clave" value="" required maxlength="18" minlength="17">
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">BANCO: </label>
                    <input type="text" class="form-control" id="banco" name="banco" value="" required >
                </div>
                <div class="col-12">
                        <button type="submit" class="btn btn-success rounded-0" >
                            <i class="fa fa-check"></i> Finalizar 
                        </button>
                    </div>
            </div>

           
            </form>
            
        </div>
    </div>
</div>

<script type="text/javascript">


  function validar(){
                        var clave = document.fvalida.clave.value.length;
                            //var apellidos = document.fvalida.apellidos.value.length;
                            // var celular = document.fvalida.celular.value.length;
                            //     var correo = document.fvalida.correo.value.length;
                                 if (clave != 18 ) {
                                     alert("La clabe interbacaria debe tener 18 digitos \n *Sin espacios*");
                                     document.fvalida.clave.focus();
                                     /* document.fvalida.nombre.focus();
                                     document.fvalida.apellidos.focus();
                                     document.fvalida.celular.focus();
                                     document.fvalida.correo.focus(); */
                                     return false;
                                             }else{ return true;
                                        }
                                        
                                    }


</script>

@endsection