<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización</title>
</head>
<body>
    <table cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="4">
                    <img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="150">
                    <br>
                    <h4>{{ $empresa->address }}<br>
                        {{ $empresa->email }} <br>
                        {{ $empresa->phone }}
                    </h4>
                </th>
                <th colspan="4" style="text-align: center; font-weight: bold; ">
                    <h1>
                        Cotización
                        <br>
                        N° {{ $quotation->correlativo }}
                        <br>
                        {{ \Carbon\Carbon::parse($quotation->created_at)->format('d - m - Y') }}
                    </h1>
                </th>
            </tr>
        </thead>
        <tbody style="border: 1px solid black">
            <tr>
                <td colspan="8" style="border: 1px solid black"><strong>Datos de Cliente</strong></td>
            </tr>
            <tr>
                <td colspan="8" style="border: 1px solid black"><strong>Cliente:</strong> {{ $quotation->customer->name }} </td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid black"><strong>Rut:</strong> {{ $quotation->customer->rut }} </td>
                <td colspan="4" style="border: 1px solid black"><strong>Teléfono:</strong> {{ $quotation->customer->phone }} </td>
            </tr>
            <tr>
                <td colspan="8" style="border: 1px solid black"><strong>Dirección:</strong> {{ $quotation->customer->address }} </td>
            </tr>
            <tr>
                <td colspan="8" style="border: 1px solid black"><strong>Notas:</strong> {{ $quotation->note }} </td>
            </tr>
        </tbody>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 40px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center; font-weight: bold">Productos</th>
            </tr>
            <tr>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotation->items as $item)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ number_format($item->quantity, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->discount, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold">SubTotal:</td>
                <td style="text-align: center;">{{ number_format($quotation->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold">Descuento (%{{ $quotation->order_discount_id }}):</td>
                <td style="text-align: center;">{{ number_format($quotation->total * ($quotation->order_discount_id / 100), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold">Impuesto (%{{ $quotation->order_tax_id }}):</td>
                <td style="text-align: center;">{{ number_format($quotation->total * ($quotation->order_tax_id / 100), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold">Total:</td>
                <td style="text-align: center;">{{ number_format($quotation->grand_total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
