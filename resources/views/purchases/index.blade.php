@extends('layouts.app')

@section('title') Compras @endsection

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
            <h4 class="mb-sm-0 font-size-18">Compras </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Compras</li>
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
                <h6>Compras</h6>
                <h5 id="total">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Total</h6>
                <h5 id="totalpurchase">0</h5>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header ">
                <div class="row">
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="startday" name="startday">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="endday" name="endday">
                    </div>
                    <div class="col-md-2">
                        <select name="proveedor" id="proveedor" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($suppliers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" id="filter">
                            <i class="mdi mdi-filter"></i> Filtrar
                        </button>

                        <button type="button" class="btn btn-danger" id="removefilter"
                            title="Eliminar filtro">
                            <i class="mdi mdi-filter-remove"></i>
                        </button>
                        <button type="button" class="btn btn-success" id="informe">
                            <i class="mdi mdi-file-pdf"></i> Generar Informe</button>
                        <form id="formfilter" action="{{ route('compras.generateInformefilter') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="supplier_id" id="proveedorfilter">
                            <input type="hidden" name="desde" id="desdefilter">
                            <input type="hidden" name="hasta" id="hastafilter">
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive  w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th>N° factura</th>
                                <th>Tipo</th>
                                <th>Total</th>
                                <th>Nota</th>
                                <th>Estatus</th>
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
                                <h5 class="modal-title" id="myModalLabel">Ver Compra</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Proveedor:</strong> <span id="name"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Fecha de Compra:</strong> <span id="date"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total :</strong> <span id="total"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>N° de factura:</strong> <span id="nfactura"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>¿Recibido? :</strong> <span id="recibido"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Tipo de compra :</strong> <span id="type"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Notas:</strong> <span id="note"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Archivos:</strong> <span id="files"></span>
                                    </div>
                                    <hr>
                                    <div class="col-md-12 text-center">
                                        <h4>Productos</h4>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-bordered nowrap w-100">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Articulo</th>
                                                    <th>Cantidad</th>
                                                    <th>Costo</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="details" class="text-center">
                                            </tbody>
                                            <tfoot>
                                                <tr></tr>
                                                    <td colspan="3" class="text-end">Total</td>
                                                    <td id="total2"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
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
                <form id="my-form" action="{{ route('compras.changeStatus') }}" method="POST">
                    @csrf
                    <input type="hidden" id="id" name="id" >
                    <input type="hidden" id="status" name="status">
                </form>
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
    const basepath = "{{ asset('assets/images/') }}";
    const baseStorage = "{{ asset('') }}";
    const numberFormat2 = new Intl.NumberFormat('de-DE');

    function totalPurchase() {
        $.ajax({
            url: "{{ route('compras.totalPurchases') }}",
            type: "POST",
            data: {
                start: $('#startday').val(),
                end: $('#endday').val(),
                supplier_id: $('#proveedor').val(),
                _token: "{{ csrf_token() }}"
            },
            dataType: "JSON",
            success: function(data) {
                console.log(data);

                $('#total').html(numberFormat2.format(data.total));
                $('#totalpurchase').html(numberFormat2.format(data.total_monto));
            }
        });
    }

    totalPurchase();

    const table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('compras.datatable') }}",
            data: function (d) {
                d.supplier_id = $('#proveedor').val();
                d.start = $('#startday').val();
                d.end = $('#endday').val();
            }
        },
        dataType: 'json',
        type: "POST",
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        responsive: true,
        language: {
            url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json",
        },
        columns: [
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'supplier',
                name: 'supplier'
            },
            {
                data: 'reference',
                name: 'reference'
            },
            {
                data: 'type_purchase',
                name: 'type_purchase'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'note',
                name: 'note'
            },
            {
                data: 'received',
                name: 'received'
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
            render: function (data) {
                return moment(data).format('DD/MM/YYYY hh:mm A');
            }
        },
        {
            targets: 4,
            render: function (data) {
                return '$ ' + numberFormat2.format(data);
            }
        },
        {
            targets:6,
            render: function (data, type, row) {
                if (data == 0) {
                    return `
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            No Recibido <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="dropdown-header noti-title">
                                <h5 class="font-size-13 text-muted text-truncate mn-0">Cambiar Status</h5>
                            </div>
                            <!-- item-->
                            <a class="dropdown-item" href="#" onclick="changeStatus(1, ${row.id})">Recibido</a>
                        </div>
                    </div>
                    `;
                } else if (data == 1) {
                    return '<p class="badge bg-success">Recibido</p>';
                }
            }
        }],
    });

    $('#filter').on('click', function() {
        if ($('#startday').val() > $('#endday').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La fecha inicial no puede ser mayor que la fecha final',
                timer: 1500
            });
            return false;
        }
        table.draw();
        totalPurchase();
    });

    $('#informe').on('click', function() {
        if ($('#proveedor').val() != '' && $('#startday').val() == '' && $('#endday').val() == '') {
            $('#proveedorfilter').val($('#proveedor').val());
            $('#formfilter').submit();
        }

        if ($('#startday').val() != '' && $('#endday').val() != '' && $('#proveedor').val() == '') {
            if ($('#startday').val() > $('#endday').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La fecha inicial no puede ser mayor que la fecha final',
                    timer: 1500
                });
                return false;
            }
            $('#desdefilter').val($('#startday').val());
            $('#hastafilter').val($('#endday').val());
            $('#formfilter').submit();
        }

        if ($('#startday').val() != '' && $('#endday').val() != '' && $('#proveedor').val() != '') {
            if ($('#startday').val() > $('#endday').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La fecha inicial no puede ser mayor que la fecha final',
                    timer: 1500
                });
                return false;
            }
            $('#proveedorfilter').val($('#proveedor').val());
            $('#desdefilter').val($('#startday').val());
            $('#hastafilter').val($('#endday').val());
            $('#formfilter').submit();
        }

        if ($('#startday').val() == '' && $('#endday').val() == '' && $('#proveedor').val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor selecciona un filtro',
                timer: 1500
            });
        }

    });

    function viewRecord(id) {
        $.ajax({
            url: "{{ route('compras.show', ':id') }}"
                .replace(':id', id),
            type: 'GET',
            success: function(res) {
                $('#name').text(res.supplier.name);
                $('#date').text(moment(res.created_at).format('DD/MM/YYYY hh:mm A'));
                $('#total').text(res.total);
                $('#note').text(res.note);
                $('#total2').text(res.total);
                if (res.received == '1') {
                    $('#recibido').text('Recibido');
                } else {
                    $('#recibido').text('No Recibido');
                }
                $('#type').text(res.type_purchase);
                $('#nfactura').text(res.reference);
                if (res.files != '') {
                    $('#files').append('<a href="' + baseStorage + res.files + '" class="btn btn-info" download target="_blank">Descargar Archivo</a>');
                }
                $('#details').empty();

                res.purchase_items.forEach((value, index) => {
                    $('#details')
                        .append('<tr>')
                        .append('<td>' + value.product.name + '</td>')
                        .append('<td>' + value.quantity + '</td>')
                        .append('<td>' + value.cost + '</td>')
                        .append('<td>' + value.subtotal + '</td>')
                        .append('</tr>');
                })

                $('#myModal').modal('show');
            }
        });

    }

    function deleteRecord(id) {
        Swal.fire({
            title: '¿Esta seguro de eliminar esta Compra?',
            text: "No podra recuperar la información!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    "{{ route('compras.destroy', ':id') }}"
                    .replace(':id', id);
            }
        })
    }

    $('#close').on('click', function() {
        $('#myModal').modal('hide');
        $('#name').text('');
        $('#date').text('');
        $('#total').text('');
        $('#note').text('');
        $('#total2').text('');
        $('#received').text('');
        $('#nfactura').text('');
        $('#details').empty();
    });

    function changeStatus(status, id) {
        $('#my-form #status').val(status);
        $('#my-form #id').val(id);

        Swal.fire({
            title: '¿Esta seguro de cambiar el estado de la compra?',
            text: "Los productos de la compra seran ingresados al stock al marcarlo como recibido, y el precio de compra sera actualizado en el precio de costo del mismo producto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, cambiar!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#my-form').submit();

            }
        })
    }
</script>
@endSection
