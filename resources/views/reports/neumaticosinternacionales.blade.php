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
                    <li class="breadcrumb-item active">Neumaticos Int. Vendidos</li>
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
                <h6>Total Neumaticos</h6>
                <h5 id="total">0</h5>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card">
            <div class="card-body text-center text-uppercase">
                <h6>Peso Total</h6>
                <h5 id="totalpeso">0</h5>
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
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary" id="filter">
                            <i class="mdi mdi-filter"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-danger" id="removefilter"
                            title="Eliminar filtro">
                            <i class="mdi mdi-filter-remove"></i>
                        </button>
                        <button type="button" class="btn btn-success" id="informe">
                            <i class="mdi mdi-file-pdf"></i>
                            Generar Informe
                        </button>
                        <button type="button" class="btn btn-warning " onclick="generateReportexcel()">
                            <i class="mdi mdi-file-excel"></i>
                            Generar Excel
                        </button>
                        <form id="formfilter" action="{{ route('reportes.pdfNeumaticosInternacionales') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="start" id="startfilter">
                            <input type="hidden" name="end" id="endfilter">
                        </form>
                        <form id="formfilter" action="{{ route('reportes.pdfNeumaticosInternacionales') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="start" id="startfilter">
                            <input type="hidden" name="end" id="endfilter">
                        </form>
                        <form id="formexcelfilter" action="{{ route('reportes.NeumaticosInternacionalesExcel') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="start" id="startfilter">
                            <input type="hidden" name="end" id="endfilter">
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
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th>SubTotal</th>
                                <th>Peso</th>
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

    function totalNeumaticos() {

        $.ajax({
            url: "{{ route('reportes.totalneumaticos') }}",
            type: "POST",
            data: {
                start: $('#startday').val(),
                end: $('#endday').val(),
                _token: "{{ csrf_token() }}"
            },
            dataType: "JSON",
            success: function(data) {
                $('#total').html(numberFormat2.format(data.total_neumaticos));
                $('#totalpeso').html(data.total_peso.toFixed(2) + ' kg');
            }
        });
    }

    totalNeumaticos();
    const table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('reportes.datatableNeumaticosInternacionales') }}",
            data: function(d) {
                if ($('#startday').val() != '' && $('#endday').val() != '') {
                    d.start = $('#startday').val();
                    d.end = $('#endday').val();
                }
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
                data: 'price',
                name: 'price'
            },
            {
                data: 'subtotal',
                name: 'subtotal'
            },
            {
                data: 'weight',
                name: 'weight'
            }
        ],
        columnDefs: [{
                targets: 0,
                render: function(data) {
                    return moment(data).format('DD/MM/YYYY ');
                }
            },
            {
                targets: [4, 5],
                render: function(data) {
                    return '$ ' + numberFormat2.format(data);
                }
            },
        ],

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
        totalNeumaticos();
    });

    $('#removefilter').on('click', function() {
        $('#startday').val('').trigger('change');
        $('#endday').val('').trigger('change');
        table.draw();
        totalNeumaticos();
    });

    $('#informe').on('click', function() {
        if ($('#startday').val() > $('#endday').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La fecha inicial no puede ser mayor que la fecha final',
                timer: 1500
            });
            return false;
        }
        $('#startfilter').val($('#startday').val());
        $('#endfilter').val($('#endday').val());
        $('#formfilter').submit();

    });

    generateReportexcel = () => {
        $('#startfilter').val($('#startday').val());
        $('#endfilter').val($('#endday').val());
        $('#formexcelfilter').submit();
    }

</script>
@endSection
