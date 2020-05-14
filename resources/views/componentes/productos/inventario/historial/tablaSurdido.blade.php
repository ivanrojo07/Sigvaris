<table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="tablaHistorialInventario">
    <thead>
        <tr class="info">
            <th>FECHA</th>
            <th>USUARIO</th>
            <th>SKU</th>
            <th>STOCK AÑADIDO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($historialModificacionesInventario as $modificacion)
            @if ($modificacion->producto->oficina_id==session('oficina'))
            <tr>
                <td>{{\Carbon\Carbon::parse($modificacion->created_at)->formatLocalized('%d de %B de %Y')}}</td>
                <td>{{$modificacion->producto->sku}}</td>
                <td>{{$modificacion->user()->first()->name}}</td>
                <td>{{$modificacion->numero}}</td>
            </tr>
            @endif
        @endforeach
    </tbody>    
</table>