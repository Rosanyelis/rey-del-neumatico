// Variables globales
const dataProduct = [];
const dataPartial = [];
const totalProduct = [];
const basepath = 'http://rey-del-neumatico.test';

// Función para cargar productos (con caché, en localstorage y optimización)
function loadProducts(categoryId = null) {
    const url = categoryId ? "/pos/get-product-pos" : "/pos/get-product-pos";

    $.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        data: categoryId ? { category_id: categoryId } : {},
        success: function(data) {
            totalProduct.length = 0;  // Limpiar array global
            totalProduct.push(...data); // Rellenar array con los productos cargados
            localStorage.setItem('products', JSON.stringify(data)); // Guardar productos en localstorage

            updateProductList(data);

        }
    });
}

// Función para actualizar la lista de productos
function updateProductList(products) {
    $('#productlist').empty();
    products.forEach(element => {
        const code = element.code.trim();
        const image = element.image ? basepath + element.image : basepath + '/assets/images/no-image.png';
        const productHtml = `
            <div class="col-3">
                <a href="javascript:void(0);"  onclick="addProductToTableFromImage('${code}')" >
                    <div class="card" style="width: 100%; height: 104px;">
                        <div class="card-body p-2">
                            <div class="product-img position-relative p-0">
                                <img src="${image}" class="producto mx-auto d-block rounded">
                            </div>
                        </div>
                        <div class="card-footer py-1 text-center bg-dark-subtle text-uppercase" style="font-size: 10px;">
                            <b>${element.name}</b>
                        </div>
                    </div>
                </a>
            </div>
        `;
        $('#productlist').append(productHtml);
    });

}

// Función para cargar clientes desde caché o servidor
function loadCustomers() {
    const cachedCustomers = localStorage.getItem('customers');
    if (cachedCustomers) {
        const customers = JSON.parse(cachedCustomers);
        return Promise.resolve(customers);
    } else {
        return $.ajax({
            url: '/pos/get-customers',
            type: 'GET',
            dataType: 'json'
        }).then(function(data) {
            localStorage.setItem('customers', JSON.stringify(data));
            return data;
        });
    }
}

