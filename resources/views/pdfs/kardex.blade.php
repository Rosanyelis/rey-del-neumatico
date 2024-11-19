<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex de {{ $producto->name }}</title>
</head>
<body>
    <table border="1" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="3"><img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="60"></th>
                <th colspan="3" style="text-align: center"><h3>{{ $empresa->name }}</h3></th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center"><h4>Kardex del Producto {{ $producto->name }}</h4></th>
            </tr>
            <tr>
                <th>Movimiento</th>
                <th>Fecha</th>
                <th>Detalles</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody></tbody>
            @foreach ($kardexes as $kardex)
                <tr style="text-align: center; font-size: 14px;@if ($kardex->type == 2) background-color: #e3cbcb; @elseif($kardex->type == 1) background-color: #c1dbd2; @endif">
                    <td>{{ $kardex->type == 1 ? 'Ingreso' : 'Salida' }}</td>
                    <td>{{ \Carbon\Carbon::parse($kardex->created_at)->format('d/m/Y H:i A' ) }}</td>
                    <td>{{ $kardex->description }}</td>
                    <td>{{ $kardex->quantity }}</td>
                    <td>{{ number_format($kardex->price, 0, '', '.') }}</td>
                    <td>{{ number_format($kardex->total, 0, '', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
</body>
</html>
