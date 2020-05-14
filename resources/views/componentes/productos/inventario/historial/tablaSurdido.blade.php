<table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="tablaHistorialInventario">
    <thead>
        <tr class="info">
            <th>FECHA</th>
            <th>USUARIO</th>
            <th>STOCK ANTERIOR</th>
            <th>STOCK NUEVO</th>
            <th>MOTIVO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($historialModificacionesInventario as $modificacion)
            @if ($modificacion->producto->oficina_id==session('oficina'))
            <tr>
                <td>{{$modificacion->created_at}}</td>
                <td>{{$modificacion->producto->sku}}</td>
                <td>{{$modificacion->user()->first()->name}}</td>
                <td>{{$modificacion->numero}}</td>
            </tr>
            @endif
        @endforeach
    </tbody>    
</table>