<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento de venta</title>
    <style>
        * {
            font-size: 12px;
            font-family: 'DejaVu Sans', serif;
        }

        h1 {
            font-size: 18px;
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
            margin: 0 auto;
        }

        td.precio {
            text-align: right;
            font-size: 11px;
        }

        td.cantidad {
            font-size: 11px;
        }

        td.producto {
            text-align: center;
        }

        th {
            text-align: center;
        }


        .centrado {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 250px;
            max-width: 250px;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .ticket {
            margin: 10px;
            padding: 0;
        }

        body {
            text-align: center;
        }
    </style>
</head>
<body class="ticket centrado">
        <img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="60">
        <h1>{{ $empresa->name }}</h1>
        <h3>{{ $empresa->address }}<br>
            {{ $empresa->email }} <br>
            {{ $empresa->phone }}
        </h3>
        <h3>Documento #00000{{ $sale->id }}</h3>
        <h3 style="text-align: left; font-weight: normal" >
            <strong>Cliente:</strong> {{ $sale->customer->name }}
            <br>
            <strong>Rut:</strong> {{ $sale->customer->rut }}
            <br>
            <strong>Correo:</strong> {{ $sale->customer->email }}
            <br>
            <strong>Tlf:</strong> {{ $sale->customer->phone }}
            <br>
            <strong>Dirección:</strong> {{ $sale->customer->address }}
            <br>
            <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i A') }}
            <br>
            <strong>Vendedor:</strong> {{ $sale->user->name }}
            <br>
            <strong>Notas:</strong> {{ $sale->note }}
            <br>
            <strong>Nota de pago:</strong> {{ $sale->note_pay }}
        </h3>
        <br>
        <table>
            <thead>
                <tr class="centrado">
                    <th class="producto">Artículo</th>
                    <th class="cantidad">Cant.</th>
                    <th class="precio">Precio</th>
                    <th class="precio">Subtotal</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($sale->saleitems as $item)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $item->product_name }}</td>
                    <td>{{ number_format($item->quantity, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold">SubTotal:</td>
                    <td style="text-align: center;">{{ number_format($sale->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold">Descuento:</td>
                    <td style="text-align: center;">{{ number_format($sale->total_discount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold">Propina:</td>
                    <td style="text-align: center;">{{ number_format($sale->perquisite, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold">Total:</td>
                    <td style="text-align: center;">{{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        <br>
        <br>
        <h1 class="centrado">¡GRACIAS POR SU COMPRA!
            <br>
            <strong>¡HASTA PRONTO!</strong>
        </h1>
</body>
</html>
