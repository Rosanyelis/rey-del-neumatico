@extends('layouts.app')

@section('title') Dashboard @endsection

@section('css')
<!-- plugin css -->
<link
    href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}"
    rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Bienvenido !</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bienvenido !</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2)
<div class="row">

    <div class="col-xl-4 col-md-4 col-sm-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="">
                        <h4 class="text-muted mb-3 lh-1 d-block text-truncate">Ventas de Mes</h4>
                        <h5 class="mb-3 text-success">
                            CLP<span>
                                {{ number_format($totalMes, 0, ',', '.') }}</span>
                        </h3>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-4 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Inventario Total</span>
                        <h5 class="mb-3">Prod.:
                            {{ number_format($totalProductos, 0, ',', '.') }}
                        </h5>
                        <h6 class="mb-3 text-success">CLP
                            {{ number_format($totalMontoProductos, 0, ',', '.') }}
                        </h6>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-4 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Cotizaciones</span>
                        <h4 class="mb-3">
                            {{ number_format($totalQuote, 0, ',', '.') }}
                        </h4>
                        <h6 class="mb-3 text-success">CLP
                            {{ number_format($totalMontoQuote, 0, ',', '.') }}
                        </h6>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-4 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Ventas Acumuladas</span>
                        <h4 class="mb-3 text-success">
                            CLP
                            <span>{{ number_format($totalAcumuladasventas, 0, ',', '.') }}</span>
                        </h4>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-4 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Ordenes de Trabajo</span>
                        <h4 class="mb-3">
                            {{ number_format($totalot, 0, ',', '.') }}
                        </h4>
                        <h5 class="mb-3 text-success">CLP
                            {{ number_format($totalMontoot, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-4 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Ventas del día</span>
                        <h5 class="mb-3 text-success">
                            Servicios: CLP {{ number_format($totalservices['servicesTotal'], 0, ',', '.') }}
                        </h5>
                        <h5 class="mb-3 text-success">
                            Productos: CLP {{ number_format($totalservices['otherProductsTotal'], 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->



</div>

<div class="row">
    <div class="col-xl-4 col-md-6">
        <!-- card -->
        <div class="card card-h-100">
            <div class="card-header text-center">
                <h4 class="card-title mb-0">Ordenes de Trabajo</h4>
            </div>
            <!-- card body -->
            <div class="card-body">
                <canvas id="ot-chart" height="200" style="max-height: 250 px"></canvas>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-8 col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="card-title mb-0">Ventas del Año {{ date('Y') }}</h4>
            </div>
            <div class="card-body">
                <canvas id="bar" height="200" style="max-height: 250 px"></canvas>
            </div>
        </div>
    </div> <!-- end col -->
</div>

<div class="row">
    <div class="col-xl-3">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Ventas del Día</h4>
            </div><!-- end card header -->

            <div class="card-body px-0 pt-2">
                <div class="table-responsive px-3" data-simplebar style="max-height: 395px;">
                    <table class="table align-middle table-nowrap">
                        <tbody>
                            <tr>
                                <td><strong>Efectivo</strong></td>
                                <td>CLP {{ number_format($totalxdia['totalefectivo'], 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tarjeta</strong></td>
                                <td>CLP {{ number_format($totalxdia['totalcredito'], 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cheque</strong></td>
                                <td>CLP {{ number_format($totalxdia['totalcheque'], 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Transferencia</strong></td>
                                <td>CLP {{ number_format($totalxdia['totaltransferencia'], 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- end card -->
    </div>



    <div class="col-xl-4">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Servicios del Día</h4>
            </div><!-- end card header -->

            <div class="card-body px-0 pt-2">
                <div class="table-responsive px-3" data-simplebar style="max-height: 395px;">
                    <table class="table align-middle table-nowrap">
                        <tbody>
                            @foreach ($topservices as  $topservice)
                            <tr>
                                <td><strong>{{ $topservice->name }}</strong></td>
                                <td>CLP {{ number_format($topservice->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- end card -->
    </div>

    <div class="col-xl-5">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Compras del mes</h4>
            </div><!-- end card header -->

            <div class="card-body px-0 pt-2">
                <div class="table-responsive px-3" data-simplebar style="max-height: 395px;">
                    <table class="table align-middle table-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Proveedor</th>
                                <th scope="col">Fecha de Compra</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($compras as  $compra)
                            <tr>
                                <td><strong>{{ $compra->name }}</strong></td>
                                <td>{{ Carbon\Carbon::parse($compra->created_at)->format('d/m/Y') }}</td>
                                <td>CLP {{ number_format($compra->total, 0, ',', '.') }}</td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- end card -->
    </div>
</div>
@endif

@if (Auth::user()->rol_id == 4)
<div class="row">

    <div class="col-xl-12 col-md-12 col-sm-12">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body text-center">
                <img src="{{ asset('') }}/{{ $empresa->logo }}" alt="rey del neumatico">
            </div>
        </div>
        <!-- end card -->
    </div>
</div>
@endif

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <script src="{{ asset('assets/js/pages/allchart.js') }}"></script> -->

<script>
    $(document).ready(function () {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        @if (count($productsQty) > 0)
            Toast.fire({
                icon: "error",
                title: "Tienes Productos sin Stock",
                text: "Por favor revisa las notificaciones y reponga el inventario"
            });
        @endif

    });

    const ctx = document.getElementById('bar');
    const otc = document.getElementById('ot-chart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Ingreso de ventas generadas CLP ', // Agregar el año al título
                data: [{{ $totalventasanuales }}],
                borderWidth: 1,
                backgroundColor: 'rgba(28, 132, 238, 1)',
                borderColor: 'rgba(28, 132, 238, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Formatear los números con separador de miles
                        callback: function(value, index, values) {
                            return value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Ventas: CLP ' + context.formattedValue;
                        }
                    }
                }
            }
        }
    });

    new Chart(otc, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'En Proceso', 'Completadas', 'Canceladas'],
            datasets: [{
                data: [{{ $statuWorkorders['Pendiente'] }}, {{ $statuWorkorders['EnProceso'] }}, {{ $statuWorkorders['Completado'] }}, {{ $statuWorkorders['Cancelado'] }}],
                backgroundColor: [
                    'rgba(133, 141, 152, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(52, 195, 143, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                borderColor: [
                    'rgba(133, 141, 152, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(52, 195, 143, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                }

            }
        }
    });

</script>
@endSection
