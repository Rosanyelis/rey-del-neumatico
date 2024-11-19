@extends('layouts.app')

@section('title') Categorías @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Categorías </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="{{ route('categorias.index') }}">Categorías</a></li>
                    <li class="breadcrumb-item active">Importar Categorías</li>
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
                        <h4 class="card-title">Importar de Categorías</h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('categorias.index') }}"
                            class="btn btn-secondary btn-sm float-end">
                            <i class="mdi mdi-plus"></i>
                            Regresar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('categorias.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <p><a href="{{ asset('samples-imports/samples-categorias.xlsx') }}"
                                    class="btn btn-info btn-sm pull-right">
                                    <i class="fa fa-download"></i>
                                    Descargar archivo de muestra</a></p>
                            <p class="text-primary">La primera linea en el archivo xlsx descargado debe permanecer como esta. Por favor no
                                cambie el orden de las columnas.</p>
                            <p class="text-danger">El orden correcto de la columna es <strong>(Código de categorIa, nombre de la categoria)</strong> y debes
                                seguir esto. </p>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="example-fileinput">Subir Archivo</label>
                                <input type="file" name="file" id="example-fileinput" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-md float-end">Importar Categorías</button>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endSection

@section('scripts')
@endSection
