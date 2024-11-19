<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neumaticos Internacionales</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Precio Costo</th>
                <th>Precio Venta</th>
                <th>Tipo</th>
                <th>Nacionalidad</th>
                <th>Peso</th>
                <th>Stock Actual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->codigo }}</td>
                    <td>{{ $product->producto }}</td>
                    <td>{{ $product->precio_costo }}</td>
                    <td>{{ $product->precio_venta }}</td>
                    <td>{{ $product->tipo }}</td>
                    <td>{{ $product->nacionalidad }}</td>
                    <td>{{ $product->peso }}</td>
                    <td>{{ $product->stock_actual }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
