@extends('layouts.app')

@section('title') Tiendas @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Tiendas </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Configuraciones</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tiendas.index') }}">Tiendas</a></li>
                    <li class="breadcrumb-item active">Nueva Tienda</li>
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
                <h4 class="card-title">Agregar tienda</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('tiendas.store') }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation @if ($errors->any()) was-validated @endif"
                    novalidate >
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de la Tienda</label>
                                <input class="form-control" type="text" name="name" id="name" required value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-64 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Codigo de la Tienda</label>
                                <input class="form-control" type="text" name="code" id="code" required value="{{ old('code') }}">
                                @if ($errors->has('code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('code') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input class="form-control" type="file" name="logo" id="logo">
                                @if ($errors->has('logo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('logo') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrônico</label>
                                <input class="form-control" type="email" name="email" id="email" required value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input class="form-control" type="text" name="phone" id="phone" required value="{{ old('phone') }}">
                                @if ($errors->has('phone'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('phone') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Dirección 1</label>
                                <input class="form-control" type="text" name="address" id="address" required value="{{ old('address') }}">
                                @if ($errors->has('address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="address2" class="form-label">Dirección 2</label>
                                <input class="form-control" type="text" name="address2" id="address2" value="{{ old('address2') }}">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">Ciudad</label>
                                <input class="form-control" type="text" name="city" id="city" required value="{{ old('city') }}">
                                @if ($errors->has('city'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('city') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="state" class="form-label">Estado</label>
                                <input class="form-control" type="text" name="state" id="state" required value="{{ old('state') }}">
                                @if ($errors->has('state'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('state') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Código Postal</label>
                                <input class="form-control" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
                                @if ($errors->has('postal_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('postal_code') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="country" class="form-label">País</label>
                                <input class="form-control" type="text" name="country" id="country" required value="{{ old('country') }}">
                                @if ($errors->has('country'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('country') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="currency_code" class="form-label">Codigo de Moneda</label>
                                <input class="form-control" type="text" name="currency_code" id="currency_code" required value="{{ old('currency_code') }}">
                                @if ($errors->has('currency_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('currency_code') }}
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary w-md">Guardar Tienda</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')

@endSection
