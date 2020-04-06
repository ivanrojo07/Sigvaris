@extends('principal')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@section('content')
<div class="container">

    
    <div class="card">
        <div class="card-header">
            <h4>Historial Negados</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Producto negado</th>
                        <th>Fecha</th>
                        <th>Fecha entrega</th>
                        <th>Comentarios</th>
                        <th></th>
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
                        <td>{{$negado->producto_id}}</td>
                        <td>{{\Carbon\Carbon::parse($negado->fecha)->format('m/d/Y')}}</td>
                        <td>{{\Carbon\Carbon::parse($negado->fecha_entraga)->format('m/d/Y')}}</td>
                        <td>{{$negado->comentarios}}</td>
                        <td>
                            <div class="row">
                                <div class="col-auto pr-2">
                                    <a href=""
                                        class="btn btn-primary"><i class="fas fa-eye"></i><strong> Ver</strong></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection