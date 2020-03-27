@extends('principal')
@section('content')
<div class="container">
    <div class="card">
        
            <div class="card-header">
                <h1>Descuento</h1>
            </div>
            <div class="card-body">    
                {{ csrf_field() }}
                <div class="row">
                    <div class="form-group col-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required="" value="{{ $descuento->nombre }}" readonly="">
                    </div>
                    <div class="form-group col-3">
                        <label for="inicio">De:</label>
                        <input type="date" class="form-control" name="inicio" id="inicio" required="" value="{{ $descuento->inicio }}" readonly="">
                    </div>
                    <div class="form-group col-3">
                        <label for="fin">A:</label>
                        <input type="date" step="0.01" name="fin" class="form-control" id="fin" required="" value="{{ $descuento->fin }}" readonly="">
                    </div>
                   
                </div>
                    <br>
                    <label>Tipo: </label>
                    <div class="form-group col-10">
                        <label>Descripción: </label>
                        <label type="text">{{ $descuento->descripcion }}</label>
                    </div>
                    
                </div>
            </div>
</div>
@endsection