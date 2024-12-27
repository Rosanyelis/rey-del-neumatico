<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\WorkOrderController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    # POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/guardar-venta', [PosController::class, 'store'])->name('pos.store');

    Route::get('/pos/get-customers', [PosController::class, 'getCustomers'])->name('pos.getCustomers');
    Route::post('/pos/get-products', [PosController::class, 'getProducts'])->name('pos.getProducts');
    Route::post('/pos/get-workorders', [PosController::class, 'getWorkorders'])->name('pos.getWorkorders');
    Route::post('/pos/store-customer', [PosController::class, 'storeCustomer'])->name('pos.storeCustomer');
    Route::get('/pos/get-product-pos', [PosController::class, 'getProductPos'])->name('pos.getProductPos');
    Route::get('/pos/get-invoice', [PosController::class, 'show'])->name('pos.show');

    Route::get('/kardex', [KardexController::class, 'index'])->name('kardex.index');
    Route::post('/kardex/generar-informe', [KardexController::class, 'getInforme'])->name('kardex.getInforme');
    # Roles
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RolController::class, 'update'])->name('roles.update');
    Route::get('/roles/{role}', [RolController::class, 'destroy'])->name('roles.destroy');

    # Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/usuarios/{user}/update', [UserController::class, 'update'])->name('users.update');
    Route::get('/usuarios/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');

    # Tiendas
    Route::get('/tiendas', [StoreController::class, 'index'])->name('tiendas.index');
    Route::get('/tiendas/create', [StoreController::class, 'create'])->name('tiendas.create');
    Route::post('/tiendas/store', [StoreController::class, 'store'])->name('tiendas.store');
    Route::get('/tiendas/{tienda}/edit', [StoreController::class, 'edit'])->name('tiendas.edit');
    Route::put('/tiendas/{tienda}/update', [StoreController::class, 'update'])->name('tiendas.update');
    Route::get('/tiendas/{tienda}/destroy', [StoreController::class, 'destroy'])->name('tiendas.destroy');

    # Productos
    Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
    Route::get('/productos/datatable', [ProductController::class, 'datatable'])->name('productos.datatable');
    Route::post('/productos/totales', [ProductController::class, 'totalProductos'])->name('productos.totalProductos');
    Route::get('/productos/create', [ProductController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductController::class, 'store'])->name('productos.store');
    Route::get('/productos/{product}/show', [ProductController::class, 'show'])->name('productos.show');
    Route::get('/productos/{product}/edit', [ProductController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{product}/update', [ProductController::class, 'update'])->name('productos.update');
    Route::get('/productos/{product}/delete', [ProductController::class, 'destroy'])->name('productos.destroy');
    Route::get('/productos/importar-productos', [ProductController::class, 'view_import'])->name('productos.viewimport');
    Route::post('/productos/import-data', [ProductController::class, 'import'])->name('productos.import');
    Route::get('/productos/todos-los-productos', [ProductController::class, 'allproductpdf'])->name('products.allproductpdf');
    Route::post('/productos/generar-informe-filtrado', [ProductController::class, 'generateInformefilter'])->name('products.generateInformefilter');
    Route::get('/productos/{product}/kardex', [ProductController::class, 'kardex'])->name('products.kardex');
    Route::get('/productos/{product}/kardex-pdf', [ProductController::class, 'kardexpdf'])->name('products.kardexpdf');
    Route::post('/productos/{product}/kardex-pdf-filter', [ProductController::class, 'kardexpdffilter'])->name('products.kardexpdffilter');
    Route::get('/productos/productos-internacionales-excel', [ProductController::class, 'export'])->name('products.export');
    Route::get('/productos/productos-excel', [ProductController::class, 'exportproduct'])->name('products.exportproduct');


    # Categorias
    Route::get('/categorias', [CategoryController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoryController::class, 'create'])->name('categorias.create');
    Route::post('/categorias/guardar', [CategoryController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{category}/editar', [CategoryController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{category}/actualizar', [CategoryController::class, 'update'])->name('categorias.update');
    Route::get('/categorias/{category}/eliminar', [CategoryController::class, 'destroy'])->name('categorias.destroy');
    Route::get('/categorias/importar-categorias', [CategoryController::class, 'view_import'])->name('categorias.viewimport');
    Route::post('/categorias/import-data', [CategoryController::class, 'import'])->name('categorias.import');
    Route::get('/categorias/{category}/productos-por-categoria', [CategoryController::class, 'productcategory'])->name('categories.productcategory');

    # Ventas
    Route::get('/ventas-por-caja', [SaleController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/datatable', [SaleController::class, 'datatable'])->name('ventas.datatable');
    Route::post('/ventas/total-ventas-por-caja', [SaleController::class, 'totalSales'])->name('ventas.totalSales');
    Route::post('/ventas/generar-informe', [SaleController::class, 'generateInforme'])->name('ventas.generateInforme');

    Route::get('/ventas-por-mes', [SaleController::class, 'indexmonth'])->name('ventas.indexmonth');
    Route::get('/ventas/datatable-por-mes', [SaleController::class, 'datatablexmonth'])->name('ventas.datatablexmonth');
    Route::post('/ventas/total-ventas-por-mes', [SaleController::class, 'totalSalesxmonth'])->name('ventas.totalSalesxmonth');
    Route::post('/ventas/generar-informe-por-mes', [SaleController::class, 'generateInformexmes'])->name('ventas.generateInformexmes');

    Route::get('/ventas-por-rango', [SaleController::class, 'indexrange'])->name('ventas.indexrange');
    Route::get('/ventas/datatable-por-rango', [SaleController::class, 'datatablexrange'])->name('ventas.datatablexrange');
    Route::post('/ventas/total-ventas-por-rango', [SaleController::class, 'totalSalesxrange'])->name('ventas.totalSalesxrange');
    Route::post('/ventas/generar-informe-por-rango', [SaleController::class, 'generateInformexrange'])->name('ventas.generateInformexrange');

    Route::get('/ventas/{sale}/show', [SaleController::class, 'show'])->name('ventas.show');
    Route::get('/ventas/{sale}/generar-factura', [SaleController::class, 'generateInvoice'])->name('ventas.generateInvoice');
    Route::get('/ventas/{sale}/generar-factura-ticket', [SaleController::class, 'generateTicket'])->name('ventas.generateTicket');
    Route::get('/ventas/{sale}/generar-ticket', [SaleController::class, 'printPos'])->name('ventas.printPos');

    # Cotizaciones
    Route::get('/cotizaciones', [QuotationController::class, 'index'])->name('cotizaciones.index');
    Route::get('/cotizaciones/datatable', [QuotationController::class, 'datatable'])->name('cotizaciones.datatable');
    Route::post('/cotizaciones/total', [QuotationController::class, 'totalQuotes'])->name('cotizaciones.totalQuotes');
    Route::get('/cotizaciones/create', [QuotationController::class, 'create'])->name('cotizaciones.create');
    Route::post('/cotizaciones', [QuotationController::class, 'store'])->name('cotizaciones.store');
    Route::get('/cotizaciones/{quotation}/show', [QuotationController::class, 'show'])->name('cotizaciones.show');
    Route::get('/cotizaciones/{quotation}/edit', [QuotationController::class, 'edit'])->name('cotizaciones.edit');
    Route::put('/cotizaciones/{quotation}/update', [QuotationController::class, 'update'])->name('cotizaciones.update');
    Route::get('/cotizaciones/{quotation}/delete', [QuotationController::class, 'destroy'])->name('cotizaciones.destroy');
    Route::get('/cotizaciones/{quotation}/productjson', [QuotationController::class, 'productjson'])->name('cotizaciones.productjson');
    Route::get('/cotizaciones/{quotation}/quotepdf', [QuotationController::class, 'quotepdf'])->name('cotizaciones.quotepdf');
    Route::get('/cotizaciones/{quotation}/enviar-cotizacion', [QuotationController::class, 'sendEmailQuotepdf'])->name('cotizaciones.sendEmailQuotepdf');

    # proveedores
    Route::get('/proveedores', [SupplierController::class, 'index'])->name('proveedor.index');
    Route::get('/proveedores/create', [SupplierController::class, 'create'])->name('proveedor.create');
    Route::post('/proveedores', [SupplierController::class, 'store'])->name('proveedor.store');
    Route::get('/proveedores/{proveedor}/edit', [SupplierController::class, 'edit'])->name('proveedor.edit');
    Route::put('/proveedores/{proveedor}', [SupplierController::class, 'update'])->name('proveedor.update');
    Route::get('/proveedores/{proveedor}', [SupplierController::class, 'destroy'])->name('proveedor.destroy');

    # Clientes
    Route::get('/clientes', [CustomerController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [CustomerController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [CustomerController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}/show', [CustomerController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/edit', [CustomerController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}/update', [CustomerController::class, 'update'])->name('clientes.update');
    Route::get('/clientes/{cliente}/delete', [CustomerController::class, 'destroy'])->name('clientes.destroy');

    # Gastos
    Route::get('/gastos', [ExpenseController::class, 'index'])->name('gastos.index');
    Route::get('/gastos/create', [ExpenseController::class, 'create'])->name('gastos.create');
    Route::post('/gastos', [ExpenseController::class, 'store'])->name('gastos.store');
    Route::get('/gastos/{gasto}/show', [ExpenseController::class, 'show'])->name('gastos.show');
    Route::get('/gastos/{gasto}/edit', [ExpenseController::class, 'edit'])->name('gastos.edit');
    Route::put('/gastos/{gasto}/update', [ExpenseController::class, 'update'])->name('gastos.update');
    Route::get('/gastos/{gasto}/delete', [ExpenseController::class, 'destroy'])->name('gastos.destroy');

    # compras
    Route::get('/compras', [PurchaseController::class, 'index'])->name('compras.index');
    Route::get('/compras/datatable', [PurchaseController::class, 'datatable'])->name('compras.datatable');
    Route::post('/compras/total-compras', [PurchaseController::class, 'totalPurchases'])->name('compras.totalPurchases');

    Route::get('/compras/create', [PurchaseController::class, 'create'])->name('compras.create');
    Route::post('/compras/store', [PurchaseController::class, 'store'])->name('compras.store');
    Route::get('/compras/{compra}/show', [PurchaseController::class, 'show'])->name('compras.show');
    Route::get('/compras/{compra}/edit', [PurchaseController::class, 'edit'])->name('compras.edit');
    Route::put('/compras/{compra}/update', [PurchaseController::class, 'update'])->name('compras.update');
    Route::get('/compras/{compra}/delete', [PurchaseController::class, 'destroy'])->name('compras.destroy');

    Route::get('/compras/{compra}/purchasepdf', [PurchaseController::class, 'purchasepdf'])->name('compras.purchasepdf');
    Route::get('/compras/{compra}/purchasepos', [PurchaseController::class, 'purchasefactura'])->name('compras.purchasefactura');
    Route::get('/compras/generar-informe', [PurchaseController::class, 'generateInforme'])->name('compras.generateInforme');
    Route::post('/compras/generar-informe-filtrado', [PurchaseController::class, 'generateInformefilter'])->name('compras.generateInformefilter');
    Route::post('/compras/cambio-status', [PurchaseController::class, 'changeStatus'])->name('compras.changeStatus');

    # Ordenes de Trabajo
    Route::get('/ordenes-trabajo', [WorkOrderController::class, 'index'])->name('ordenes-trabajo.index');
    Route::get('/ordenes-trabajo/totales', [WorkOrderController::class, 'totalWorkOrder'])->name('ordenes-trabajo.totalWorkOrder');
    Route::get('/ordenes-trabajo/create', [WorkOrderController::class, 'create'])->name('ordenes-trabajo.create');
    Route::post('/ordenes-trabajo', [WorkOrderController::class, 'store'])->name('ordenes-trabajo.store');
    Route::get('/ordenes-trabajo/{workOrder}/show', [WorkOrderController::class, 'show'])->name('ordenes-trabajo.show');
    Route::get('/ordenes-trabajo/{workOrder}/edit', [WorkOrderController::class, 'edit'])->name('ordenes-trabajo.edit');
    Route::put('/ordenes-trabajo/{workOrder}/update', [WorkOrderController::class, 'update'])->name('ordenes-trabajo.update');
    Route::post('/ordenes-trabajo/destroy', [WorkOrderController::class, 'destroy'])->name('ordenes-trabajo.destroy');
    Route::get('/ordenes-trabajo/{workOrder}/workOrder', [WorkOrderController::class, 'workorderpdf'])->name('ordenes-trabajo.workorderpdf');
    Route::get('/ordenes-trabajo/{workOrder}/workOrderpos', [WorkOrderController::class, 'workorderpos'])->name('ordenes-trabajo.workorderpos');
    Route::get('/ordenes-trabajo/{workOrder}/enviar-orden-de-trabajo', [WorkOrderController::class, 'sendEmailWorkorderpdf'])->name('ordenes-trabajo.sendEmailWorkorderpdf');
    Route::post('/ordenes-trabajo/productjson', [WorkOrderController::class, 'productjson'])->name('ordenes-trabajo.productjson');

    # Ordenes de Trabajo - monitor
    Route::get('/monitor', [MonitorController::class, 'index'])->name('monitor.index');

    # Reportes
    Route::get('/informe-de-ventas', [ReportsController::class, 'informeventas'])->name('reportes.informeventas');
    Route::get('/informe-de-ventas-por-dia-con-propina', [ReportsController::class, 'informeVentasxdia'])->name('reportes.informeVentasxdia');
    Route::get('/datatable-ventas-por-dia-con-propina', [ReportsController::class, 'datatableVentasxDia'])->name('reportes.datatableVentasxDia');
    Route::post('/informe-de-ventas-por-dia', [ReportsController::class, 'informeVentasxdiaPdf'])->name('reportes.informeVentasxdiaPdf');
    Route::get('/informe-de-gastos', [ReportsController::class, 'informegastos'])->name('reportes.informegastos');
    Route::get('/informe-de-ventas-de-productos-por-dia', [ReportsController::class, 'informeVentasxdiaxproducto'])->name('reportes.informeVentasxdiaxproducto');
    Route::get('/datatable-de-ventas-de-productos-por-dia', [ReportsController::class, 'datatableVentasxDiaxProducto'])->name('reportes.datatableVentasxDiaxProducto');
    Route::post('/informe-de-ventas-de-productos-por-dia-pdf', [ReportsController::class, 'pdfVentasxDiaxProducto'])->name('reportes.pdfVentasxDiaxProducto');
    Route::get('/informe-de-productos-vendidos-incluidos-eliminados', [ReportsController::class, 'informeProductosVendidos'])->name('reportes.informeProductosVendidos');
    Route::get('/datatable-de-productos-vendidos-incluidos-eliminados', [ReportsController::class, 'datatableProductosVendidos'])->name('reportes.datatableProductosVendidos');
    Route::post('/informe-de-productos-vendidos-incluidos-eliminados-pdf', [ReportsController::class, 'pdfProductosVendidos'])->name('reportes.pdfProductosVendidos');
    Route::get('/informe-de-neumaticos-internacionales', [ReportsController::class, 'informeNeumaticosInternacionales'])->name('reportes.informeNeumaticosInternacionales');
    Route::get('/datatable-de-neumaticos-internacionales', [ReportsController::class, 'datatableNeumaticosInternacionales'])->name('reportes.datatableNeumaticosInternacionales');
    Route::post('/total-de-neumaticos-internacionales', [ReportsController::class, 'totalneumaticos'])->name('reportes.totalneumaticos');
    Route::post('/informe-de-neumaticos-internacionales-pdf', [ReportsController::class, 'pdfNeumaticosInternacionales'])->name('reportes.pdfNeumaticosInternacionales');
    Route::post('/informe-de-neumaticos-internacionales-excel', [ReportsController::class, 'NeumaticosInternacionalesExcel'])->name('reportes.NeumaticosInternacionalesExcel');



});
Route::get('comandos', function () {
    Artisan::call('optimize');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:cache');
    Artisan::call('route:cache');

    return 'Comandos ejecutados con éxitos';
});

require __DIR__.'/auth.php';
