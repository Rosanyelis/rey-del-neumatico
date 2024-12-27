@extends('layouts.app')

@section('title') Ordenes de Trabajo @endsection

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
            <h4 class="mb-sm-0 font-size-18">Ordenes de Trabajo </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Ordenes de Trabajo</li>
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
                <h6>Ordenes de T.</h6>
                <h5 id="total">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Total de OT</h6>
                <h5 id="total_monto">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Completadas</h6>
                <h5 id="completadas">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Pendientes</h6>
                <h5 id="pendientes">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>En Proceso</h6>
                <h5 id="en_proceso">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Canceladas</h6>
                <h5 id="canceladas">0</h5>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header ">
                <div class="row">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('ordenes-trabajo.create') }}"
                            class="btn btn-primary btn-sm ">
                            <i class="mdi mdi-plus"></i>
                            Nueva Orden de Trabajo
                        </a>
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
                                <th>N° Orden</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Creado Por</th>
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
                                <h5 class="modal-title" id="myModalLabel">Ver Orden de Trabajo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Cliente:</strong> <span id="name"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>N° de Orden:</strong> <span id="nfactura"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Fecha de Orden:</strong> <span id="date"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total :</strong> <span id="totals"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <strong>Estatus :</strong> <span id="status"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Detalles :</strong> <span id="detalles"></span>
                                    </div>
                                    <hr>
                                    <div class="w-100"></div>
                                    <div class="col-md-12 text-center">
                                        <h4>Detalles de Vehiculo</h4>
                                    </div>
                                    <hr>
                                    <div class="w-100"></div>
                                    <div class="col-md-6">
                                        <strong>Marca :</strong> <span id="marca"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Modelo:</strong> <span id="modelo"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Patente de vehiculo :</strong> <span id="patente"></span>
                                    </div>
                                    <hr>
                                    <div class="col-md-12 text-center">
                                        <h4>Servicios</h4>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-bordered nowrap w-100">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Servicio</th>
                                                    <th>Detalles</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="details" class="text-center">
                                            </tbody>
                                            <tfoot>
                                                <tr></tr>
                                                    <td colspan="4" class="text-end">Total</td>
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
                <form id="my-form" action="{{ route('ordenes-trabajo.destroy') }}" method="POST">
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
    function totalot() {
        $.ajax({
            url: "{{ route('ordenes-trabajo.totalWorkOrder') }}",
            type: "GET",
            dataType: 'json',
            success: function(data) {
                $('#total').html(numberFormat2.format(data.total));
                $('#total_monto').html(numberFormat2.format(data.total_monto));
                $('#completadas').html(numberFormat2.format(data.statusCompletado));
                $('#pendientes').html(numberFormat2.format(data.statusPendiente));
                $('#en_proceso').html(numberFormat2.format(data.statusEnProceso));
                $('#canceladas').html(numberFormat2.format(data.statusCancelado));
            }
        });
    }

    totalot();
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('ordenes-trabajo.index') }}",
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
                data: 'customer',
                name: 'customer'
            },
            {
                data: 'correlativo',
                name: 'correlativo'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'user',
                name: 'user'
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
            targets: 3,
            render: function (data) {
                return '$ ' + numberFormat2.format(data);
            }
        },
        {
            targets: 4,
            render: function (data, type, row) {
                if (data == 'Pendiente') {
                    return `
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            Pendiente <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="dropdown-header noti-title">
                                <h5 class="font-size-13 text-muted text-truncate mn-0">Cambiar Status</h5>
                            </div>
                            <!-- item-->
                            <a class="dropdown-item" href="#" onclick="changeStatus('En Proceso', ${row.id})">En Proceso</a>
                            <a class="dropdown-item" href="#" onclick="changeStatus('Completado', ${row.id})">Completado</a>
                            <a class="dropdown-item" href="#" onclick="changeStatus('Cancelado', ${row.id})">Cancelado</a>
                        </div>
                    </div>
                    `;
                } else if (data == 'En Proceso') {
                    return `
                       <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            En Proceso <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="dropdown-header noti-title">
                                <h5 class="font-size-13 text-muted text-truncate mn-0">Cambiar Status</h5>
                            </div>
                            <!-- item-->
                            <a class="dropdown-item" href="#" onclick="changeStatus('Completado', ${row.id})">Completado</a>
                            <a class="dropdown-item" href="#" onclick="changeStatus('Cancelado', ${row.id})">Cancelado</a>
                        </div>
                    </div>`;
                } else if (data == 'Completado') {
                    return '<p class="badge bg-success">Completado</p>';
                } else if (data == 'Cancelado') {
                    return '<p class="badge bg-danger">Cancelado</p>';
                }
            }
        }
        ],
    });


    function viewRecord(id) {
        $.ajax({
            url: "{{ route('ordenes-trabajo.show', ':id') }}"
                .replace(':id', id),
            type: 'GET',
            success: function(res) {
                console.log(res);

                $('#name').text(res.customer.name);
                $('#date').text(moment(res.created_at).format('DD/MM/YYYY hh:mm A'));
                $('#totals').text(numberFormat2.format(res.total));
                $('#nfactura').text(res.correlativo);
                $('#total2').text(numberFormat2.format(res.total));
                $('#status').text(res.status);
                $('#marca').text(res.marca);
                $('#patente').text(res.patente_vehiculo);
                $('#modelo').text(res.modelo);
                $('#detalles').text(res.details);

                $('#details').empty();

                res.items.forEach((value, index) => {
                    $('#details')
                        .append('<tr>')
                        .append('<td>' + value.product.name + '</td>')
                        .append('<td>' + value.details + '</td>')
                        .append('<td>' + value.quantity + '</td>')
                        .append('<td>' + numberFormat2.format(value.price) + '</td>')
                        .append('<td>' + numberFormat2.format(value.total) + '</td>')
                        .append('</tr>');
                })

                $('#myModal').modal('show');
            }
        });

    }

    function changeStatus(status, id) {
        $('#my-form #status').val(status);
        $('#my-form #id').val(id);

        Swal.fire({
            title: '¿Esta seguro de cambiar el estado de la orden de trabajo?',
            text: "No podra cambiar el estado si es cancelado o completado!. Al completar la orden los productos seran descontados del inventario.",
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
    })
</script>
@endSection
