<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\TypeProduct;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NeumaticosInternacionalesExport;

class ReportsController extends Controller
{
    /**
     * Informe de ventas
     */
    public function informeventas()
    {
        $informe = [];
        $data = DB::table('sales')
                ->join('users', 'sales.user_id', '=', 'users.id')
                ->select('sales.*', 'users.name as user_name')
                ->get();
        $total = 0;
        $totalefectivo = 0;
        $totalcredito = 0;
        $totalcheque = 0;
        $totaltransferencia = 0;
        $totalpropina = 0;
        foreach ($data as $sale) {
            $informe[] = [
                'id' => $sale->id,
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'cliente' => $sale->customer_name,
                'vendedor' => $sale->user_name,
                'total' => $sale->grand_total,
            ];
            $total += $sale->grand_total;

            $totalpropina += $sale->perquisite;

            $payments = DB::table('sales')
                ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                ->select(DB::raw('SUM(sale_payments.pos_paid) AS total'), 'sale_payments.payment_method')
                ->groupBy('sale_payments.payment_method')
                ->where('sales.id', $sale->id)
                ->get();

            foreach ($payments as $key) {
                if ($key->payment_method == 'Efectivo') {
                    $totalefectivo = $key->total;
                } else if ($key->payment_method == 'Tarjeta de credito') {
                    $totalcredito = $key->total;
                } else if ($key->payment_method == 'Cheque') {
                    $totalcheque = $key->total;
                } else if ($key->payment_method == 'Transferencia') {
                    $totaltransferencia = $key->total;
                }
            }

        }

        return Pdf::loadView('pdfs.informesales', compact('informe', 'total', 'totalefectivo', 'totalcredito', 'totalcheque', 'totaltransferencia', 'totalpropina'))
                ->stream(''.config('app.name', 'Laravel').' - Informe de ventas totales - ' . Carbon::now(). '.pdf');
    }

    /**
     * Informe de gastos
     */
    public function informegastos()
    {
        $informe = [];
        $data = DB::table('expenses')
        ->join('stores', 'expenses.store_id', '=', 'stores.id')
        ->join('users', 'expenses.user_id', '=', 'users.id')
        ->select('expenses.*', 'stores.name as store_name', 'users.name as user_name')
        ->get();
        $total = 0;
        foreach ($data as $sale) {
            $informe[] = [
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'motivo' => $sale->name,
                'monto' => $sale->amount,
                'creado_por' => $sale->user_name,
            ];
            $total += $sale->amount;
        }

        return Pdf::loadView('pdfs.informegastos', compact('informe', 'total'))
                ->stream(''.config('app.name', 'Laravel').' - Informe de gastos totales - ' . Carbon::now(). '.pdf');
    }

    public function informeproductos()
    {
        $products = Product::all();
        return Pdf::loadView('pdfs.porcategoria', compact('products'))
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos.pdf');
    }

    public function informeVentasxdia()
    {

        $users = User::where('rol_id', '!=' ,'1')->get();
        return view('reports.ventapropina', compact('users'));

    }

    public function datatableVentasxDia(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('sales')
                ->join('users', 'sales.user_id', '=', 'users.id')
                ->select('sales.*', 'users.name as user_name');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('vendedor') && $request->get('vendedor') != '') {
                        $query->where('sales.user_id', $request->get('vendedor'));
                    }
                    if ($request->has('dateday') && $request->get('dateday') != '') {
                        $query->whereDate('sales.created_at', '=', $request->get('dateday'));
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('users.name', 'like', "%{$searchValue}%")
                                     ->orWhere('sales.customer_name', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->make(true);
        }
    }

    public function informeVentasxdiaPdf(Request $request)
    {
        $informe = [];
        $user_id = ($request->get('user') == 'Todos' ? '' : $request->get('user'));
        $data = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->select('sales.*', 'users.name as user_name')
            ->whereDate('sales.created_at', '=', $request->get('day'))
            ->where(function ($subQuery) use ($user_id) {
                if ($user_id != '' && $user_id != 'Todos') {
                    $subQuery->where('sales.user_id', $user_id);
                }
            })
            ->get();

        $total = 0;
        $totalefectivo = 0;
        $totalcredito = 0;
        $totalcheque = 0;
        $totaltransferencia = 0;
        $totalpropina = 0;
        foreach ($data as $sale) {
            $informe[] = [
                'id' => $sale->id,
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'cliente' => $sale->customer_name,
                'propina' => $sale->perquisite,
                'vendedor' => $sale->user_name,
                'total' => $sale->grand_total,
            ];
            $total += $sale->grand_total;
            $totalpropina += $sale->perquisite;

            $payments = DB::table('sales')
                ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                ->select(DB::raw('SUM(sale_payments.pos_paid) AS total'), 'sale_payments.payment_method')
                ->groupBy('sale_payments.payment_method')
                ->where(function ($subQuery) use ($user_id) {
                    if ($user_id != '' && $user_id != 'Todos') {
                        $subQuery->where('sales.user_id', $user_id);
                    }
                })
                ->get();

            foreach ($payments as $key) {
                if ($key->payment_method == 'Efectivo') {
                    $totalefectivo = $key->total;
                } else if ($key->payment_method == 'Tarjeta de credito') {
                    $totalcredito = $key->total;
                } else if ($key->payment_method == 'Cheque') {
                    $totalcheque = $key->total;
                } else if ($key->payment_method == 'Transferencia') {
                    $totaltransferencia = $key->total;
                }
            }

        }

        return Pdf::loadView('pdfs.ventaspropinasxdia', compact('informe', 'total', 'totalefectivo', 'totalcredito', 'totalcheque', 'totaltransferencia', 'totalpropina'))
                ->stream(''.config('app.name', 'Laravel').' - Informe de ventas por dia con propina - ' . Carbon::now(). '.pdf');
    }

