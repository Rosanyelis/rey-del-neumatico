@extends('layouts.app')

@section('title') Ventas @endsection

@section('css')
<!-- DataTables -->
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
    rel="stylesheet" type="text/css" />
<link
    href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
    rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link
    href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
    rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ventas por Mes y Caja </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Ventas por Mes y Caja</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Ventas</h6>
                <h5 id="total">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Propina</h6>
                <h5 id="totalpropina">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Tarjeta</h6>
                <h5 id="totaltarjeta">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Efectivo</h6>
                <h5 id="totalefectivo">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Cheque</h6>
                <h5 id="totalcheque">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Transferencia</h6>
                <h5 id="totaltransferencia">0</h5>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header ">
                <div class="row py-0">
                    <div class="col-md-2">
                        <select name="month" id="month" class="form-control">
                            <option value="">Seleccione mes</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="vendedor" id="vendedor" class="form-control">
                            <option value="Todos">Todos los vendedores</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="button" class="btn btn-primary " id="filter">
                            <i class="mdi mdi-filter"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-danger " id="removefilter"
                            title="Eliminar filtro">
                            <i class="mdi mdi-filter-remove"></i>
                        </button>
                        <button type="button" class="btn btn-success " onclick="generateReport()">
                            <i class="mdi mdi-file-pdf"></i>
                            Generar Informe
                        </button>
                        <form id="formfilter" action="{{ route('ventas.generateInformexmes') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="month" id="monthfilter">
                            <input type="hidden" name="user_id" id="userfilter">
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Forma de pago</th>
                                <th>Vendedor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- sample modal content -->
                <div id="myModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true"
                    data-bs-scroll="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Ver Venta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row gy-3">
                                    <div class="col-md-4">
                                        <strong>Cliente:</strong> <br>
                                        <span id="name"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Vendedor:</strong> <br>
                                        <span id="user"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Fecha:</strong> <br>
                                        <span id="date"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Descuento:</strong> <br>
                                        <span id="discount"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Propina:</strong> <br>
                                        <span id="propina"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Forma de Pago:</strong> <br>
                                        <span id="forma_pago"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Total:</strong> <br>
                                        <span id="totalf"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Notas:</strong> <span id="note"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Notas de pago:</strong> <span id="notepay"></span>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <table id="productstbl" class="table table-bordered table-striped table-sm dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th colspan="5">Detalles de Venta</th>
                                                </tr>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productstbody">
                                            </tbody>
                                            <tfoot>
                                                <th colspan="4" class="text-end"><h5>Total</h5></th>
                                                <th colspan="4"> <span id="total1"></span> </th>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="col-md-12 text-center">
                                            <table id="paymentstbl" class="table table-bordered dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Metodo de Pago</th>
                                                        <th>Monto</th>
                                                        <th>Notas o Referencias</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="paymentstbody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect"
                                    data-bs-dismiss="modal" id="close">Cerrar</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endSection

@section('scripts')
<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}">
</script>
<!-- Buttons examples -->
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}">
</script>
<script
    src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}">
</script>
<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}">
</script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}">
</script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}">
</script>

<!-- Responsive examples -->
<script
    src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
</script>
<script
    src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
</script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<!-- Datatable init js -->
<script>
    const numberFormat2 = new Intl.NumberFormat('de-DE');

    function totalSales() {
        $.ajax({
            url: "{{ route('ventas.totalSalesxmonth') }}",
            type: "POST",
            data: {
                month: $('#month').val(),
                user_id: $('#vendedor').val(),
                _token: "{{ csrf_token() }}"
            },
            dataType: "JSON",
            success: function(data) {
                $('#total').html(numberFormat2.format(data.total));
                $('#totalpropina').html(numberFormat2.format(data.totalpropina));
                $('#totaltarjeta').html(numberFormat2.format(data.totalcredito));
                $('#totalefectivo').html(numberFormat2.format(data.totalefectivo));
                $('#totalcheque').html(numberFormat2.format(data.totalcheque));
                $('#totaltransferencia').html(numberFormat2.format(data.totaltransferencia));
            }
        });
    }

    totalSales();

    const table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('ventas.datatablexmonth') }}",
            data: function(d) {
                d.month = $('#month').val();
                d.user_id = $('#vendedor').val();
            }
        },
        dataType: 'json',
        type: "POST",
        responsive: true,
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, "All"]
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json",
        },
        columns: [
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'customer_name',
                name: 'customer_name',
                orderable: true,
                searchable: true
            },
            {
                data: 'grand_total',
                name: 'grand_total'
            },
            {
                data: 'payment_method',
                name: 'payment_method'
            },
            {
                data: 'user_name',
                name: 'user_name'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            },
        ],
        columnDefs: [{
                targets: 0,
                render: function(data) {
                    return moment(data).format('DD/MM/YYYY hh:mm A');
                }
            },
            {
                targets: 2,
                render: function(data) {
                    return '$ ' + numberFormat2.format(data);
                }
            }
        ]
    });

    $('#filter').on('click', function() {
        totalSales();
        table.draw();

    });

    $('#removefilter').on('click', function() {

        $('#month').val('').trigger('change');
        $('#vendedor').val('').trigger('change');
        table.draw();
        totalSales();
    });

    function generateReport() {
        $('#monthfilter').val($('#month').val());
        $('#userfilter').val($('#vendedor').val());
        $('#formfilter').submit();
    }

    function viewRecord(id) {
        $.ajax({
            url: "{{ route('ventas.show', ':id') }}"
                .replace(':id', id),
            type: 'GET',
            success: function(res) {
                console.table(res);

                $('#name').text(res.customer_name);
                $('#user').text(res.user.name);
                $('#totalf').text(numberFormat2.format(res.grand_total));
                $('#propina').text(numberFormat2.format(res.perquisite));
                $('#date').text(moment(res.created_at).format('DD/MM/YYYY hh:mm A'));
                if (res.payment_status == 'paid') {
                    $('#forma_pago').text('Pago Total');
                } else {
                    $('#forma_pago').text('Pago Parcial');
                }

                $('#note').text(res.note);
                $('#notepay').text(res.note_pay);
                $('#discount').text(numberFormat2.format(res.total_discount));

                res.saleitems.forEach((value, index) => {
                    $('#productstbl #productstbody')
                        .append('<tr>')
                        .append('<td>' + value.product_code + '</td>')
                        .append('<td>' + value.product_name + '</td>')
                        .append('<td>' + value.quantity + '</td>')
                        .append('<td>' + numberFormat2.format(value.unit_price) + '</td>')
                        .append('<td>' + numberFormat2.format(value.subtotal) + '</td>')
                        .append('</tr>');
                });

                $('#productstbl #total1').text(numberFormat2.format(res.grand_total));

                res.payments.forEach((value, index) => {
                    $('#paymentstbl #paymentstbody')
                        .append('<tr>')
                        .append('<td>' + value.payment_method + '</td>')
                        .append('<td>' + numberFormat2.format(value.pos_paid) + '</td>')
                        .append('<td>' + value.reference + '</td>')
                        .append('</tr>');
                });

                $('#myModal').modal('show');
            }
        });
    }

    $('#close').on('click', function() {
        $('#myModal').modal('hide');
        $('#name').text('');
        $('#user').text('');
        $('#total').text('');
        $('#date').text('');
        $('#forma_pago').text('');
        $('#note').text('');
        $('#notepay').text('');
        $('#tax').text('');
        $('#discount').text('');

        $('#productstbl #productstbody').empty();
        $('#paymentstbl #paymentstbody').empty();

        $('#total1').text('');
    })
</script>
@endSection
