<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Ventas por vendedor y mes</title>
    <style>

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }
    </style>
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
            <tr style="border-bottom: 1px solid #1C84EE; ">
                <td colspan="10" style="font-weight: bold; text-align: center; padding-bottom: 15px; padding-top: 40px">
                    Ventas del mes {{ $mes }} @if ($vendedor != 'Todos') del vendedor {{ $vendedor }} @endif
                </td>
            </tr>
            <tr>
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" >
                    <strong>Informe Generado:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i A') }}
                </td>
            </tr>
            <tr >
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" ><strong>Total ventas:</strong> {{ count($informe) }}</td>
            </tr>

        </tbody>
    </table>

    <table border="1" cellspacing="0" cellpadding="0"
        style="width: 100%;font-family: Helvetica, Arial, sans-serif; margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Total</th>
                <th>Efectivo</th>
                <th>Tarjeta</th>
                <th>Cheque</th>
                <th>Transferencia</th>
                <th>Propina</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center">
                <td >{{ number_format($total, 0, ',', '.') }}</td>
                <td >{{ number_format($totalefectivo, 0, ',', '.') }}</td>
                <td >{{ number_format($totalcredito, 0, ',', '.') }}</td>
                <td >{{ number_format($totalcheque, 0, ',', '.') }}</td>
                <td >{{ number_format($totaltransferencia, 0, ',', '.') }}</td>
                <td >{{ number_format($totalpropina, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table border="1" cellspacing="0" cellpadding="0" style="width: 100%;font-family: Helvetica, Arial, sans-serif; margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>N°</th>
                <th>Cliente</th>
                <th>Propina</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($informe as $value)
            <tr style="text-align: center">
                <td >#00000{{ $value['id'] }}</td>
                <td >{{ $value['cliente'] }}</td>
                <td style="font-weight: bold">{{ number_format($value['propina'], 0, ',', '.') }}</td>
                <td style="font-weight: bold">{{ number_format($value['total'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
