@extends('layouts.app')

@section('title') Clientes @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Clientes </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a>
                    </li>
                    <li class="breadcrumb-item active">Nuevo Cliente</li>
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
                <h4 class="card-title">Nuevo Cliente</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('clientes.store') }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation @if ($errors->any()) was-validated @endif"
                    novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de Cliente</label>
                                <input class="form-control" type="text" name="name" id="name" required
                                    value="{{ old('name') }}">
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="rut" class="form-label">Rut de Cliente</label>
                                <input class="form-control" type="text" name="rut" id="rut" required
                                    value="{{ old('rut') }}" placeholder="00000000-0">
                                @if($errors->has('rut'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('rut') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo</label>
                                <input class="form-control" type="email" name="email" id="email" required
                                    value="{{ old('email') }}" placeholder="test@example.com">
                                @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Celular</label>
                                <input class="form-control" type="text" name="phone" id="phone" required
                                    value="{{ old('phone') }}">
                                @if($errors->has('phone'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('phone') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Dirección</label>
                                <input class="form-control" type="text" name="address" id="address" required
                                    value="{{ old('address') }}">
                                @if($errors->has('address'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('address') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-md float-end">Guardar Clientes</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')
<script>
    $(document).ready(function () {

        var Fn = {
            // Valida el rut con su cadena completa "XXXXXXXX-X"
            validaRut: function (rutCompleto) {
                rutCompleto = rutCompleto.replace("‐", "-");
                if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rutCompleto))
                    return false;
                var tmp = rutCompleto.split('-');
                var digv = tmp[1];
                var rut = tmp[0];
                if (digv == 'K') digv = 'k';

                return (Fn.dv(rut) == digv);
            },
            dv: function (T) {
                var M = 0,
                    S = 1;
                for (; T; T = Math.floor(T / 10))
                    S = (S + T % 10 * (9 - M++ % 6)) % 11;
                return S ? S - 1 : 'k';
            }
        }

        $('#rut').on('change', function () {
            if (Fn.validaRut($("#rut").val())) {
                alert("El rut ingresado es válido :D");
            } else {
                alert("El Rut no es válido :'( ");
                $('#rut').val('');
            }
        });

    });

</script>
@endSection
