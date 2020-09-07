<table>
    <thead>
    <tr>
        <th>id</th>
        <th>venta</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $devolucion->id }}</td>
            <td>{{ $devolucion->venta_id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>