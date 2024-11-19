<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Quotation;
use App\Mail\SendQuotation;
use Illuminate\Http\Request;
use App\Models\QuotationItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;

class QuotationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('rol_id', '!=' ,'1')->get();
        return view('quotes.index', compact('users'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('quotations')
                ->join('users', 'quotations.user_id', '=', 'users.id')
                ->join('customers', 'quotations.customer_id', '=', 'customers.id')
                ->select('quotations.*', 'users.name as user', 'customers.name as customer', 'customers.rut as rut')
                ->orderby('quotations.id', 'desc');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('user_id') && $request->get('user_id') != '') {
                        $query->where('quotations.user_id', $request->get('user_id'));
                    }

                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('quotations.created_at', [$request->get('start'), $request->get('end')]);
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('customers.name', 'like', "%{$searchValue}%")
                                     ->orWhere('users.name', 'like', "%{$searchValue}%")
                                     ->orWhere('customers.rut', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->addColumn('actions', function ($data) {
                    return view('quotes.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function totalQuotes(Request $request)
    {
        if ($request->ajax()) {
            $data = Quotation::select(DB::raw('count(id) as total'), DB::raw('sum(grand_total) as total_monto'))
                ->where(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('created_at', [$request->get('start'), $request->get('end')]);
                    }
                    if ($request->has('user_id') && $request->get('user_id') != '') {
                        $query->where('user_id', $request->get('user_id'));
                    }
                })
                ->first();
            return response()->json($data);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('quotes.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuotationRequest $request)
    {
        $correlativoInicial = 1001;
        $nroOrden = 0;
        $count = Quotation::count();
        if ($count > 0) {
            $data = Quotation::latest()->first();
            $nroOrden = $data->correlativo + 1;
        } else {
            $nroOrden = 1001;
        }
        $customer = Customer::where('name', $request->customer)->first();
        $productos = json_decode($request->array_products);
        $descuento = $request->total * ($request->order_discount_id / 100);
        $grandtotal = $request->total - $descuento;
        $quote = Quotation::create([
            'customer_id'    => $customer->id,
            'user_id'        => auth()->user()->id,
            'store_id'       => 1,
            'correlativo'    => $nroOrden,
            'customer_name'  => $request->customer,
            'order_discount_id' => $request->order_discount_id,
            'order_tax_id' => $request->order_tax_id,
            'total_discount' => $descuento,
            'total'          => $request->total,
            'grand_total'    => $grandtotal,
            'total_items'    => count($productos),
            'note'           => $request->note
        ]);

        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            QuotationItems::create([
                'quotation_id'   => $quote->id,
                'product_id'     => $product->id,
                'product_name'   => $key->producto,
                'product_code'   => $product->code,
                'quantity'       => $key->quantity,
                'unit_price'     => $key->price,
                'net_unit_price' => $key->price,
                'discount'       => $key->discount,
                'subtotal'       => $key->total,
                'real_unit_price' => $key->total,
            ]);
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización Guardada Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($quotation)
    {
        $data = Quotation::with('items')->find($quotation);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($quotation)
    {
        $customers = Customer::all();
        $products = Product::all();
        $quotation = Quotation::with('items')->find($quotation);
        return view('quotes.edit', compact('quotation', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotationRequest $request, $quotation)
    {
        $customer = Customer::where('name', $request->customer)->first();
        $productos = json_decode($request->array_products);
        $descuento = $request->total * ($request->order_discount_id / 100);
        $grandtotal = $request->total - $descuento;
        $quote = Quotation::find($quotation);
        $quote->update([
            'customer_id'    => $customer->id,
            'user_id'        => auth()->user()->id,
            'store_id'       => 1,
            'customer_name'  => $request->customer,
            'order_discount_id' => $request->order_discount_id,
            'order_tax_id' => $request->order_tax_id,
            'total_discount' => $descuento,
            'total'          => $request->total,
            'grand_total'    => $grandtotal,
            'total_items'    => count($productos),
            'note'           => $request->note
        ]);

        $quote->items()->delete();

        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            QuotationItems::create([
                'quotation_id'   => $quote->id,
                'product_id'     => $product->id,
                'product_name'   => $key->producto,
                'product_code'   => $product->code,
                'quantity'       => $key->quantity,
                'unit_price'     => $key->price,
                'net_unit_price' => $key->price,
                'discount'       => $key->discount,
                'subtotal'       => $key->total,
                'real_unit_price' => $key->total,
            ]);
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización Actualizada Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($quotation)
    {
        $quotation = Quotation::find($quotation);
        $quotation->delete();

        $quotationItems = QuotationItems::where('quotation_id', $quotation->id)->get();
        foreach ($quotationItems as $key) {
            $key->delete();
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada con exito');
    }

    public function quotepdf($quotation)
    {
        $quotation = Quotation::find($quotation);
        return Pdf::loadView('pdfs.quotation', compact('quotation'))
                ->stream(''.config('app.name', 'Laravel').' - Cotizacion.pdf');
    }

    public function productjson($quotation)
    {
        $data = Product::find($quotation);
        return response()->json($data);
    }

    public function sendEmailQuotepdf($quotation)
    {
        $quotation = Quotation::with('customer')->find($quotation);

        if ($quotation->customer->email == null) {
            return redirect()->route('cotizaciones.index')->with('error', 'El Cliente no posee correo para enviar la cotizacion');
        }

        $publicpath = public_path('storage/cotizaciones/');
        $namepdf = config('app.name', 'Laravel').' - cotizacion - '.$quotation->customer_name.' - '.date('Y-m-d').'.pdf';
        $urlpdf = $publicpath.$namepdf;


        $pdf = Pdf::loadView('pdfs.quotation', compact('quotation'))
                ->save($urlpdf);

        try {
            Mail::to($quotation->customer->email)
            ->cc('ventas@reydelneumatico.cl')
            ->send(new SendQuotation($quotation, $urlpdf, $namepdf));

            return redirect()->route('cotizaciones.index')->with('success', 'Cotizacion Enviada Exitosamente');
        } catch (\Throwable $th) {
            Log::error("error al enviar cotizacion: ".$th->getMessage());

            return redirect()->route('cotizaciones.index')->with('error', 'Error al enviar la cotizacion, verifique su correo');
        }

    }
}