// Función para inicializar los componentes select2 y autocompletado
function initializeSelect2AndAutocomplete() {
    $('#categorys').select2({
        placeholder: 'Seleccione una categoria',
    });

    // Cargar clientes con select2
    $('#customer').select2({
        placeholder: 'Seleccione un cliente',
        ajax: {
            url: '/pos/get-customers',
            type: 'GET',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data.map(item => ({ text: item.text, id: item.id }))
                };
            },
            cache: true
        }
    });

    // Autocompletar productos
    $('#productos').autocomplete({
        minLength: 1,
        source: function(request, response) {
            $.ajax({
                url: '/pos/get-products',
                dataType: 'json',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        select: function(event, ui) {
            addProductToTable(ui.item);
            $('#productos').val('').trigger('change');
            return false;
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item) {
        return $("<li>").append("<div>" + item.code + " - " + item.name + " (" + item.quantity + ") - " + item.price + "</div>").appendTo(ul);
    };

    // Autocompletar órdenes de trabajo
    $('#workorder').autocomplete({

        minLength: 1,
        source: function(request, response) {
            $.ajax({
                url: '/pos/get-workorders',
                dataType: 'json',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        select: function(event, ui) {
            addWorkOrderToTable(ui.item);
            $('#workorder').val('');
            return false;
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item) {
        return $("<li>").append("<div> Orden:" + item.correlativo + " - " + item.rut + " - " + item.name + "</div>").appendTo(ul);
    };
}

// Funcion para agregar productos a la tabla de ventas desde las imagenes
function addProductToTableFromImage(code) {
    // tomar los productos del localStorage
    const products = JSON.parse(localStorage.getItem('products'));
    const product = products.find(item => item.code.trim() === code);
    if (product) {
        addProductToTable(product);
    }
}
// Función para agregar productos a la tabla de ventas
function addProductToTable(product) {
    const { code, name, quantity, price, id } = product;
    const existingProduct = dataProduct.find(item => item.code === code);
    console.log(existingProduct);

    if (existingProduct) {
        updateProductInTable(existingProduct, quantity, price);
    } else {
        const newProduct = {
            id, code, name, quantity: 1, stock: quantity, price, subtotal: price, type: 'product'
        };
        dataProduct.push(newProduct);

        const rowHtml = `
            <tr id="producto-${code}">
                <td>${name} (${code})</td>
                <td class="text-center">${quantity}</td>
                <td class="text-center">${price}</td>
                <td class="text-center">
                    <input type="number" id="quantity-${code}"
                    class="form-control form-control-sm"
                    value="1" min="1" onchange="updateQuantity(this)" data-id="${code}">
                </td>
                <td class="text-center"><span id="subtotal-${code}" data-id="${code}">${price}</span></td>
                <td><button type="button" class="btn btn-danger btn-sm" data-id="${code}" onclick="deleteRow(this)"><i class="mdi mdi-delete"></i></button></td>
            </tr>
        `;
        $('#posTable tbody').append(rowHtml);
    }
    calculateTotal();
}

function updateProductInTable(existingProduct, quantity, price) {
    // Validar que la cantidad no exceda el stock disponible
    if (quantity > existingProduct.stock) {
        Swal.fire({
            icon: 'error',
            title: 'Oops!, no hay stock',
            text: 'El producto no tiene stock disponible, favor de seleccionar otro!',
        });
        return;
    }

    // Actualizar los datos del producto en la estructura de datos
    existingProduct.quantity = parseInt(existingProduct.quantity) + 1;
    existingProduct.subtotal = parseFloat(price) * existingProduct.quantity;

    // Actualizar los valores en la tabla HTML
    const productCode = existingProduct.code;
    $(`#quantity-${productCode}`).val(existingProduct.quantity); // Actualizar input de cantidad
    $(`#subtotal-${productCode}`).text(existingProduct.subtotal.toFixed(2)); // Actualizar subtotal

    // Recalcular los totales generales
    calculateTotal();

}


// Función para agregar órdenes de trabajo a la tabla
function addWorkOrderToTable(order) {
    const { correlativo, total, rut, name, id } = order;
    const existingOrder = dataProduct.find(item => item.code === correlativo);

    if (existingOrder) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'La Orden ya ha sido agregada!, no puede agregarla de nuevo!',
        });
    } else {
        const newOrder = {
            id, code: correlativo, name: `Orden: ${correlativo} - ${rut} - ${name}`, quantity: 1, price: total, subtotal: total, type: 'workorder'
        };
        dataProduct.push(newOrder);

        const rowHtml = `
            <tr id="producto-${correlativo}">
                <td>Orden: ${correlativo}</td>
                <td class="text-center">0</td>
                <td class="text-center">${total}</td>
                <td class="text-center">1</td>
                <td class="text-center">${total}</td>
                <td><button type="button" class="btn btn-danger btn-sm" data-id="${correlativo}" onclick="deleteRow(this)"><i class="mdi mdi-delete"></i></button></td>
            </tr>
        `;
        $('#posTable').append(rowHtml);
    }
    calculateTotal();
}

// Actualizar cantidad
function updateQuantity(code) {
    const product = dataProduct.find(p => p.code === code);
    const newQuantity = parseInt($(`#quantity-${code}`).val());

    if (newQuantity > product.stock) {
        Swal.fire("No hay suficiente stock", "", "error");
        $(`#quantity-${code}`).val(1);
        return;
    }

    product.quantity = newQuantity;
    product.subtotal = product.price * newQuantity;
    updateTableRow(code, product);
    calculateTotal();
}

// Actualizar fila de la tabla
function updateTableRow(code, product) {
    $(`#quantity-${code}`).val(product.quantity);
    $(`#subtotal-${code}`).text(product.subtotal.toFixed(2));
}

// Calcular totales de productos, descuento y propina
function calculateTotal() {
    const total = dataProduct.reduce((acc, product) => acc + parseFloat(product.subtotal), 0);
    const items = dataProduct.reduce((acc, product) => acc + parseInt(product.quantity), 0);
    const discount = parseFloat($("#discount").val()) || 0;
    const propina = parseFloat($("#propina").val()) || 0;
    const totalPay = total - discount + propina;

    $("#totalComplete").text(total.toFixed(2));
    $("#totalItems").text(items);
    $("#totalpay").text(totalPay.toFixed(2));
}

// Inicialización
$(document).ready(function() {
    initializeSelect2AndAutocomplete();

    // Cargar productos por categoría seleccionada
    $('#categorys').on('select2:select', function() {
        const categoryId = $(this).val();
        loadProducts(categoryId);
    });

    // Cargar productos iniciales (sin filtro de categoría)
    loadProducts();
});
