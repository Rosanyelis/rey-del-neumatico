@extends('layouts.app')

@section('title') Gastos @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Gastos </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('gastos.index') }}">Gastos</a>
                    </li>
                    <li class="breadcrumb-item active">Editar Gasto</li>
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
                <h4 class="card-title">Editar Gasto</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('gastos.update', $expense->id) }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation @if ($errors->any()) was-validated @endif"
                    novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Referencia</label>
                                <input class="form-control" type="text" name="name" id="name" required
                                    value="{{ $expense->name }}">
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Monto</label>
                                <input class="form-control" type="number" name="amount" id="amount" required
                                    value="{{ $expense->amount }}" placeholder="ejem: 0000.00">
                                @if($errors->has('amount'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('amount') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">Archivo</label>
                                <input class="form-control" type="file" name="file" id="file">
                                @if($errors->has('file'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('file') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="note" class="form-label">Notas</label>
                                <textarea class="form-control" name="note" id="note">{{ $expense->note }}</textarea>
                                @if($errors->has('note'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('note') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary w-md float-end">Actualizar Gastos</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')
<script>

</script>
@endSection
