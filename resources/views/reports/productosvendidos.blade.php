@extends('layouts.app')

@section('title') Reportes @endsection

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
            <h4 class="mb-sm-0 font-size-18">Reportes </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Reportes</li>
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
                        <h4 class="card-title">Listado de Productos Vendidos </h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="dateday">Día</label>
                            <input type="date" class="form-control" id="dateday" name="dateday">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="type">Tipo de Producto</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($types as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                        <form id="formfilter" action="{{ route('reportes.pdfVentasxDiaxProducto') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="day" id="dayfilter">
                            <input type="hidden" name="type" id="typefilter">
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nro.Documento</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Cant.</th>
                                <th>Monto</th>
                                <th>Metodo de Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- sample modal content -->
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
            url: "{{ route('reportes.datatableVentasxDiaxProducto') }}",
            data: function(d) {
                d.dateday = $('#dateday').val();
                d.type = $('#type').val();
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
                data: 'id',
                name: 'id'
            },
            {
                data: 'product_name',
                name: 'product_name'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'quantity',
                name: 'quantity'
            },
            {
                data: 'subtotal',
                name: 'subtotal'
            },
            {
                data: 'payment',
                name: 'payment'
            }
        ],
        columnDefs: [{
                targets: 0,
                render: function(data) {
                    return moment(data).format('DD/MM/YYYY ');
                }
            },
            {
                targets: 1,
                render: function(data) {
                    return '#00000' + data;
                }
            },
            {
                targets: [5],
                render: function(data) {
                    return '$ ' + numberFormat2.format(data);
                }
            },
        ]
    });

    $('#filter').on('click', function() {
        if ($('#dateday').val() != '' && $('#type').val() != 'Seleccione') {
            table.draw();
        }
        if ($('#dateday').val() != '' && $('#type').val() == 'Seleccione') {
            table.draw();
        }
    });

    $('#removefilter').on('click', function() {
        $('#dateday').val('').trigger('change');
        $('#type').val('').trigger('change');
        table.draw();
    });

    $('#informe').on('click', function() {
        if ($('#dateday').val() != '' && $('#type').val() != 'Seleccione') {
            $('#dayfilter').val($('#dateday').val());
            $('#typefilter').val($('#type').val());
            $('#formfilter').submit();
        }
        if ($('#dateday').val() != '' && $('#type').val() == 'Seleccione') {
            $('#dayfilter').val($('#dateday').val());
            $('#formfilter').submit();
        }
    });

</script>
@endSection
