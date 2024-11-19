<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento de Compra</title>
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
        <h3>Documento de Compra</h3>
        <h3 style="text-align: left; font-weight: normal" >
            <strong>Proveedor:</strong> {{ $purchase->supplier->name }}
            <br>
            <strong>Teléfono:</strong> {{ $purchase->supplier->phone }}
            <br>
            <strong>Correo:</strong> {{ $purchase->supplier->email }}
            <br>
            <br>
            <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($purchase->created_at)->format('d/m/Y H:i A') }}
            <br>
            <strong>Nro. Factura de compra:</strong> {{ $purchase->reference }}
            <br>
            <strong>¿Recibido?:</strong> {{ ($purchase->received == 1 ? 'Recibido' : 'No Recibido') }}
            <br>
            <strong>Tipo de Compra:</strong> {{ $purchase->type_purchase }}
            <br>
            <strong>Notas:</strong> {{ $purchase->note }}
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
            @foreach ($purchase->purchaseItems as $item)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->cost, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold">Total:</td>
                    <td style="text-align: center;">{{ number_format($purchase->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        <br>
        <br>

</body>
</html>
