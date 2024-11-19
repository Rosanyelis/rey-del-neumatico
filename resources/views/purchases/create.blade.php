@extends('layouts.app')

@section('title') Compras @endsection

@section('css')
<!-- choices css -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Compras </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a>
                    </li>
                    <li class="breadcrumb-item active">Nueva Compra</li>
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
                <h4 class="card-title">Nueva Compra</h4>
                <p class="card-title-desc">Verifique la información al registrar, ya que si recarga la página, esta se perderá.</p>
            </div>
            <div class="card-body p-4">
                <form id="formQuotation" action="{{ route('compras.store') }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation "
                    novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="supplier" class="form-label">Proveedores</label>
                                <select class="form-control @if ($errors->has('supplier')) is-invalid @endif "
                                    name="supplier" id="supplier" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($suppliers as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('supplier'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('supplier') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-6">
                            <div class="mb-3">
                                <label for="note" class="form-label">Notas</label>
                                <input type="text" class="form-control" name="note" id="note" >
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="reference" class="form-label">N° de Factura</label>
                                <input type="text" class="form-control" name="reference" id="reference" >
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="received" class="form-label">¿Recibido?</label>
                                <select class="form-control @if ($errors->has('received')) is-invalid @endif "
                                name="received" id="received" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    <option value="1">Recibido</option>
                                    <option value="0">No Recibido</option>
                                </select>

                                @if ($errors->has('received'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('received') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="type_purchase" class="form-label">Tipo de Compra</label>
                                <select class="form-control @if ($errors->has('type_purchase')) is-invalid @endif" name="type_purchase"
                                id="type_purchase" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Nacional" {{ old('type_purchase') == 'Nacional' ? 'selected' : '' }} >Nacional</option>
                                    <option value="Internacional" {{ old('type_purchase') == 'Internacional' ? 'selected' : '' }}>Internacional</option>
                                </select>

                                @if ($errors->has('type_purchase'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type_purchase') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="archivo" class="form-label">Archivo</label>
                                <input type="file" class="form-control" name="archivo" id="archivo" >
                            </div>
                        </div>

                        <div class="w-100"></div>
                        <hr>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="mb-3">
                                <label for="producto" class="form-label">Nombre de Producto</label>
                                <select class="form-control" name="producto" id="producto" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($products as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="product_name" id="product_name">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Precio Actual Prod.</label>
                                <input type="text" class="form-control" name="price" id="price"  readonly>
                            </div>
                        </div>

                        <div class="w-100"></div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Cantidad del Producto</label>
                                <input type="text" class="form-control" name="quantity" id="quantity" >
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="cost" class="form-label">Costo de Compra</label>
                                <input type="text" class="form-control" name="cost" id="cost" >
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="cost" class="form-label">Peso de Neumático(opc)</label>
                                <input type="text" class="form-control" name="weight" id="weight" >
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <button type="button" class="btn btn-info mt-4" id="add_product">Agregar Producto</button>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table" id="table_products">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Costo</th>
                                        <th>Total</th>
                                        <th>Peso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body_products">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><h4>Total</h4></td>
                                        <td colspan="3" ><h4 id="total">0</h4></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="total" id="totalform">
                    <input type="hidden" name="array_products" id="array_products">
                    <button type="button" id="guardar" class="btn btn-primary w-md float-end">Guardar Compra</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    $(document).ready(function() {
        $('#producto').select2();
        $('#customer').select2();
        var datosTabla = [];

        $('#producto').on('select2:select', function(e) {
            var id = $('#producto').val();
            console.log(id);
            $.ajax({
                type: 'GET',
                url: "{{ route('cotizaciones.productjson', ':id') }}".replace(':id', id),
                data: {
                    id: id
                },
                success: function(data) {
                    $('#price').val(data.price);
                    $('#product_name').val(data.name);
                }
            });
        });

        $('#add_product').on('click', function() {
            var producto = $('#product_name').val();
            let cost = parseFloat($('#cost').val());
            let quantity = parseFloat($('#quantity').val());
            let weight = $('#weight').val();
            let subtotal = cost * quantity;
            let total = subtotal;

            let datosFila = {};
                datosFila.producto = producto;
                datosFila.quantity = quantity;
                datosFila.cost = cost;
                datosFila.total = total;
                datosFila.weight = weight;
                datosTabla.push(datosFila);

            $("#table_products tbody").append(
                `<tr>
                    <td>`+producto+`</td>
                    <td>`+quantity+`</td>
                    <td>`+cost+`</td>
                    <td>`+total+`</td>
                    <td>`+weight+`</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm"
                            id="delete_product" data-name="`+producto+`">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>`);


            $("#total").empty();
            let totalfinal = 0;
            for (let i = 0; i < datosTabla.length; i++) {
                totalfinal += parseFloat(datosTabla[i].total);
            }
            $("#total").append(totalfinal);


            $("#price").val('');
            $("#quantity").val('');
            $("#cost").val('');
            $("#product_name").val("");
            $("#weight").val('');
            $("#producto").val(null).trigger("change");
        });

        $('#table_products').on('click', '#delete_product', function() {
            let product = $(this).data('name');
            $("#table_products tbody").find('tr').each(function() {
                if ($(this).find('td').eq(0).text() == product) {
                    $(this).remove();
                }
            });

            datosTabla = datosTabla.filter(function(item) {
                return item.producto !== product;
            });
            $("#total").empty();
            let totalfinal = 0;
            for (let i = 0; i < datosTabla.length; i++) {
                totalfinal += datosTabla[i].total;
            }
            $("#total").append(totalfinal);
        });

        $('#guardar').on('click', function() {
            if (datosTabla.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No hay productos agregados, por favor agrega uno',
                });
                return false;
            }
            $('#array_products').val(JSON.stringify(datosTabla));
            $('#totalform').val($('#total').text());
            $('guardar').prop('disabled', true);
            $('guardar').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Por favor, espere...');
            $('#formQuotation').submit();
        });
    })
</script>
@endSection