    public function informeVentasxdiaxproducto()
    {

        $types = TypeProduct::all();
        return view('reports.productosvendidos', compact('types'));

    }

    public function datatableVentasxDiaxProducto(Request $request)
    {

        $data = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('sales.*', 'users.name as user_name', 'products.name as product_name', 'products.type as type',
            'sale_items.unit_price as price', 'sale_items.subtotal as subtotal', 'sale_items.quantity as quantity',
            'sale_payments.payment_method as payment');

            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('type') && $request->get('type') != '') {
                        $query->where('products.type', $request->get('type'));
                    }
                    if ($request->has('dateday') && $request->get('dateday') != '') {
                        $query->whereDate('sales.created_at', '=', $request->get('dateday'));
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('users.name', 'like', "%{$searchValue}%")
                                     ->orWhere('sales.customer_name', 'like', "%{$searchValue}%")
                                     ->orWhere('sale_payments.payment_method', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->make(true);
    }

    public function pdfVentasxDiaxProducto(Request $request)
    {
        $informe = [];
        $type = $request->get('type');
        $data = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('sales.*', 'users.name as user_name', 'products.name as product_name', 'products.type as type',
            'sale_items.unit_price as price', 'sale_items.subtotal as subtotal', 'sale_items.quantity as quantity',
            'sale_payments.payment_method as payment')
            ->whereDate('sales.created_at', '=', $request->get('day'))
            ->where(function ($subQuery) use ($type) {
                if ($type != '' && $type != 'Todos') {
                    $subQuery->where('products.type', $type);
                }
            })
            ->get();

        foreach ($data as $key) {

            $informe[] = [
                'fecha' => Carbon::parse($key->created_at)->format('d/m/Y'),
                'documento' => '#00000'.$key->id,
                'producto' => $key->product_name,
                'tipo' => $key->type,
                'cantidad' => $key->quantity,
                'precio' => $key->price,
                'pago' => $key->payment,
            ];
        }

        return Pdf::loadView('pdfs.productosvendidosxdia', compact('informe'))
                ->stream(''.config('app.name', 'Laravel').' - Listado de productos vendidos por dia - ' . Carbon::now(). '.pdf');
    }

    public function informeProductosVendidos()
    {

        return view('reports.productosvendidoswitheliminados');

    }

    public function datatableProductosVendidos(Request $request)
    {
        $data = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
            ->leftJoin('products', 'sale_items.product_id', '=', 'products.id')
            ->select('sales.*', 'users.name as user_name', 'products.name as product_name', 'products.type as type',
            'sale_items.unit_price as price', 'sale_items.subtotal as subtotal', 'sale_items.quantity as quantity',
            'sale_payments.payment_method as payment')
            ->where('products.type', '!=', 'SERVICIOS');

            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('dateday') && $request->get('dateday') != '') {
                        $query->whereDate('sales.created_at', '=', $request->get('dateday'));
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('users.name', 'like', "%{$searchValue}%")
                                     ->orWhere('sales.customer_name', 'like', "%{$searchValue}%")
                                     ->orWhere('sale_payments.payment_method', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->make(true);
    }

    public function pdfProductosVendidos(Request $request)
    {
        $informe = [];
        $data = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
            ->leftJoin('products', 'sale_items.product_id', '=', 'products.id')
            ->select('sales.*', 'users.name as user_name', 'products.name as product_name', 'products.type as type',
            'sale_items.unit_price as price', 'sale_items.subtotal as subtotal', 'sale_items.quantity as quantity',
            'sale_payments.payment_method as payment')
            ->where('products.type', '!=', 'SERVICIOS')
            ->whereDate('sales.created_at', '=', $request->get('day'))
            ->get();

        foreach ($data as $key) {

            $informe[] = [
                'fecha' => Carbon::parse($key->created_at)->format('d/m/Y'),
                'documento' => '#00000'.$key->id,
                'producto' => $key->product_name,
                'tipo' => $key->type,
                'cantidad' => $key->quantity,
                'precio' => $key->price,
                'pago' => $key->payment,
            ];
        }

        return Pdf::loadView('pdfs.productosvendidoswitheliminados', compact('informe'))
                ->stream(''.config('app.name', 'Laravel').' - Listado de productos vendidos por dia incluyendo los eliminados - ' . Carbon::now(). '.pdf');
    }

    public function informeNeumaticosInternacionales()
    {
        return view('reports.neumaticosinternacionales');
    }

    public function datatableNeumaticosInternacionales(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('sales')
                    ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                    ->join('products', 'sale_items.product_id', '=', 'products.id')
                    ->select('sales.*', 'products.name as product_name',
                    'products.type as type', 'products.weight as weight',
                    'sale_items.unit_price as price', 'sale_items.quantity as quantity',
                    'sale_items.subtotal as subtotal')
                    ->where('products.type', 'NEUMATICOS')
                    ->where('products.nacionality', 'Internacional')
                    ->orderBy('sales.id', 'desc');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                    }
                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('products.name', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->make(true);
        }
    }

    public function totalneumaticos(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('sales')
                    ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                    ->join('products', 'sale_items.product_id', '=', 'products.id')
                    ->select(DB::raw('SUM(sale_items.quantity) AS total_neumaticos'),
                            DB::raw('SUM(products.weight * sale_items.quantity) AS total_peso'))
                    ->where('products.type', 'NEUMATICOS')
                    ->where('products.nacionality', 'Internacional')
                    ->where(function ($query) use ($request) {
                        if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                            $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                        }
                    })
                    ->first();


            return response()->json($data);
        }
    }

    public function pdfNeumaticosInternacionales(Request $request)
    {
        $informe = [];
        $fechaInicio = Carbon::parse($request->get('start'))->format('d/m/Y');
        $fechaFin = Carbon::parse($request->get('end'))->format('d/m/Y');
        $data = DB::table('sales')
                ->join('users', 'sales.user_id', '=', 'users.id')
                ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                ->leftjoin('products', 'sale_items.product_id', '=', 'products.id')
                ->select('sales.*', 'users.name as user_name', 'products.name as product_name',
                'products.type as type', 'products.weight as weight',
                'sale_items.unit_price as price', 'sale_items.quantity as quantity',
                'sale_items.subtotal as subtotal')
                ->where('products.type', 'NEUMATICOS')
                ->where('products.nacionality', 'Internacional')
                ->orderBy('sales.id', 'desc')
                ->where(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                    }
                })
                ->get();

        $totales = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(sale_items.quantity) AS total_neumaticos'),
                    DB::raw('SUM(products.weight * sale_items.quantity) AS total_peso'))
            ->where('products.type', 'NEUMATICOS')
            ->where('products.nacionality', 'Internacional')
            ->where(function ($query) use ($request) {
                if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                    $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                }
            })
            ->first();

        foreach ($data as $key) {

            $informe[] = [
                'fecha' => Carbon::parse($key->created_at)->format('d/m/Y'),
                'producto' => $key->product_name,
                'tipo' => $key->type,
                'cantidad' => $key->quantity,
                'costo' => $key->price,
                'subtotal' => $key->subtotal,
                'peso' => $key->weight
            ];
        }

        return Pdf::loadView('pdfs.neumaticoscomprados', compact('informe', 'fechaInicio', 'fechaFin', 'totales'))
                ->stream(''.config('app.name', 'Laravel').' - Listado de neumaticos internacionales vendidos por dia - ' . Carbon::now(). '.pdf');
    }
    
     public function NeumaticosInternacionalesExcel( Request $request )
    {
        $informe = [];
        $fechaInicio = Carbon::parse($request->get('start'))->format('d/m/Y');
        $fechaFin = Carbon::parse($request->get('end'))->format('d/m/Y');
        $data = DB::table('sales')
                ->join('users', 'sales.user_id', '=', 'users.id')
                ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                ->leftjoin('products', 'sale_items.product_id', '=', 'products.id')
                ->select('sales.*', 'users.name as user_name', 'products.name as product_name',
                'products.type as type', 'products.weight as weight',
                'sale_items.unit_price as price', 'sale_items.quantity as quantity',
                'sale_items.subtotal as subtotal')
                ->where('products.type', 'NEUMATICOS')
                ->where('products.nacionality', 'Internacional')
                ->orderBy('sales.id', 'desc')
                ->where(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                    }
                })
                ->get();

        $totales = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(sale_items.quantity) AS total_neumaticos'),
                    DB::raw('SUM(products.weight * sale_items.quantity) AS total_peso'))
            ->where('products.type', 'NEUMATICOS')
            ->where('products.nacionality', 'Internacional')
            ->where(function ($query) use ($request) {
                if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                    $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                }
            })
            ->first();

        foreach ($data as $key) {

            $informe[] = [
                'fecha' => Carbon::parse($key->created_at)->format('d/m/Y'),
                'producto' => $key->product_name,
                'tipo' => $key->type,
                'cantidad' => $key->quantity,
                'costo' => $key->price,
                'subtotal' => $key->subtotal,
                'peso' => $key->weight
            ];
        }

        return Excel::download(new NeumaticosInternacionalesExport($informe, $fechaInicio, $fechaFin, $totales), 'Neumaticos internacionales vendidos - ' . Carbon::now(). '.xlsx');
    }

}
