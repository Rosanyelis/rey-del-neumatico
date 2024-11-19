<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsExport implements FromView
{

    public function view(): View
    {
        return view('exports.productsexcel', [
            'products' =>Product::join('categories', 'products.category_id', '=', 'categories.id')
        ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
        ->select('products.*', 'product_store_qties.quantity as stock', 'categories.name as category_name')
        ->orderBy('categories.name', 'asc', 'products.code', 'asc')
        ->get()
        ]);
    }
}
