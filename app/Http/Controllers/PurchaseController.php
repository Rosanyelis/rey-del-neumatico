<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kardex;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Str;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use App\Models\ProductStoreQty;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('purchases.index', compact('suppliers'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('purchases')
                ->join('users', 'purchases.user_id', '=', 'users.id')
                ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                ->select('purchases.*', 'users.name as user', 'suppliers.name as supplier');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('supplier_id') && $request->get('supplier_id') != '') {
                        $query->where('purchases.supplier_id', $request->get('supplier_id'));
                    }

                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('purchases.created_at', [$request->get('start'), $request->get('end')]);
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('suppliers.name', 'like', "%{$searchValue}%")
                                     ->orWhere('users.name', 'like', "%{$searchValue}%")
                                     ->orWhere('purchases.reference', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->addColumn('actions', function ($data) {
                    return view('purchases.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function totalPurchases(Request $request)
    {
        if (request()->ajax()) {
            $data = Purchase::select(DB::raw('count(id) as total'), DB::raw('sum(total) as total_monto'))
                ->where(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('created_at', [$request->get('start'), $request->get('end')]);
                    }
                    if ($request->has('supplier_id') && $request->get('supplier_id') != '') {
                        $query->where('supplier_id', $request->get('supplier_id'));
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
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        $urlfile = null;
        if ($request->hasFile('archivo')) {
            $uploadPath = public_path('/storage/compras/');
            $file = $request->file('archivo');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/compras/'.$fileName;
            $urlfile = $url;
        }
        $productos = json_decode($request->array_products);
        $purchase = Purchase::create([
            'supplier_id'    => $request->supplier,
            'user_id'        => auth()->user()->id,
            'store_id'       => 1,
            'total'          => $request->total,
            'reference'      => $request->reference,
            'files'          => $urlfile,
            'note'           => $request->note,
            'received'       => $request->received,
            'type_purchase'  => $request->type_purchase
        ]);

        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
             $pQty = ProductStoreQty::where('product_id', $product->id)->first();
            $habian = $pQty->quantity;
            $ingresaron = $key->quantity;
            $quedan = $habian + $ingresaron;
            PurchaseItem::create([
                'purchase_id'       => $purchase->id,
                'product_id'        => $product->id,
                'quantity'          => $key->quantity,
                'cost'              => $key->cost,
                'subtotal'          => $key->total,
                'weight'            => $key->weight
            ]);

            if ($request->received == 1) {
                $p = Product::find($product->id);
                $p->update([
                    'cost' => $key->cost,
                ]);
                $productqty = ProductStoreQty::where('product_id', $p->id)->first();
                $productqty->quantity = $productqty->quantity + $key->quantity;
                $productqty->save();
                # ingresamos informacion en kardex del producto
                Kardex::create([
                    'product_id'    => $p->id,
                    'ingreso'       => $ingresaron,
                    'habian'        => $habian,
                    'salieron'      => 0,
                    'quedan'        => $quedan,
                    'quantity'      => $quedan,
                    'price'         => $key->cost,
                    'total'         => $key->total,
                    'type'          => 1,
                    'description'   => 'Compra de ' . $key->producto,
                    'user_id'       => auth()->user()->id,
                    'purchase_id'   => $purchase->id
                ]);
            }
        }

        return redirect()->route('compras.index')->with('success', 'Compra Guardada Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($purchase)
    {
        $data = Purchase::with('purchaseItems', 'supplier', 'purchaseItems.product')->find($purchase);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($purchase)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $purchase = Purchase::with('purchaseItems', 'purchaseItems.product')->find($purchase);
        return view('purchases.edit', compact('suppliers', 'products', 'purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, $purchase)
    {
        $urlfile = null;
        if ($request->hasFile('archivo')) {
            $uploadPath = public_path('/storage/compras/');
            $file = $request->file('archivo');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/compras/'.$fileName;
            $urlfile = $url;
        }

        $productos = json_decode($request->array_products);
        $purchase = Purchase::find($purchase);
        $purchase->update([
            'supplier_id'    => $request->supplier,
            'user_id'        => auth()->user()->id,
            'store_id'       => 1,
            'total'          => $request->total,
            'reference'      => $request->reference,
            'files'          => $urlfile,
            'note'           => $request->note,
            'type_purchase'  => $request->type_purchase
        ]);
        PurchaseItem::where('purchase_id', $purchase->id)->delete();
        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            PurchaseItem::create([
                'purchase_id'       => $purchase->id,
                'product_id'        => $product->id,
                'quantity'          => $key->quantity,
                'cost'              => $key->cost,
                'subtotal'          => $key->total,
                'weight'            => $key->weight
            ]);
        }
        return redirect()->route('compras.index')->with('success', 'Compra Actualizada Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($purchase)
    {
        $purchase = Purchase::find($purchase);
        $purchase->delete();

        $purchaseItems = PurchaseItem::where('purchase_id', $purchase->id)->get();
        foreach ($purchaseItems as $key) {
            $key->delete();
        }
        return redirect()->route('compras.index')->with('success', 'Compra Eliminada Exitosamente');
    }

    public function purchasepdf($purchase)
    {
        $purchase = Purchase::with('purchaseItems', 'purchaseItems.product', 'supplier')->find($purchase);
        return Pdf::loadView('pdfs.purchase', compact('purchase'))
                ->stream(''.config('app.name', 'Laravel').' - Compra.pdf');
    }

    public function purchasefactura($purchase)
    {
        $purchase = Purchase::with('purchaseItems', 'purchaseItems.product', 'supplier')->find($purchase);
        return Pdf::loadView('pdfs.purchasepos', compact('purchase'))
                ->setPaper([0,0,220,1000])
                ->stream(''.config('app.name', 'Laravel').' - Compra.pdf');
    }

    public function generateInforme()
    {
        $informe = [];
        $data = DB::table('purchases')
                ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                ->select('purchases.*', 'suppliers.name as supplier')
                ->get();
        $total = 0;
        foreach ($data as $sale) {
            $informe[] = [
                'id' => $sale->id,
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'proveedor' => $sale->supplier,
                'factura' => $sale->reference,
                'total' => $sale->total,
                'nota' => $sale->note,

            ];
            $total += $sale->total;
        }

        return Pdf::loadView('pdfs.informepurchase', compact('informe', 'total'))
                ->stream(''.config('app.name', 'Laravel').' - Informe de Compras totales - ' . Carbon::now(). '.pdf');
    }

    public function generateInformefilter(Request $request)
    {
        $informe = [];

        $query = DB::table('purchases')
        ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
        ->select('purchases.*', 'suppliers.name as supplier');
        if ($request->has('desde') && $request->has('hasta') && $request->desde != '' && $request->hasta != '') {
            $query->whereBetween('purchases.created_at', [$request->desde, $request->hasta]);
        }

        if ($request->has('supplier_id') &&  $request->supplier_id != '') {
            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        $data = $query->get();


        $total = 0;
        foreach ($data as $sale) {
            $informe[] = [
                'id' => $sale->id,
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'proveedor' => $sale->supplier,
                'factura' => $sale->reference,
                'total' => $sale->total,
                'nota' => $sale->note,

            ];
            $total += $sale->total;
        }

        return Pdf::loadView('pdfs.informepurchase', compact('informe', 'total'))
                ->stream(''.config('app.name', 'Laravel').' - Informe de Compras totales - ' . Carbon::now(). '.pdf');
    }

    public function changeStatus(Request $request)
    {
        $purchase = Purchase::with('purchaseItems', 'purchaseItems.product')->find($request->id);
        $purchase->update([
            'received' => $request->status
        ]);

        foreach ($purchase->purchaseItems as $value) {
            $product = Product::find($value->product_id);
            $product->update([
                'cost' => $value->cost,
            ]);
            $productqty = ProductStoreQty::where('product_id', $value->product_id)->first();
            $ingresaron = $value->quantity;
            $habian = $productqty->quantity;
            $quedan = $habian + $ingresaron;
            $productqty->quantity = $productqty->quantity + $value->quantity;
            $productqty->save();
            # ingresamos informacion en kardex del producto
            Kardex::create([
                'product_id'    => $value->product_id,
                'ingreso'       => $ingresaron,
                'habian'        => $habian,
                'salieron'      => 0,
                'quedan'        => $quedan,
                'quantity'      => $quedan,
                'price'         => $value->cost,
                'total'         => $value->subtotal,
                'type'          => 1,
                'description'   => 'Compra de ' . $value->product->name,
                'user_id'       => auth()->user()->id,
                'purchase_id'   => $request->id
            ]);
            
             # Buscamos el id del producto en  wordpress para actualizar su stock
            $productoWPId = $this->SearchProductByCode($product->code);
            if ($productoWPId != 'Producto no encontrado') {
                 # actualizamos el stock en wordpress
                $this->updateStockWP($productoWPId, $quedan);
            }
        }


        return redirect()->route('compras.index')->with('success', 'El estado de Compra se actualizo correctamente');
    }
    
    public function SearchProductByCode($sku)
    {
        $client = new Client([
            'base_uri' => env('WOOCOMMERCE_API_URL'),
        ]);

        $response = $client->request('GET', 'products', [
            'query' => [
                'sku' => $sku,
                'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY'),
                'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET'),
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $products = json_decode($response->getBody(), true);
            if (!empty($products)) {
                return response()->json($products[0]['id']);
            } else {
                return response()->json('Producto no encontrado');
            }
        }

        return response()->json('Error al consultar el producto.', $response->getStatusCode());
    }

    public function updateStockWP($productId, $newStockQuantity)
    {
        // Validación de parámetros
        if (!is_numeric($productId) || !is_numeric($newStockQuantity) || $newStockQuantity < 0) {
            return response()->json(['error' => 'Parámetros inválidos.'], 400);
        }

        $client = new Client([
            'base_uri' => env('WOOCOMMERCE_API_URL'),
        ]);

        try {
            // Realiza la solicitud PUT para actualizar el stock
            $response = $client->request('PUT', 'products/' . $productId, [
                'query' => [
                    'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY'),
                    'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET'),
                ],
                'json' => [
                    'stock_quantity' => $newStockQuantity,
                    'manage_stock' => true, // Asegura que WooCommerce gestione el stock
                ],
            ]);

            // Verifica si la solicitud fue exitosa
            if ($response->getStatusCode() === 200) {
                return response()->json(['message' => 'Stock actualizado exitosamente.']);
            } else {
                // Retorna un mensaje detallado si la solicitud falla
                return response()->json([
                    'error' => 'Error al actualizar el stock.',
                    'details' => json_decode($response->getBody(), true) // Incluye detalles de la respuesta
                ], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json([
                'error' => 'Excepción al intentar actualizar el stock.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
