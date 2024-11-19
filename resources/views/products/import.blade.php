@extends('layouts.app')

@section('title') Productos @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Productos </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="{{ route('productos.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Importar Productos</li>
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
                        <h4 class="card-title">Importar de Productos</h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('productos.index') }}"
                            class="btn btn-secondary btn-sm float-end">
                            <i class="mdi mdi-plus"></i>
                            Regresar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('productos.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <p><a href="{{ asset('samples-imports/samples-productos.xlsx') }}"
                                    class="btn btn-info btn-sm pull-right">
                                    <i class="fa fa-download"></i>
                                    Descargar archivo de muestra</a></p>
                            <p class="text-primary">La primera linea en el archivo xlsx descargado debe permanecer como esta. Por favor no
                                cambie el orden de las columnas.</p>
                            <p class="text-danger">El orden correcto de la columna es <strong>(Código de producto, nombre del producto, precio de compra, precio de venta, precio de mayoreo, cantidad, inventario minimo, codigo de categoria, tipo de producto)</strong> y debes
                                seguir esto. </p>
                            <p class="text-danger">No se permiten campos vacíos</p>
                            <p class="text-info">Recordar que los tipos de productos son: Standard, Combo y Servicios</p>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="example-fileinput">Subir Archivo</label>
                                <input type="file" name="file" id="example-fileinput" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-md float-end">Importar Productos</button>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endSection

@section('scripts')
@endSection