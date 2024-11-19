<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
</head>
<body>
<table>
    <thead>
        <tr>
            <th >Categoria</th>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Stock</th>
            <th>Costo</th>
            <th>Precio</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->category_name }}</td>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->type }}</td>
                <td>{{ number_format($product->stock, 0, ',', '.') }}</td>
                <td>{{ number_format($product->cost, 0, ',', '.') }}</td>
                <td>{{ number_format($product->price, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
