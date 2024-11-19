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
                <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px; ">
                    {{ $empresa->name }}
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px; ">
                    {{ $empresa->email }}
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px; ">
                {{ $empresa->phone }}
                </th>
            </tr>
            <tr>
                <th colspan="6" style="padding: 5px; text-align: center; font-size: 10px" >
                    <strong>Informe Generado:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i A') }}
                </th>
            </tr>
            <tr>
                <th colspan="3" style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Total Neumáticos Vendidos</th>
                <th colspan="3" style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Total en peso</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center">
                <td colspan="3" style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">{{ $totales->total_neumaticos }}</td>
                <td colspan="3" style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">{{ $totales->total_peso }}</td>
            </tr>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Fecha</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Producto</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Cantidad</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Precio Compra</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Subtotal</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Peso</th>  
            </tr>
        </thead>
        <tbody>
        @foreach ($informe as $value)
            <tr style="text-align: center">
            <td >{{ $value['fecha'] }}</td>
                <td >{{ $value['producto'] }}</td>
                <td >{{ $value['cantidad'] }}</td>
                <td style="font-weight: bold">{{ $value['costo'] }}</td>
                <td style="font-weight: bold">{{ $value['subtotal'] }}</td>
                <td >{{ $value['peso'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
