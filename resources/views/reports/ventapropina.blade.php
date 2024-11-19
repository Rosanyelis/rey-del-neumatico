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
                        <h4 class="card-title">Listado de Ventas Totales con Propinas</h4>
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
                            <label for="vendedor">Vendedor</label>
                            <select name="vendedor" id="vendedor" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
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
                        <form id="formfilter" action="{{ route('reportes.informeVentasxdiaPdf') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="day" id="dayfilter">
                            <input type="hidden" name="user" id="userfilter">
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Propina</th>
                                <th>Total</th>
                                <th>Vendedor</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end"><h5>Totales:</h5></th>
                                <th></th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
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
            url: "{{ route('reportes.datatableVentasxDia') }}",
            data: function(d) {
                d.dateday = $('#dateday').val();
                d.vendedor = $('#vendedor').val();
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
                data: 'customer_name',
                name: 'customer_name'
            },
            {
                data: 'perquisite',
                name: 'perquisite'
            },
            {
                data: 'grand_total',
                name: 'grand_total'
            },
            {
                data: 'user_name',
                name: 'user_name'
            }
        ],
        columnDefs: [{
                targets: 0,
                render: function(data) {
                    return moment(data).format('DD/MM/YYYY hh:mm A');
                }
            },
            {
                targets: [2, 3],
                render: function(data) {
                    return '$ ' + numberFormat2.format(data);
                }
            },
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            // Elimine el formato para obtener datos enteros para la suma
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total de Propina en todas las páginas
            totalpropina = api
                .column(2)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            totalventa = api
                .column(3)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );


            // Actualizar pie de página
            $( api.column( 2 ).footer() ).html('<h5>$ '+ numberFormat2.format(totalpropina) +'</h5>');
            $( api.column( 3 ).footer() ).html('<h5>$ '+ numberFormat2.format(totalventa) +'</h5>');
            // $('#total').html('Total: ' + total.toFixed(2));
        }
    });

    $('#filter').on('click', function() {
        if ($('#dateday').val() != '' && $('#vendedor').val() != 'Seleccione') {
            table.draw();
        }
        if ($('#dateday').val() != '' && $('#vendedor').val() == 'Seleccione') {
            table.draw();
        }
    });

    $('#removefilter').on('click', function() {
        $('#dateday').val('').trigger('change');
        $('#vendedor').val('').trigger('change');
        table.draw();
    });

    $('#informe').on('click', function() {
        if ($('#dateday').val() != '' && $('#vendedor').val() != 'Seleccione') {
            $('#dayfilter').val($('#dateday').val());
            $('#userfilter').val($('#vendedor').val());
            $('#formfilter').submit();
        }
        if ($('#dateday').val() != '' && $('#vendedor').val() == 'Seleccione') {
            $('#dayfilter').val($('#dateday').val());
            $('#formfilter').submit();
        }
    });

</script>
@endSection
