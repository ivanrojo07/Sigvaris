@extends('paciente.show')
@section('submodulos')
<div class="container">

    
    <div class="card">
        <div class="card-header">
            <h4>Historial Ventas</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Descuento</th>
                        <th>Fecha</th>
                        <th>Operación</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$ventas)
                    <h3>No hay ventas registrados</h3>
                    @else
                    @foreach($ventas as $venta)
                    <tr>
                        <td>{{$venta->id}}</td>
                        <td>{{$venta->paciente->fullname}}</td>
                        <td>{{$venta->total}}</td>
                        @if($venta->descuento)
                            <td>{{$venta->descuento->nombre}}</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{$venta->fecha}}</td>
                        <td>
                            <div class="row">
                                <div class="col-auto pr-2">
                                    <a href="{{route('ventas.show', ['venta'=>$venta])}}"
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