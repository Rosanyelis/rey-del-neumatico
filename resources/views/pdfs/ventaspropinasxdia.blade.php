<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Ventas Totales {{ \Carbon\Carbon::now()->format('d/m/Y H:i A') }}</title>
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
                    Información de Ventas Totales
                </td>
            </tr>
            <tr >
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" >Nº Facturas: {{ count($informe) }}</td>
            </tr>

        </tbody>
    </table>

    <table border="1" cellspacing="0" cellpadding="0" style="width: 100%;font-family: Helvetica, Arial, sans-serif; margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 10px">N°</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Propina</th>
                <th>Total</th>
                <th>Vendedor</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($informe as $value)
            <tr style="text-align: center">
                <td >#00000{{ $value['id'] }}</td>
                <td >{{ $value['fecha'] }}</td>
                <td >{{ $value['cliente'] }}</td>
                <td >{{ $value['propina'] }}</td>
                <td style="font-weight: bold">{{ number_format($value['total'], 0, ',', '.') }}</td>
                <td >{{ $value['vendedor'] }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="padding: 5px; text-align: right; font-size: 18px; font-weight: bold" >Total ventas: </td>
                <td colspan="2" style="padding: 5px; text-align: left; font-size: 18px; font-weight: bold" >{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    <table border="1" cellspacing="0" cellpadding="0" style="width: 100%;font-family: Helvetica, Arial, sans-serif; margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Total en Efectivo</th>
                <th>Total en T.Credito</th>
                <th>Total en Cheque</th>
                <th>Total en Transferencia</th>
                <th>Total en Propina</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center">
                <td >{{ number_format($totalefectivo, 0, ',', '.') }}</td>
                <td >{{ number_format($totalcredito, 0, ',', '.') }}</td>
                <td >{{ number_format($totalcheque, 0, ',', '.') }}</td>
                <td >{{ number_format($totaltransferencia, 0, ',', '.') }}</td>
                <td >{{ number_format($totalpropina, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
