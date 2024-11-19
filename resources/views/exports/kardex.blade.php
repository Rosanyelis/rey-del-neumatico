<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex</title>
</head>
<body>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Ingreso</th>
            <th>Habian</th>
            <th>Salieron</th>
            <th>Quedan</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
            <th>Descripcion</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kardex as $k)
            <tr>
                <td>{{ \Carbon\Carbon::parse($k->fecha)->format('d/m/Y H:i A') }}</td>
                <td>{{ $k->producto }}</td>
                <td>{{ $k->tipo_movimiento }}</td>
                <td>{{ $k->ingresaron }}</td>
                <td>{{ $k->habian }}</td>
                <td>{{ $k->salieron }}</td>
                <td>{{ $k->quedan }}</td>
                <td>{{ $k->cantidad }}</td>
                <td>{{ $k->precio }}</td>
                <td>{{ $k->total }}</td>
                <td>{{ $k->descripcion }}</td>
                <td>{{ $k->usuario }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
