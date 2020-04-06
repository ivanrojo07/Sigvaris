@extends('principal')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@section('content')
<div class="container">

    
    <div class="card">
        <div class="card-header">
            <div class="row">
            <div class="col-6">
                <h4>Historial Negados</h4>
            </div>
            <div class="col-6 text-center">
                <a href="{{route('negado.index')}}" class="btn btn-primary">
                    <strong> Crear un producto Negado</strong>
                </a>
            </div>

        </div>

            
        </div>
        <div class="card-body">
            <form action="{{url('negado/show2')}}" method="POST" id="formBusuqeda">
                @csrf
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="desde" class="text-muted text-uppercase"><strong>Desde:</strong></label>
                        <input type="date" class="form-control" name="fechaInicioBusqueda" required>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="hasta" class="text-muted text-uppercase"><strong>Hasta:</strong></label>
                        <input type="date" class="form-control" name="fechaFinBusqueda" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <a type="submit" class="btn btn-primary text-white border-0" id="botonBuscarCrms">Buscar</a>
                    </div>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Producto negado</th>
                        <th>Fecha</th>
                        <th>Fecha entrega</th>
                        <th>Comentarios</th>
                        <th>Operación</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$negados)
                    <h3>No hay productos negados registrados</h3>
                    @else
                    @foreach($negados as $negado)
                    <tr>
                        <td>{{$negado->id}}</td>
                        <td>{{$negado->paciente->fullname}}</td>
                        <td>{{$negado->producto->descripcion}}</td>
                        <td>{{\Carbon\Carbon::parse($negado->fecha)->format('m/d/Y')}}</td>
                        <td>{{\Carbon\Carbon::parse($negado->fecha_entrega)->format('m/d/Y')}}</td>
                        <td>{{$negado->comentarios}}</td>
                        <td>
                            <div class="row">
                                <div class="col-auto pr-2">
                                    <a href="{{route('negado.edit', ['negado'=>$negado])}}"
                                        class="btn btn-primary"><i class="fas fa-eye"></i><strong> editar</strong></a>
                                </div>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
            {{-- $negados->links() --}}
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#botonBuscarCrms").click(function(){
            $("#formBusuqeda").submit();
        });

    });
</script>
@endsection