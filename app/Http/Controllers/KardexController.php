<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\KardexExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();
        set_time_limit(-1);
        $productos = Product::all();
        if ($request->ajax()) {
            $data = DB::table('kardexes')
                ->join('products', 'products.id', '=', 'kardexes.product_id')
                ->leftjoin('users', 'users.id', '=', 'kardexes.user_id')
                ->select('kardexes.*', 'products.name as product_name', 'users.name as user_name')
                ->groupBy('kardexes.id',
                        'kardexes.description',
                        'kardexes.type',
                        'kardexes.created_at',
                        'kardexes.product_id',
                        'kardexes.ingreso',
                        'kardexes.habian',
                        'kardexes.salieron',
                        'kardexes.quedan',
                        'kardexes.price',
                        'kardexes.total',
                        'products.name',
                        'users.name')
                ->orderBy('kardexes.id', 'desc');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('kardexes.created_at', [$request->get('start'), $request->get('end')]);
                    }

                    if ($request->has('product_id') && $request->get('product_id') != '') {
                        $query->where('kardexes.product_id', $request->get('product_id'));
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('products.name', 'like', "%{$searchValue}%")
                                     ->orWhere('kardexes.description', 'like', "%{$searchValue}%")
                                     ->orWhere('kardexes.type', 'like', "%{$searchValue}%");
                        });
                    }

                })
                ->make(true);
        }
        return view('kardex.index', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getInforme(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ], [
            'product_id.required' => 'El producto es requerido',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('success', '' . $validator->errors()->first());
        }

        $product = Product::find($request->get('product_id'));

        $start = $request->get('start');
        $end = $request->get('end');
        $producto = $request->get('product_id');

        $name = $this->limpiarNombre($product->name);

        return Excel::download(new KardexExport($start, $end, $producto),
                    'kardex o movimientos del producto '.$name.'.xlsx');

    }

    public function limpiarNombre($nombre) {
        return str_replace('/', ' ', $nombre);
    }

}
