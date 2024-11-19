@extends('layouts.pos')

@section('title') POS @endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
 <style>
    ::-webkit-scrollbar {
        width: 5px; /* Ancho del scrollbar */
    }

    /* Establece el estilo del thumb (la parte deslizable del scrollbar) */
    /* En navegadores Webkit (Chrome, Safari) */
    ::-webkit-scrollbar-thumb {
        background-color: #888; /* Color del thumb */
        border-radius: 5px; /* Redondez del thumb */
    }

    /* Establece el estilo del track (la pista del scrollbar) */
    /* En navegadores Webkit (Chrome, Safari) */
    ::-webkit-scrollbar-track {
        background-color: #f1f1f1; /* Color del track */
    }

    .producto {
        width: 60px;
        height: auto; /* La altura se ajusta automáticamente */
        object-fit: cover !important; /* Hace que la imagen llene todo el contenedor, recortando si es necesario */
        object-position: center; /* Centra la imagen */
    }
 </style>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6 col-lg-6 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <table style="width:100%;" class="layout-table">
                    <tr>
                        <td width="50%">
                            <div id="pos">
                                <form action="{{ route('pos.store') }}" method="post" id="posForm">
                                    @csrf
                                    <div class="well well-sm" id="leftdiv">
                                        <div id="lefttop" style="margin-bottom:5px;">
                                            <div class="form-group" style="margin-bottom:5px;">
                                                <div class="input-group">
                                                    <select class="form-select" name="customer" id="customer" style="width: 90%">
                                                        <option selected>Seleccione cliente</option>
                                                    </select>
                                                    <button class="btn btn-secondary" type="button" title="Añadir cliente"
                                                        data-bs-toggle="modal" data-bs-target="#customerModal">
                                                        <i class="mdi mdi-plus-circle"></i>
                                                    </button>
                                                </div>
                                                <div style="clear:both;"></div>
                                            </div>
                                            <div class="form-group row" style="margin-bottom:5px;">
                                                <div class="col-md-2">
                                                    <label for="note_ref" class="col-form-label" >Nota Ref.</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="note_ref" id="note_ref"
                                                    class="form-control kb-text" placeholder="Nota de Referencia" />
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-bottom:5px;">
                                                <div class="col-md-2">
                                                    <label for="productos" class="col-form-label" >Productos</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="code" id="productos" class="form-control"
                                                    placeholder="Buscar articulos por codigo o nombre, u escanear el codigo de barras" />
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-bottom:5px;">
                                                <div class="col-md-2">
                                                    <label for="workorder" class="col-form-label" >O. Trab.</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="workorder" id="workorder" class="form-control"
                                                    placeholder="Buscar Orden de Trabajo por numero de orden" />
                                                </div>
                                            </div>

                                        </div>
                                        <div id="print" class="fixed-table-container">
                                            <div class="slimScrollDiv" style="position: relative;overflow: auto;width: auto;height: 250px;">
                                                <table id="posTable" class="table table-sm table-striped table-condensed table-hover list-table"
                                                    style="margin:0px;" data-height="100">
                                                    <thead>
                                                        <tr class="success">
                                                            <th>Producto</th>
                                                            <th class="oculto" style="width: 12%;text-align:center;">Inventario</th>
                                                            <th style="width: 15%;text-align:center;">Precio</th>
                                                            <th style="width: 15%;text-align:center;">Cantidad</th>
                                                            <th style="width: 20%;text-align:center;">Subtotal</th>
                                                            <th style="width: 20px;" class="satu"><i class="fa fa-trash-o"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <div id="totaldiv">
                                                <table id="totaltbl" class="table table-condensed totals" style="margin-bottom:10px;">
                                                    <tbody>
                                                        <tr class="info">
                                                            <td width="25%">Total de Articulos</td>
                                                            <td class="text-center" style="padding-right:10px;"><span id="totalItems">0</span></td>
                                                            <td width="25%">Total</td>
                                                            <td class="text-center" colspan="2"><span id="totalComplete">0</span></td>
                                                        </tr>
                                                        <tr class="info">
                                                            <td width="25%">
                                                                Descuento
                                                            </td>
                                                            <td class="text-center" style="padding-right:10px;">
                                                                <input type="number" name="discount" id="discount"
                                                                class="form-control form-control-sm" value="0">
                                                            </td>
                                                            <td width="25%">
                                                                Propina
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" name="propina" id="propina"
                                                                class="form-control form-control-sm" value="0">
                                                            </td>
                                                        </tr>
                                                        <tr class="success">
                                                            <td colspan="3" style="font-weight:bold;">
                                                                Total a pagar
                                                            </td>
                                                            <td class="text-center"  style="font-weight:bold;">
                                                                $ <span id="totalpay">0</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="botones" class="row text-center px-3 g-1">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 d-grid" style="padding: 0;">
                                                <button type="button" class="btn btn-danger" style="height:70px;"
                                                id="salir">Salir de caja</button>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-3 col-xs-6 d-grid" >
                                                <button type="button" class="btn btn-info" id="print_bill" style="height:70px;"
                                                onclick="imprimir();">
                                                    Imprimir Venta Reciente
                                                </button>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 d-grid" style="padding: 0;">
                                                <button type="button" id="paymentmethod" class="btn btn-success" style="height:70px;">
                                                    Pagar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="productos" id="productosarray">
                                    <input type="hidden" name="tax" id="taxh">
                                    <input type="hidden" name="discount" id="discounth">
                                    <input type="hidden" name="perquisite" id="propinah">
                                    <input type="hidden" name="subtotal" id="subtotal">
                                    <input type="hidden" name="grandtotal" id="total">
                                    <input type="hidden" name="total_items" id="total_items">
                                    <input type="hidden" name="methodpay" id="method_pay">
                                    <input type="hidden" name="balance" id="balanceh">
                                    <input type="hidden" name="note" id="noteh">
                                    <input type="hidden" name="amount" id="amounth">
                                    <input type="hidden" name="paymentby" id="paymentbyh">
                                    <input type="hidden" name="notepay" id="notepayh">
                                    <input type="hidden" name="paypartial" id="paypartial">
                                    <input type="hidden" name="notepayment" id="note_payment">

                                </form>
                            </div>
                        </td>
                    </tr>
                </table>
                <!-- Modal Clientes -->
                <div class="modal fade" id="customerModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="customerModal" aria-modal="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Nuevo Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" >
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nombre de Cliente</label>
                                            <input class="form-control" type="text" name="name" id="name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="rut" class="form-label">Rut de Cliente</label>
                                            <input class="form-control" type="text" name="rut" id="rut" required
                                                placeholder="00000000-0">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo</label>
                                            <input class="form-control" type="email" name="email" id="email" required
                                                placeholder="test@example.com">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Celular</label>
                                            <input class="form-control" type="text" name="phone" id="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Dirección</label>
                                            <input class="form-control" type="text" name="address" id="address" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary pull-left" data-bs-dismiss="modal"> Cerrar </button>
                                <button type="button" class="btn btn-primary" id="add_customer"> Guardar Cliente </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Payment -->
                <div class="modal fade" id="modalPayment" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="modalPayment" aria-hidden="true"
                    data-bs-scroll="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Finalizando Venta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>Total a pagar: <span id="total_amount"></span> </h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="method">Forma de Pago</label>
                                            <select id="method" name="method" class="form-control">
                                                <option value="Total">Total</option>
                                                <option value="Parcial">Parcial</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="w-100"></div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="notepayment">Nota</label>
                                            <textarea name="notepayment" id="note" class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="row" id="total_pay" >
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="amount">Cantidad</label>
                                            <input type="number" id="amount" name="amount" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="paymentBy">Pagando por</label>
                                            <select name="paymentBy" id="paymentBy" class="form-control">
                                                <option value="seleccione">Seleccione</option>
                                                <option value="Efectivo">Efectivo</option>
                                                <option value="Tarjeta de credito">Tarjeta de credito</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Transferencia">Transferencia</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none" id="partial_pay">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="importe">Importe</label>
                                            <input type="number" id="importe" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="methodPay">Pagando por</label>
                                            <select name="methodPay" id="methodPay" class="form-control">
                                                <option value="seleccione">Seleccione</option>
                                                <option value="Efectivo">Efectivo</option>
                                                <option value="Tarjeta">Tarjeta</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Transferencia">Transferencia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="notePayment" id="labelNote">Nota de Pago</label>
                                            <input type="text" id="notePayment" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="add_partial" class="btn btn-primary mt-4" id="add_payment">Agregar</button>
                                    </div>
                                    <div class="col-md-12">
                                        <table id="paymentTable" class="table table-condensed table-sm ">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" class="text-center">Pagos parciales</th>
                                                </tr>
                                                <tr>
                                                    <th>Forma de Pago</th>
                                                    <th>Importe</th>
                                                    <th>Detalles</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="payment_list">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row d-none" id="efecty_pay" >
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="change">Nota de pago</label>
                                            <input type="text" id="notepayefecty" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none" id="cheque_pay" >
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="change">Numero de cheque</label>
                                            <input type="number" id="nro_cheque" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none" id="transferencia_pay" >
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="change">Numero de referencia</label>
                                            <input type="number" id="nro_transferencia" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect"
                                    data-bs-dismiss="modal" id="close">Cerrar</button>
                                <button type="button" class="btn btn-primary waves-effect"
                                    id="save_payment">Guardar</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>

                <table id="printFactura" class="table table-bordered table-condensed w-100 d-none"
                    style="width: 280px; font-size: 12px">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center">
                            <img src="{{ asset('') }}/{{ $empresa->logo }}" alt="logo" height="60">
                                <h1>{{ $empresa->name }}</h1>
                                <h3>{{ $empresa->address }}<br>
                                    {{ $empresa->email }}<br>
                                    {{ $empresa->phone }}
                                </h3>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-center">
                                <h3>Documento #00000{{ $saleLast->id }}</h3>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Cliente:</strong> {{ $saleLast->customer->name }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Rut:</strong> {{ $saleLast->customer->rut }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Correo:</strong> {{ $saleLast->customer->email }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Tlf:</strong> {{ $saleLast->customer->phone }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Dirección:</strong> {{ $saleLast->customer->address }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($saleLast->created_at)->format('d/m/Y H:i A') }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Vendedor:</strong> {{ $saleLast->user->name }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Nota:</strong> {{ $saleLast->note }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: left; font-weight: normal">
                                <strong>Nota de pago:</strong> {{ $saleLast->note_pay }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: left; font-weight: normal">
                                &nbsp;
                            </th>
                        </tr>
                        <tr>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>Precio</th>
                            <th>SubTotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saleLast->saleitems as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ number_format($item->quantity, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align: right;">Subtotal</th>
                            <th>{{ number_format($saleLast->total, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: right;">Descuento </th>
                            <th>{{ number_format($saleLast->total_discount, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: right;">Propina</th>
                            <th>{{ number_format($saleLast->perquisite, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: right;">Total</th>
                            <th>{{ number_format($saleLast->grand_total, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-center pt-5 pb-5">
                                <h3>¡GRACIAS POR SU COMPRA!</h3>
                                <h3>¡HASTA PRONTO!</h3>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <table style="width:100%;" class="layout-table">
                    <tr>
                        <td id="products" width="50%" style="padding: 8px; vertical-align: top">
                            <div class="row gx-2 gy-0">
                                <div class="col-12">
                                    <div class="input-group">
                                        <select class="form-select" name="category" id="categorys" style="width: 100%">
                                            <option selected>Seleccione Categoria</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="productlist" class="row gx-2 gy-3"
                                style="position: relative;overflow: auto;width: auto;
                                    height: 610px;margin: 0; padding: 0">

                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

<script>
    var dataProduct = [];
    var dataPartial = [];
    var totalProduct = [];
    const basepath = "{{ asset('assets/images/') }}";
    const baseStorage = "{{ asset('') }}";
    $(document).ready(function() {

        // Inicializa el select2 de categorias
        $('#categorys').select2({
            placeholder: 'Seleccione una categoria',
        });
        // Inicializa el select2 de clientes y carga la data
        $('#customer').select2({
            placeholder: 'Seleccione un cliente',
            ajax: {
                url: '{{ route("pos.getCustomers") }}',
                type: 'GET',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {

                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true,
            },
        });
        // Inicializa el autocomplete de productos para ser seleccionados
        $('#productos').autocomplete({
            minLength: 1,
            source: function(request, response) {
                $.ajax({
                    url: '{{ route("pos.getProducts") }}',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                let select =  ui.item.code + ' - ' + ui.item.name ;
                let qtyHtml = '#quantity-' + ui.item.id;
                let subtotalHtml = '#subtotal-' + ui.item.id;
                let totalComplete = 0;

                // Verifica si hay stock
                if (ui.item.quantity == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!, no hay stock',
                        text: 'El producto no tiene stock disponible, favor de seleccionar otro!',
                    });
                }

                if (dataProduct.length > 0) {
                    let code = ui.item.code;
                        code = code.trim();
                    let index = dataProduct.findIndex((item) => item.code == code);

                    if (index == -1) {

                        let datosFila = {};
                            datosFila.id = ui.item.id;
                            datosFila.code = code;
                            datosFila.name = ui.item.name;
                            datosFila.stock = ui.item.quantity;
                            datosFila.quantity = 1;
                            datosFila.price = ui.item.price;
                            datosFila.subtotal = ui.item.price;
                            datosFila.type = 'product';

                            dataProduct.push(datosFila);

                        $('#posTable').append(
                            '<tr id="producto-' + code + '">' +
                            '<td>' + select + '</td>' +
                            '<td class="text-center">' + ui.item.quantity + '</td>' +
                            '<td class="text-center">' + ui.item.price + '</td>' +
                            '<td class="text-center"><input type="number" id="quantity-' + code + '" onchange="calculateQuantity(this)" data-id="' + code + '" class="form-control form-control-sm" min="1" value="1"></td>' +
                            '<td class="text-center"><span id="subtotal-' + code + '" data-id="' + code + '">'+ ui.item.price +'</span></td>' +
                            '<td><button type="button" id="btnDelete-' + code + '" class="btn btn-danger btn-sm" data-id="' + code + '" onclick="deleteRow(this)"><i class="mdi mdi-delete "></i></button></td>'
                        );

                    }

                    if (index != -1) {
                        let qtyHtml = '#quantity-' + code;
                        let subtotalHtml = '#subtotal-' + code;

                        let sumaQty = parseInt(dataProduct[index].quantity) + 1;

                        if (dataProduct[index].stock < sumaQty) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!, no hay stock',
                                text: 'El producto no tiene stock disponible, favor de seleccionar otro!',
                            });

                            return false;
                        }

                        dataProduct[index].quantity = parseInt(dataProduct[index].quantity) + 1;
                        dataProduct[index].price = parseFloat(dataProduct[index].price) + parseFloat(ui.item.price);
                        dataProduct[index].subtotal = parseFloat(dataProduct[index].subtotal) + parseFloat(ui.item.price);

                        $(qtyHtml).val(dataProduct[index].quantity);
                        $(subtotalHtml).text(dataProduct[index].subtotal);
                    }

                }

                if (dataProduct.length == 0) {
                    let code = ui.item.code;
                    code = code.trim();
                    let datosFila = {};
                    datosFila.id = ui.item.id;
                    datosFila.code = code;
                    datosFila.name = ui.item.name;
                    datosFila.quantity = 1;
                    datosFila.stock = ui.item.quantity;
                    datosFila.price = ui.item.price;
                    datosFila.subtotal = ui.item.price;
                    datosFila.type = 'product';

                    dataProduct.push(datosFila);

                    $('#posTable').append(
                        '<tr id="producto-' + code + '">' +
                        '<td>' + select + '</td>' +
                        '<td class="text-center">' + ui.item.quantity + '</td>' +
                        '<td class="text-center">' + ui.item.price + '</td>' +
                        '<td class="text-center"><input type="number" id="quantity-' + code + '" onchange="calculateQuantity(this)" data-id="' + code + '" class="form-control form-control-sm" min="1" value="1"></td>' +
                        '<td class="text-center"><span id="subtotal-' + code + '" data-id="' + code + '">'+ ui.item.price +'</span></td>' +
                        '<td><button type="button" id="btnDelete-' + code + '" class="btn btn-danger btn-sm" data-id="' + code + '" onclick="deleteRow(this)"><i class="mdi mdi-delete "></i></button></td>'
                    );
                }

                calculateTotal();
                calculateArticulos();
                calculateComplete();
                $('#productos').val('');
                return false;
            }

        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $( "<li>" )
                .append( "<div>" + item.code + " - " + item.name + " (" + item.quantity + ") - " + item.price + "</div>" )
                .appendTo( ul );
        };
        // Inicializa el autocomplete de Ordenes de trabajo
        $('#workorder').autocomplete({
            minLength: 1,
            source: function(request, response) {
                $.ajax({
                    url: '{{ route("pos.getWorkorders") }}',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                let stock = 0;
                let quantity = 0;
                let select = 'Orden:' + ui.item.correlativo + ' - ' + ui.item.rut + ' - ' + ui.item.name;

                if (dataProduct.length > 0) {
                    let index = dataProduct.findIndex((item) => item.code == ui.item.correlativo);

                    if (index == 1) {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'error',
                            title: 'Oops...',
                            text: 'La Orden ya ha sido agregada!, no puede agregarla de nuevo!',
                            showConfirmButton: false,
                            timer: 2500
                        });
                        $('#workorder').val('');
                    }

                    if (index == -1) {
                        let datosFila = {};
                        datosFila.id = ui.item.id;
                        datosFila.code = ui.item.correlativo;
                        datosFila.name = 'Orden:' + ui.item.correlativo + ' - ' + ui.item.rut + ' - ' + ui.item.name;
                        datosFila.quantity = 1;
                        datosFila.price = ui.item.total;
                        datosFila.subtotal = ui.item.total;
                        datosFila.type = 'workorder';

                        dataProduct.push(datosFila);

                        $('#posTable').append(
                            '<tr id="producto-' + ui.item.correlativo + '">' +
                            '<td>Orden: ' + ui.item.correlativo + '</td>' +
                            '<td class="text-center">' + stock + '</td>' +
                            '<td class="text-center">' + ui.item.total + '</td>' +
                            '<td class="text-center">' + quantity + '</td>' +
                            '<td class="text-center">' + ui.item.total + '</td>' +
                            '<td><button type="button" id="btnDelete-' + ui.item.correlativo + '" class="btn btn-danger btn-sm" data-id="' + ui.item.correlativo + '"  onclick="deleteRow(this)"><i class="mdi mdi-delete "></i></button></td>'
                        );

                    }

                }

                if (dataProduct.length == 0) {

                    let datosFila = {};
                    datosFila.id = ui.item.id;
                    datosFila.code = ui.item.correlativo;
                    datosFila.name = 'Orden:' + ui.item.correlativo + ' - ' + ui.item.rut + ' - ' + ui.item.name;
                    datosFila.quantity = 1;
                    datosFila.price = ui.item.total;
                    datosFila.subtotal = ui.item.total;
                    datosFila.type = 'workorder';

                    dataProduct.push(datosFila);

                    $('#posTable').append(
                        '<tr id="producto-' + ui.item.correlativo + '">' +
                        '<td>Orden: ' + ui.item.correlativo + '</td>' +
                        '<td class="text-center">' + stock + '</td>' +
                        '<td class="text-center">' + ui.item.total + '</td>' +
                        '<td class="text-center">' + quantity + '</td>' +
                        '<td class="text-center">' + ui.item.total + '</td>' +
                        '<td><button type="button" id="btnDelete-' + ui.item.correlativo + '" class="btn btn-danger btn-sm"  data-id="' + ui.item.correlativo + '"  onclick="deleteRow(this)"><i class="mdi mdi-delete "></i></button></td>'
                    );

                }

                calculateTotal();
                calculateArticulos();
                calculateComplete();
                $('#workorder').val('');
                return false;
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $( "<li>" )
                .append( "<div> Orden:" + item.correlativo + " - " + item.rut + " - " + item.name + "</div>" )
                .appendTo( ul );
        }
        // carga los productos del lado derecho con imagenes
        $.ajax({
            url: '{{ route("pos.getProductPos") }}',
            dataType: 'json',
            type: 'GET',
            success: function(data) {
                totalProduct = data;
                $('#productlist').empty();
                data.forEach(element => {
                    let code = element.code;
                    code = code.trim();
                    if (element.image == null) {
                        element.image = basepath + '/no-image.png';
                    } else {
                        element.image = baseStorage + element.image;
                    }

                    $('#productlist').append('<div class="col-3"><a href="javascript:void(0);" data-id="'+code+'" id="add-product" ><div class="card" style="width: 100%; height: 104px;"><div class="card-body p-2"><div class="product-img position-relative p-0"><img src="'+ element.image + '"  class="producto mx-auto d-block rounded "></div></div><div class="card-footer py-1 text-center bg-dark-subtle text-uppercase" style="font-size: 10px;"><b>' + element.name + '</b></div></div></a></div>');
                })


            }
        });
        // filtra los productos segun su categoria
        $('#categorys').on('select2:select', function(e) {
            var id = $(this).val();
            console.log(id);
            $.ajax({
                type: 'GET',
                url: "{{ route('pos.getProductPos') }}",
                data: {
                    category_id: id
                },
                success: function(data) {
                    if (data.length < 16) {
                        $("#productlist").css("height", "");
                    }
                    $('#productlist').empty();
                    data.forEach(element => {
                        let code = element.code;
                        code = code.trim();
                        if (element.image == null) {
                            element.image = basepath + '/no-image.png';
                        } else {
                            element.image = baseStorage + element.image;
                        }
                        $('#productlist').append('<div class="col-3"><a href="javascript:void(0);" data-id="'+code+'" id="add-product" ><div class="card"><div class="card-body p-1"><div class="product-img position-relative p-0"><img src="'+ element.image + '" width="70" class="img-fluid mx-auto d-block rounded "></div></div><div class="card-footer py-1 text-center bg-dark-subtle text-uppercase" style="font-size: 11px;"><b>' + element.name + '</b></div></div></a></div>');
                    })
                }
            });
        });
        // agrega los productos en el pos para procesarlos
        $('#productlist').on('click', '#add-product', function() {
            let code = $(this).data('id');
            totalProduct.find(element => {
                if (element.quantity == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!, no hay stock',
                        text: 'El producto no tiene stock disponible, favor de seleccionar otro!',
                    })
                } else {
                    if (element.code == code) {
                        let select =  element.code + ' - ' + element.name ;
                        let qtyHtml = '#quantity-' + element.code;
                        let subtotalHtml = '#subtotal-' + element.code;
                        let totalComplete = 0;

                        if (dataProduct.length > 0) {
                            let index = dataProduct.findIndex((item) => item.code == code);

                            if (index == -1) {
                                let code = element.code;
                                code = code.trim();
                                let datosFila = {};
                                    datosFila.id = element.id;
                                    datosFila.code = code;
                                    datosFila.name = element.name;
                                    datosFila.quantity = 1;
                                    datosFila.stock = element.quantity;
                                    datosFila.price = element.price;
                                    datosFila.subtotal = element.price;
                                    datosFila.type = 'product';

                                    dataProduct.push(datosFila);

                                    $('#posTable').append(
                                        '<tr id="producto-' + code + '">' +
                                        '<td>' + select + '</td>' +
                                        '<td class="text-center">' + element.quantity + '</td>' +
                                        '<td class="text-center">' + element.price + '</td>' +
                                        '<td class="text-center"><input type="number" id="quantity-' + code + '" onchange="calculateQuantity(this)" data-id="' + code + '" class="form-control form-control-sm" min="1" value="1"></td>' +
                                        '<td class="text-center"><span id="subtotal-' + code + '" data-id="' + code + '">'+ element.price +'</span></td>' +
                                        '<td><button type="button" id="btnDelete-' + code + '" class="btn btn-danger btn-sm" data-id="' + code + '" onclick="deleteRow(this)"><i class="mdi mdi-delete "></i></button></td>'
                                    );

                            }

                            if (index != -1) {

                                let sumaQty = parseInt(dataProduct[index].quantity) + 1;

                                if (dataProduct[index].stock < sumaQty) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops!, no hay stock',
                                        text: 'El producto no tiene stock disponible, favor de seleccionar otro!',
                                    });

                                    return false;
                                }

                                dataProduct[index].quantity = parseInt(dataProduct[index].quantity) + 1;
                                dataProduct[index].price = parseFloat(dataProduct[index].price) + parseFloat(element.price);
                                dataProduct[index].subtotal = parseFloat(dataProduct[index].subtotal) + parseFloat(element.price);

                                $(qtyHtml).val(dataProduct[index].quantity);
                                $(subtotalHtml).text(dataProduct[index].subtotal);

                            }

                        } else{
                            let code = element.code;
                            code = code.trim();
                            let datosFila = {};
                            datosFila.id = element.id;
                            datosFila.code = code;
                            datosFila.name = element.name;
                            datosFila.stock = element.quantity;
                            datosFila.quantity = 1;
                            datosFila.price = element.price;
                            datosFila.subtotal = element.price;
                            datosFila.type = 'product';

                            dataProduct.push(datosFila);

                            $('#posTable').append(
                                '<tr id="producto-' + code + '">' +
                                '<td>' + select + '</td>' +
                                '<td class="text-center">' + element.quantity + '</td>' +
                                '<td class="text-center">' + element.price + '</td>' +
                                '<td class="text-center"><input type="number" id="quantity-' + code + '" onchange="calculateQuantity(this)" data-id="' + code + '" class="form-control form-control-sm" min="1" value="1"></td>' +
                                '<td class="text-center"><span id="subtotal-' + code + '" data-id="' + code + '">'+ element.price +'</span></td>' +
                                '<td><button id="btnDelete-' + code + '" type="button" class="btn btn-danger btn-sm" data-id="' + code + '" onclick="deleteRow(this)"><i class="mdi mdi-delete "></i></button></td>'
                            );


                        }
                        calculateTotal();
                        calculateArticulos();
                        calculateComplete();
                        return false;
                    }
                }

            });
        });

        // agregar clientes
        $('#add_customer').on('click', function() {
            let name = $('#name').val();
            let rut = $('#rut').val();
            let email = $('#email').val();
            let phone = $('#phone').val();
            let address = $('#address').val();

            if (name == '' || rut == '' ) {
                alert('Por favor rellene todos los campos');
            } else {
                $.ajax({
                    url: '{{ route("pos.storeCustomer") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        rut: rut,
                        email: email,
                        phone: phone,
                        address: address
                    },
                    success: function(data) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Se agrego correctamente el cliente',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        $('#name').val('');
                        $('#rut').val('');
                        $('#email').val('');
                        $('#phone').val('');
                        $('#address').val('');

                        $('#customer').append('<option value="' + data.id + '">' + data.rut + ' - ' + data.name + '</option>');
                        $('#customerModal').modal('hide');
                    }
                });
            }

        });

        // agrega descuento
        $('#discount').on('change', function() {
            calculateComplete();
        });
        // agrega propina
        $('#propina').on('change', function() {
            calculateComplete();
        });

        $('#paymentmethod').on('click', function() {
            $('#modalPayment').modal('show');
            $('#total_amount').text($('#totalpay').text());
            $('#amount').val($('#totalpay').text());
        });

        $('#method').on('change', function() {
            var type = $(this).val();
            if (type == 'Total') {
                $('#total_pay').removeClass('d-none');
                $('#partial_pay').addClass('d-none');
            } else if (type == 'Parcial') {
                $('#total_pay').addClass('d-none');
                $('#partial_pay').removeClass('d-none');
            } else {
                $('#total_pay').removeClass('d-none');
                $('#partial_pay').addClass('d-none');
            }
        });

        $('#paymentBy').on('change', function() {
            var type = $(this).val();
            if (type == 'Efectivo') {
                $('#efecty_pay').removeClass('d-none');
                $('#cheque_pay').addClass('d-none');
                $('#transferencia_pay').addClass('d-none');
            } else if (type == 'Cheque') {
                $('#cheque_pay').removeClass('d-none');
                $('#efecty_pay').addClass('d-none');
                $('#transferencia_pay').addClass('d-none');
            } else if (type == 'Transferencia') {
                $('#transferencia_pay').removeClass('d-none');
                $('#cheque_pay').addClass('d-none');
                $('#efecty_pay').addClass('d-none');
            } else{
                $('#efecty_pay').addClass('d-none');
                $('#cheque_pay').addClass('d-none');
                $('#transferencia_pay').addClass('d-none');
            }
        });

        $('#methodPay').on('change', function() {
            var type = $(this).val();
            if (type == 'Efectivo') {
                $('#labelNote').text('Nota de Pago');
            } else if (type == 'Cheque') {
                $('#labelNote').text('Nro de Cheque');
            } else if (type == 'Transferencia') {
                $('#labelNote').text('Nro de Transferencia');
            } else if (type == 'Tarjeta de credito') {
                $('#labelNote').text('Codigo de transaccion');
            }
            else{
                $('#labelNote').text('Nota de Pago');
            }
        });

        $('#add_partial').on('click', function() {
            let total = parseFloat($('#totalpay').text());
            let payment = $('#methodPay').val();
            let amount = parseFloat($('#importe').val());
            let details = $('#notePayment').val();

            if (total == 0) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Por favor agregue los datos de venta',
                });
                $('#modalPayment').modal('hide');

                return;
            }

            dataPartial.push({
                'payment': payment,
                'amount': amount,
                'details': details
            });


            $('#paymentTable #payment_list').append('<tr id="payment-' + payment + '"><td>' + payment + '</td><td>' + amount + '</td><td>' + details + '</td><td><button type="button" class="btn btn-danger btn-sm" id="' + payment + '" onclick="deletePartial(this)"><i class="mdi mdi-delete "></i></button></td></tr>');
            calculatePartial();
            $('#methodPay').val('seleccione').trigger('change');
            $('#importe').val('');
            $('#notePayment').val('');

        });

        $('#save_payment').on('click', function() {
            let customer        = $('#customer').val();
            let noteRef         = $('#note_ref').val();
            let tax             = $('#tax').text()
            let discount        = $('#discount').val();
            let propina         = $('#propina').val();
            let subtotal        = parseFloat($('#totalComplete').text());
            let total           = parseFloat($('#totalpay').text());
            let totalItems      = parseInt($('#totalItems').text());
            let note            = $('#note').val();
            let method          = $('#method').val();
            let paymentBy       = $('#paymentBy').val();
            let paymentspartials = JSON.stringify(dataPartial);
            let notePay         = '';
            let notePayment      = $('#notepayment').val();

            // Variable para almacenar campos incompletos
            var missingFields = [];
            if ($('#customer').val() === 'Seleccione cliente') {
                Swal.fire({
                    position: 'top-center',
                    icon: 'error',
                    title: 'Seleccione un cliente para procesar el pago',
                });

                missingFields.push('Cliente');
            }

            if ($('#method').val() === 'Total') {
                if ($('#paymentBy').val() === 'seleccione') {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: 'Seleccione la forma de pago',
                    });
                    missingFields.push('metodo');
                }
            }

            if ($('#method').val() === 'Parcial') {
                let montoActual = parseFloat($('#total_amount').text());
                let totalPartialCount = 0;
                for (let i = 0; i < dataPartial.length; i++) {
                    totalPartialCount += parseFloat(dataPartial[i].amount);
                }

                if (totalPartialCount > montoActual) {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: 'El monto de pago parcial no puede ser mayor al monto total de la venta, verifique',
                    });
                    missingFields.push('parcial');
                    return;
                }

                if (totalPartialCount < montoActual) {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: 'El monto de pago parcial no puede ser menor al monto total de la venta, verifique',
                    });
                    missingFields.push('parcial');
                    return;
                }
            }

            dataProduct.forEach(element => {
                if (element.stock < element.quantity) {
                    missingFields.push('producto:' + element.name);
                }
            });

            if ($('#notepayefecty').val() != '') {
                notePay = $('#notepayefecty').val();
            }
            if ($('#nro_cheque').val() != '') {
                notePay = $('#nro_cheque').val();
            }
            if ($('#nro_transferencia').val() != '') {
                notePay = $('#nro_transferencia').val();
            }

            $('#productosarray').val(JSON.stringify(dataProduct));
            $('#taxh').val(tax);
            $('#discounth').val(discount);
            $('#propinah').val(propina);
            $('#subtotal').val(subtotal);
            $('#total').val(total);
            $('#total_items').val(totalItems);
            $('#method_pay').val(method);
            $('#balanceh').val(parseFloat($('#balance').text()));
            $('#noteh').val(note);
            $('#amounth').val(parseFloat($('#totalpay').text()));
            $('#paymentbyh').val(paymentBy);
            $('#notepayh').val(notePay);
            $('#paypartial').val(JSON.stringify(dataPartial));
            $('#note_payment').val(noteRef);

            if (missingFields.length > 0) {
                Swal.fire({
                    position: 'top-center',
                    icon: 'error',
                    title: 'Por favor verifique los datos y productos para completar la venta',
                });
               return false;
            }
            if (missingFields.length == 0) {
                let timerInterval;
                $('#posForm').submit();
                Swal.fire({
                    title: "Por favor espere que se procese la venta",
                    html: "Solo tardara <b></b> segundos, tenga paciencia",
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                    }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {

                    }
                });

            }
        });

        $('#salir').on('click', function() {
            Swal.fire({
                title: '¿Estas seguro de salir de caja?',
                text: "Se perderan los cambios realizados",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Salir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("dashboard") }}';
                }
            })
        });
    });

    function deleteRow(dato) {
        let btnId = "#"+dato.id;
        let codeProduct = $(btnId).data('id');

        let id = '#producto-' + codeProduct;
        $(id).closest('tr').remove();
        // eliminamos el producto del array
        for (let i = 0; i < dataProduct.length; i++) {
            if (dataProduct[i].code == codeProduct) {
                dataProduct.splice(i, 1);
                break;
            }
        }
        calculateTotal();
        calculateArticulos();
        calculateComplete();
    }
    function calculateTotal() {
        let total = 0;
        for (let i = 0; i < dataProduct.length; i++) {
            total += parseFloat(dataProduct[i].subtotal);
        }
        $('#totalComplete').text(total);
    }
    function calculateArticulos() {
        let total = 0;
        for (let i = 0; i < dataProduct.length; i++) {
            total += parseFloat(dataProduct[i].quantity);
        }
        $('#totalItems').text(total);
    }
    function calculateComplete() {
        let total = 0, tax = parseFloat($('#tax').val()),
        discount = parseFloat($('#discount').val()),
        propina = parseFloat($('#propina').val());

        for (let i = 0; i < dataProduct.length; i++) {
            total += parseFloat(dataProduct[i].subtotal);
        }
        // resta el descuento
        let totalWithDiscount = total - discount + propina;
        $('#totalpay').text(totalWithDiscount);
    }
    function calculateQuantity(dato) {
        let id = '#' + dato.id;
        let productCode = $(id).data('id');
        let quantity = parseInt($(id).val());
        let subtotal = '#subtotal-' + productCode;

        if (dataProduct.length > 0) {
            let index = dataProduct.findIndex((item) => item.code == productCode);

            if (dataProduct[index].stock < quantity) {
                Swal.fire({
                    position: 'top-center',
                    icon: 'error',
                    title: 'No hay suficiente stock para el producto, que la cantidad sea igual o menor al stock',
                });

                $(id).val('1');
                return false;
            }

            dataProduct[index].quantity = quantity;
            dataProduct[index].subtotal = dataProduct[index].price * dataProduct[index].quantity;
            $(subtotal).text(dataProduct[index].subtotal);
        }

        calculateArticulos();
        calculateTotal();
        calculateComplete();
    }
    function deletePartial(payment) {
        dataPartial = dataPartial.filter((item) => item.payment != payment.id);
        let idtr = '#payment-' + payment.id;
        $(idtr).remove();
        console.log(dataPartial);
        calculatePartial();
    }

    function calculatePartial() {
        let total = 0;
        for (let i = 0; i < dataPartial.length; i++) {
            total += parseFloat(dataPartial[i].amount);
        }
    }

    function imprimir() {

        printJS({
            printable: 'printFactura',
            type: 'html',
            documentTitle: 'Factura',
            style: '*{font-size: 14px;font-family: Arial, Helvetica, sans-serif;}  @media print {@page {size: portrait;margin: 0;padding: 0;width: 280px;max-width: 280px;}}',

        });
    }
</script>
@endSection
