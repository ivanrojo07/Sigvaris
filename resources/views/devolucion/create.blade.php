@extends('principal')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{route('devolucion.cargarDevolucion')}}" method="POST">
                @csrf
                <input type="hidden" name="venta_id" id="venta_id" value="{{$venta->id}}">
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
                    <input type="number" class="form-control" id="cuenta" name="cuenta" value="" required >
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">BENEFICIARIO: </label>
                    <input type="text" class="form-control" id="beneficiario" value="beneficiario" required >
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">REFERENCIA: </label>
                    <input type="text" class="form-control" id="referencia" value="referencia" required >
                </div>
                <div class="col-4 form-group">
                    <label for="" class="text-uppercase text-muted">CLAVE: </label>
                    <input type="number" class="form-control" id="clave" name="clave" value="" required >
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





</script>

@endsection