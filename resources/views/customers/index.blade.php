@extends('layouts.app')

@section('title') Clientes @endsection

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
            <h4 class="mb-sm-0 font-size-18">Clientes </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Clientes</li>
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
                        <h4 class="card-title">Listado de Clientes</h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('clientes.create') }}"
                            class="btn btn-primary btn-sm float-end">
                            <i class="mdi mdi-plus"></i>
                            Nuevo Cliente
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tienda</th>
                                <th>Cliente</th>
                                <th>RUT</th>
                                <th>Celular</th>
                                <th>Email</th>
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
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Ver Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Cliente:</strong> <span id="name"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Tienda:</strong> <span id="store"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>RUT:</strong> <span id="rut"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Celular:</strong> <span id="phone"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Email:</strong> <span id="email"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Dirección:</strong> <span id="address"></span>
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

<!-- Datatable init js -->
<script>
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('clientes.index') }}",
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
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'store_name',
                name: 'store_name'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'rut',
                name: 'rut'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'email',
                name: 'email'
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
            visible: false
        }, ]
    });



    function viewRecord(id) {
        $.ajax({
            url: "{{ route('clientes.show', ':id') }}"
                .replace(':id', id),
            type: 'GET',
            success: function(res) {
                $('#name').text(res.name);
                $('#store').text(res.store_name);
                $('#rut').text(res.rut);
                $('#phone').text(res.phone);
                $('#email').text(res.email);
                $('#address').text(res.address);
                $('#myModal').modal('show');
            }
        });
    }

    function deleteRecord(id) {
        Swal.fire({
            title: '¿Esta seguro de eliminar este Cliente?',
            text: "No podra recuperar la información!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    "{{ route('clientes.destroy', ':id') }}"
                    .replace(':id', id);
            }
        })
    }

    $('#close').on('click', function() {
        $('#myModal').modal('hide');
        $('#name').text('');
        $('#store').text('');
        $('#rut').text('');
        $('#phone').text('');
        $('#email').text('');
        $('#address').text('');
    })
</script>
@endSection
