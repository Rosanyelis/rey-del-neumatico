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

<script src="{{ asset('pagesjs/pos.js') }}"></script>
@endSection
