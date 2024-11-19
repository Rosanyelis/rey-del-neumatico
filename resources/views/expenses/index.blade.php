@extends('layouts.app')

@section('title') Gastos @endsection

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
            <h4 class="mb-sm-0 font-size-18">Gastos </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Gastos</li>
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
                        <h4 class="card-title">Listado de Gastos</h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('gastos.create') }}"
                            class="btn btn-primary btn-sm float-end">
                            <i class="mdi mdi-plus"></i>
                            Nuevo Gasto
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
                                <th>Referencia</th>
                                <th>Monto</th>
                                <th>Creado por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th colspan="2" class="text-end"><h5>Total de Gastos</h5></th>
                            <th colspan="3"></th>
                        </tfoot>
                    </table>
                </div>
                <!-- sample modal content -->
                <div id="myModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true"
                    data-bs-scroll="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Ver Gasto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Referencia:</strong> <span id="name"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Fecha:</strong> <span id="date"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Creado por:</strong> <span id="created_by"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Monto:</strong> <span id="amount"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Archivo:</strong> <span id="file"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Nota:</strong> <span id="note"></span>
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
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('gastos.index') }}",
        dataType: 'json',
        type: "POST",
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, "All"]
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
                data: 'name',
                name: 'name'
            },
            {
                data: 'amount',
                name: 'amount'
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
        }],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            // Elimine el formato para obtener datos enteros para la suma
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total en todas las páginas
            total = api
                .column(2)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total en esta página
            pageTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Actualizar pie de página
            $( api.column( 2 ).footer() ).html('<h5>$'+ total.toFixed(0) +'</h5>');
            // $('#total').html('Total: ' + total.toFixed(2));
        }
    });

    function viewRecord(id) {
        $.ajax({
            url: "{{ route('gastos.show', ':id') }}"
                .replace(':id', id),
            type: 'GET',
            success: function(res) {
                $('#name').text(res.name);
                $('#store').text(res.store_name);
                $('#date').text(moment(res.created_at).format('DD/MM/YYYY hh:mm A'));
                $('#amount').text(res.amount);
                $('#created_by').text(res.user_name);
                $('#note').text(res.note);
                if (res.file != null) {
                    $('#file').html(
                        '<a href="' + res.file +
                        '" target="_blank">Ver Archivo</a>'
                    );
                }
                $('#myModal').modal('show');
            }
        });
    }

    function deleteRecord(id) {
        Swal.fire({
            title: '¿Esta seguro de eliminar este Gasto?',
            text: "No podra recuperar la información!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    "{{ route('gastos.destroy', ':id') }}"
                    .replace(':id', id);
            }
        })
    }

    $('#close').on('click', function() {
        $('#myModal').modal('hide');
        $('#name').text('');
        $('#store').text('');
        $('#date').text('');
        $('#amount').text('');
        $('#created_by').text('');
        $('#note').text('');
        $('#file').html('');
    })
</script>
@endSection
