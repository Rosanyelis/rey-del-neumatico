@extends('layouts.app')

@section('title') Proveedores @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Proveedores </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('proveedor.index') }}">Proveedores</a></li>
                    <li class="breadcrumb-item active">Nuevo Proveedor</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Agregar Proveedor</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('proveedor.store') }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation"
                    novalidate >
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de Proveedor</label>
                                <input class="form-control @if ($errors->has('name')) is-invalid @endif  " type="text" name="name" id="name" required value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo</label>
                                <input class="form-control @if ($errors->has('email')) is-invalid @endif" type="email" name="email" id="email"  value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Celular</label>
                                <input class="form-control @if ($errors->has('phone')) is-invalid @endif" type="text" name="phone" id="phone" value="{{ old('phone') }}">
                                @if ($errors->has('phone'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('phone') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-md float-end">Guardar Proveedor</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')

@endSection
