<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsInternationalExport implements FromView
{
    public function view(): View
    {
        return view('exports.products', [
            'products' => Product::join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
            ->select('products.code as codigo', 'products.name as producto', 'products.cost as precio_costo',
            'products.price as precio_venta', 'products.type as tipo', 'products.nacionality as nacionalidad',
            'products.weight as peso', 'product_store_qties.quantity as stock_actual')
            ->where('products.type', 'NEUMATICOS')
            ->where('products.nacionality', 'Internacional')
            ->get()
        ]);
    }


}
