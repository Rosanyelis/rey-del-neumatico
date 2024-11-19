<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Ventas de Productos por Dia {{ \Carbon\Carbon::now()->format('d/m/Y H:i A') }}</title>
</head>
<body>
    <table cellspacing="0" cellpadding="1" style="width: 100%;font-family: Helvetica, Arial, sans-serif">
        <thead>
            <tr>
                <th colspan="5">
                    <img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="150">
                </th>
                <th colspan="5" style="text-align: center; font-weight: bold; ">
                    <h4>{{ $empresa->address }}<br>
                        {{ $empresa->email }} <br>
                        {{ $empresa->phone }}
                    </h4>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" >
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i A') }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #1C84EE; ">
                <td colspan="10" style="font-weight: bold; text-align: center; padding-bottom: 15px; padding-top: 40px">
                    Información de Productos Vendidos por Dia
                </td>
            </tr>
            <tr >
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" >Nº Productos: {{ count($informe) }}</td>
            </tr>

        </tbody>
    </table>

    <table border="1" cellspacing="0" cellpadding="0" style="width: 100%;font-family: Helvetica, Arial, sans-serif; margin-top: 20px; border-collapse: collapse; font-size: 14px">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Nro.Venta</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Cant.</th>
                <th>Precio</th>
                <th>Metodo de Pago</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($informe as $value)
            <tr style="text-align: center">
                <td >{{ $value['fecha'] }}</td>
                <td >{{ $value['documento'] }}</td>
                <td >{{ $value['producto'] }}</td>
                <td >{{ $value['tipo'] }}</td>
                <td >{{ $value['cantidad'] }}</td>
                <td style="font-weight: bold">{{ number_format($value['precio'], 0, ',', '.') }}</td>
                <td >{{ $value['pago'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
