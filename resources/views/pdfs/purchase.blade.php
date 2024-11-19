<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras</title>
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
                        Compra
                        <br>
                        {{ \Carbon\Carbon::parse($purchase->created_at)->format('d - m - Y') }}
                    </h1>
                </th>
            </tr>
        </thead>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 40px;
        border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; border-radius: 20px;">
        <tbody>
            <tr>
                <td colspan="9"><strong>Datos de Compra</strong></td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>N° de Factura:</strong> {{ $purchase->reference }} </td>
                <td colspan="3">
                    <strong>¿Recibido?:</strong> {{ ($purchase->received == 1 ? 'Recibido' : 'No Recibido') }} </td>
                <td colspan="3">
                    <strong>Tipo de Compra:</strong> {{ $purchase->type_purchase }} </td>
            </tr>
            <tr>
                <td colspan="9">
                    <strong>Notas:</strong> {{ $purchase->note }}
                </td>
        </tbody>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 20px;
        border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; border-radius: 20px;">
        <tbody>
            <tr>
                <td colspan="8"><strong>Datos de Proveedor</strong></td>
            </tr>
            <tr>
                <td colspan="4" ><strong>Proveedor:</strong> {{ $purchase->supplier->name }} </td>
                <td colspan="4" ><strong>Teléfono:</strong> {{ $purchase->supplier->phone }} </td>
            </tr>
            <tr>
                <td colspan="8" ><strong>Correo:</strong> {{ $purchase->supplier->email }} </td>
            </tr>
        </tbody>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 20px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="4" style="text-align: center; font-weight: bold">Productos</th>
            </tr>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
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

</body>
</html>
