@extends('layouts.app')

@section('title') Productos @endsection

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
            <h4 class="mb-sm-0 font-size-18">Productos </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item "><a href="{{ route('productos.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Kardex</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header ">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title">Kardex del Producto {{ $producto->name }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a target="_blank" href="{{ route('products.kardexpdf', $producto->id) }}"
                            class="btn btn-success btn-sm ">
                            <i class="mdi mdi-file-pdf"></i>
                            Imprimir Kardex
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
            <div class="row">
                    <div class="col-md-12">
                        <p>El informe se genera solo si aplica el filtro, sino hay datos no podra generarse
                            el informe.
                        </p>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="startday">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="startday" name="startday">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="endday">Fecha Final</label>
                            <input type="date" class="form-control" id="endday" name="endday">
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-5 col-sm-6 p-0 text-center">
                        <button type="button" class="btn btn-primary mt-4" id="filter">
                            <i class="mdi mdi-filter"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-danger mt-4" id="removefilter"
                            title="Eliminar filtro">
                            <i class="mdi mdi-filter-remove"></i>
                        </button>
                        <button type="button" class="btn btn-success mt-4" id="informe">
                            <i class="mdi mdi-file-pdf"></i>
                            Generar Informe
                        </button>
                        <form id="formfilter" action="{{ route('products.kardexpdffilter', $producto->id) }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="start" id="startfilter">
                            <input type="hidden" name="end" id="endfilter">
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered nowrap w-100">
                        <thead>
                            <tr>
                            <th>Producto</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Usuario</th>
                                <th>Ingreso</th>
                                <th>Habian</th>
                                <th>Salida</th>
                                <th>Quedan</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Detalles</th>
                                <!-- <th>Producto</th>
                                <th>Fecha</th>
                                <th>Ingreso/Salida</th>
                                <th>Detalles</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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
    const table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('products.kardex', $producto->id) }}",
            data: function (d) {
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
                data: 'product_name',
                name: 'product_name',
                orderable: true,
                searchable: true
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'user_name',
                name: 'user_name'
            },
            {
                data: 'ingreso',
                name: 'ingreso'
            },
            {
                data: 'habian',
                name: 'habian'
            },
            {
                data: 'salieron',
                name: 'salieron'
            },
            {
                data: 'quedan',
                name: 'quedan'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'description',
                name: 'description'
            }

        ],
        columnDefs: [
            {
                targets: [1],
                render: function (data) {
                    return moment(data).format('DD/MM/YYYY hh:mm A');
                }
            },
            {
                targets: [2],
                render: function (data, type, row) {
                    if (data == 1) {
                        return '<h5 class="text-success">Ingreso</h5>';
                    }
                    if (data == 2) {
                        return '<h5 class="text-danger">Salida</h5>';
                    }
                    if (data == 3) {
                        return '<h5 class="text-warning">Ajuste de Inventario</h5>';
                    }
                    if (data == 4) {
                        return '<h5 class="text-danger">Eliminado</h5>';
                    }
                }
            },
            {
                targets: [8, 9],
                render: function (data, type, row) {
                    return numberFormat2.format(data);
                }
            }
        ]

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
    });
    $('#removefilter').on('click', function() {
        $('#startday').val('').trigger('change');
        $('#endday').val('').trigger('change');
        table.draw();
    });
    $('#informe').on('click', function() {
        if ($('#startday').val() != '' && $('#endday').val() != '' ) {

            if ($('#startday').val() > $('#endday').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La fecha inicial no puede ser mayor que la fecha final',
                    timer: 2500
                });
                return false;
            } else {
                $('#startfilter').val($('#startday').val());
                $('#endfilter').val($('#endday').val());
                $('#formfilter').submit();
            }

        }
    });
</script>
@endSection
