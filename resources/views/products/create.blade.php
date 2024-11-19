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
                    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a>
                    </li>
                    <li class="breadcrumb-item active">Nuevo Producto</li>
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
                <h4 class="card-title">Nuevo Producto</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('productos.store') }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Código de Producto</label>
                                <input class="form-control @if ($errors->has('code')) is-invalid @endif"
                                    type="text" name="code" id="code" value="{{ old('code') }}" >
                                @if($errors->has('code'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('code') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de Producto</label>
                                <input class="form-control @if ($errors->has('name')) is-invalid @endif"
                                type="text" name="name" id="name" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Imagen</label>
                                <input class="form-control @if ($errors->has('image')) is-invalid @endif"
                                type="file" name="image" id="image">
                                @if($errors->has('image'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('image') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Categorías</label>
                                <select class="form-control @if ($errors->has('category_id')) is-invalid @endif " name="category_id">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($category as $item)
                                        <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }} >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('category_id'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('category_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Producto</label>
                                <select class="form-control @if ($errors->has('type')) is-invalid @endif"
                                    name="type" >
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($typeproduct as $item)
                                        <option value="{{ $item->name }}" {{ old('type') == $item->name ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('type') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="cost" class="form-label">Costo de Producto</label>
                                <input class="form-control @if ($errors->has('cost')) is-invalid @endif"
                                type="number" name="cost" id="cost"
                                    value="{{ old('cost') }}">
                                @if($errors->has('cost'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('cost') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Precio de Producto</label>
                                <input class="form-control @if ($errors->has('price')) is-invalid @endif"
                                type="number" name="price" id="price"
                                    value="{{ old('price') }}">
                                @if($errors->has('price'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('price') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Cantidad de Producto</label>
                                <input class="form-control @if ($errors->has('quantity')) is-invalid @endif"
                                type="number" name="quantity" id="quantity"
                                    value="{{ old('quantity') }}">
                                @if($errors->has('quantity'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('quantity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="alert_quantity" class="form-label">Cantidad Mínima de Producto</label>
                                <input class="form-control @if ($errors->has('alert_quantity')) is-invalid @endif" type="number"
                                name="alert_quantity" id="alert_quantity"
                                    value="{{ old('alert_quantity') }}">
                                @if($errors->has('alert_quantity'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('alert_quantity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="max_quantity" class="form-label">Cantidad Máxima de Producto</label>
                                <input class="form-control @if ($errors->has('max_quantity')) is-invalid @endif"
                                type="number" name="max_quantity" id="max_quantity"
                                    value="{{ old('max_quantity') }}">
                                @if($errors->has('max_quantity'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('max_quantity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Detalles</label>
                                <input class="form-control @if ($errors->has('description')) is-invalid @endif"
                                 type="text" name="description" id="description"
                                    value="{{ old('description') }}">
                                @if($errors->has('description'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('description') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Peso de Neumático (opcional)</label>
                                <input class="form-control @if ($errors->has('weight')) is-invalid @endif"
                                type="number" name="weight" id="weight"
                                    value="{{ old('weight') }}">
                                @if($errors->has('weight'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('weight') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="nacionality" class="form-label">Nacionalidad de Producto</label>
                                <select class="form-control @if ($errors->has('nacionality')) is-invalid @endif"
                                name="nacionality" id="nacionality" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Nacional" {{ old('nacionality') == 'Nacional' ? 'selected' : '' }}>Nacional</option>
                                    <option value="Internacional" {{ old('nacionality') == 'Internacional' ? 'selected' : '' }}>Internacional</option>
                                </select>
                                @if($errors->has('nacionality'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nacionality') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="w-100"></div>

                        <hr>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <h4>Ubicación en Bodega (Opcional)</h4>
                            </div>
                        </div>

                        <hr>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="cellar" class="form-label">Bodega</label>
                                <input type="text" class="form-control" name="cellar" id="cellar" value="{{ old('cellar') }}">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="hail" class="form-label">Pasillo</label>
                                <input type="text" class="form-control" name="hail" id="hail" value="{{ old('hail') }}">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="rack" class="form-label">Estante o rack</label>
                                <input type="text" class="form-control" name="rack" id="rack" value="{{ old('rack') }}">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Posicion</label>
                                <input type="text" class="form-control" name="position" id="position" value="{{ old('position') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-md float-end">Guardar Producto</button>
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
