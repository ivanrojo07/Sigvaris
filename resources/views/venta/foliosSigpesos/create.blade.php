@extends('principal')
@section('content')
<div class="container">

    <div class="card">
        <form role="form" method="POST" action="{{ route('foliosSigpesos.store') }}">
            <div class="card-header">
                <h1>Nuevo Rango de folios de sigpesos  </h1>
            </div>
            <div class="card-body">
                {{ csrf_field() }}
                <div class="row">
                    <div class="form-group col-6">
                        <label class="control-label" for="Descripcion"><i class="fa fa-asterisk" aria-hidden="true"></i> Descripcion de rango:</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required autofocus>
                    </div>
                    <div class="form-group col-6">
                        <label class="control-label" for="rango_superior">Rango superior:</label>
                        <input type="number" class="form-control" id="rango_superior" name="rango_superior" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="control-label" for="rango_inferior">Rango inferior:</label>
                        <input type="number" class="form-control" id="rango_inferior" name="rango_inferior" required>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-success btn-lg btn-block">
                    <strong>Guardar</strong>
                </button>
            </div>
    </div>

    </form>
</div>

@endsection