<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento de Orden de Trabajo</title>
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
        <br>
        <h2>OT N° {{ $workOrder->correlativo }}</h2>
        <br>
        <h3 style="text-align: left; font-weight: normal" >
            <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($workOrder->created_at)->format('d/m/Y h:i A') }}
            <br>
            <strong>Cliente:</strong> {{ $workOrder->customer->name }}
            <br>
            <strong>Rut:</strong> {{ $workOrder->customer->rut }}
            <br>
            <strong>Dirección:</strong> {{ $workOrder->customer->address }}
            <br>
            <strong>Tlf:</strong> {{ $workOrder->customer->phone }}
            <br>
            <strong>Marca:</strong> {{ $workOrder->marca }}
            <br>
            <strong>Modelo:</strong> {{ $workOrder->modelo }}
            <br>
            <strong>Patente:</strong> {{ $workOrder->patente_vehiculo }}
            <br>
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
            @foreach ($workOrder->items as $service)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $service->product->name }}</td>
                    <td>{{ number_format($service->quantity, 0, ',', '.') }}</td>
                    <td>{{ number_format($service->price, 0, ',', '.') }}</td>
                    <td>{{ number_format($service->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold">Impuesto:</td>
                    <td style="text-align: center;">{{ number_format($workOrder->taxamount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold">Total:</td>
                    <td style="text-align: center;">{{ number_format($workOrder->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        <br>
        <br>

</body>
</html>
