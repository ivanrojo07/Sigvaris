<table>
    <thead>
    <tr>
        <th>Id</th>
        <th>Venta</th>
        <th>Monto</th>
        <th>Cuenta</th>
        <th>Beneficiario</th>
        <th>Referencia</th>
        <th>Clabe</th>
        <th>Banco</th>
    </tr>
    </thead>
    <tbody>
    @foreach($devolucion as $dev)
        <tr>
            <td>{{ $dev->id }}</td>
            <td>{{ $dev->venta_id }}</td>
            <td>{{ $dev->monto }}</td>
            <td>{{ $dev->cuenta }}</td>
            <td>{{ $dev->beneficiario }}</td>
            <td>{{ $dev->referencia }}</td>
            <td>{{ $dev->clave }}</td>
            <td>{{ $dev->banco }}</td>
            
        </tr>
    @endforeach
    </tbody>
</table>