@extends('layouts.pos')

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
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th width="50px" >N° Orden</th>
                                <th width="150px">Patente</th>
                                <th>Detalles</th>
                                <th width="100px">Estado</th>
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
    const basepath = "{{ asset('assets/images/') }}";
    const baseStorage = "{{ asset('') }}";
    const numberFormat2 = new Intl.NumberFormat('de-DE');

    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('monitor.index') }}",
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
                data: 'correlativo',
                name: 'correlativo'
            },
            {
                data: 'patente_vehiculo',
                name: 'patente_vehiculo'
            },
            {
                data: 'details',
                name: 'details'
            },
            {
                data: 'status',
                name: 'status'
            },
        ],
        columnDefs: [
        {
            targets: 3,
            render: function (data, type, row) {
                if (data == 'Pendiente') {
                    return '<span class="badge bg-secondary " style="font-size: 14px">Pendiente</span>';
                } else if (data == 'En Proceso') {
                    return '<span class="badge bg-info " style="font-size: 14px">Pendiente</span>';
                } else if (data == 'Completado') {
                    return '<span class="badge bg-success" style="font-size: 14px">Completado</span>';
                } else if (data == 'Cancelado') {
                    return '<span class="badge bg-danger" style="font-size: 14px">Cancelado</span>';
                }
            }
        }
        ],
    });


</script>
@endSection
