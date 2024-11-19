<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos por Categorias</title>
</head>
<body>
    <table >
        <thead>
            <tr>
                <th colspan="2" style="text-align: center">{{ $empresa->name }}</th>
            </tr>
            <tr>
                <th>Categoria</th>
                <th>Productos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->products->name }}</td>
                </tr>
            @endforeach
    </table>
</body>
</html>
