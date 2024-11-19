<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>

</head>
<body>
    <table border="1" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="2"><img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="60"></th>
                <th colspan="5" style="text-align: center"><h3>{{ $empresa->name }}</h3></th>
            </tr>
            <tr>
                <th colspan="7" style="text-align: center"><h4>Listado de Productos</h4></th>
            </tr>
            <tr>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Categoria</th>
                <th>Tipo</th>
                <th>Stock</th>
                <th>Costo</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->storeqty->quantity }}</td>
                    <td>{{ $product->cost }}</td>
                    <td>{{ $product->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
