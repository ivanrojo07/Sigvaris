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
            @if $modificacion->producto()->oficina_id===session('oficina')
            <tr>
                <td>{{$modificacion->created_at}}</td>
                <td>{{$modificacion->user()->first()->name}}</td>
                <td>{{$modificacion->stock_anterior}}</td>
                <td>{{$modificacion->stock_nuevo}}</td>
                <td>{{$modificacion->motivo}}</td>
            </tr>
        @endforeach
    </tbody>    
</table>