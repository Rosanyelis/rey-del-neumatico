<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento nº 00000{{ $sale->id }}</title>
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
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i A') }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" >
                    <strong>Vendedor:</strong> {{ $sale->user->name }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #1C84EE; ">
                <td colspan="10" style="font-weight: bold; text-align: center; padding-bottom: 15px; padding-top: 40px">
                    Información de Cliente
                </td>
            </tr>
            <tr>
                <td colspan="7" style="padding: 5px;font-size: 14px" >
                    <strong>Cliente:</strong> {{ $sale->customer->name }}
                </td>
                <td colspan="3" style="padding: 5px;font-size: 14px">
                    <strong>Rut:</strong> {{ $sale->customer->rut }}
                </td>
            </tr>
            <tr>
                <td colspan="7" style="padding: 5px;font-size: 14px">
                    <strong>Correo:</strong> {{ $sale->customer->email }}
                </td>
                <td colspan="3" style="padding: 5px;font-size: 14px">
                    <strong>Teléfono:</strong> {{ $sale->customer->phone }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="padding: 5px;font-size: 14px">
                    <strong>Dirección:</strong> {{ $sale->customer->address }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="padding: 5px;font-size: 14px">
                    <strong>Nota:</strong> {{ $sale->note }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="padding: 5px;font-size: 14px">
                    <strong>Nota de pago:</strong> {{ $sale->note_pay }}
                </td>
            </tr>
        </tbody>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 40px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <td colspan="5" style="text-align: center; font-weight: bold; ">
                    Información de la Venta
                </td>
            </tr>
            <tr>
                <th style="text-align: center; font-weight: bold">Código</th>
                <th style="text-align: center; font-weight: bold">Artículo</th>
                <th style="text-align: center; font-weight: bold">Cant.</th>
                <th style="text-align: center; font-weight: bold">P. Unitario</th>
                <th style="text-align: center; font-weight: bold">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->saleitems as $item)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ number_format($item->quantity, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">SubTotal:</td>
                <td style="text-align: center;">{{ number_format($sale->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">Descuento:</td>
                <td style="text-align: center;">{{ number_format($sale->total_discount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">Propina:</td>
                <td style="text-align: center;">{{ number_format($sale->perquisite, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">Total:</td>
                <td style="text-align: center;">{{ number_format($sale->grand_total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table border="0" cellspacing="0" style="width: 100%; margin-top: 40px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <tr>
            <td style="text-align: center; font-size: 14px">
                <h2>Gracias por su compra</h2>
                <h3>¡Hasta pronto!</h3>
            </td>
        </tr>
    </table>
</body>
</html>
