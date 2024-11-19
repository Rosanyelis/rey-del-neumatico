<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Kardex;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\SaleItems;
use App\Models\WorkOrder;
use App\Models\SalePayment;
use Illuminate\Http\Request;
use App\Models\WorkOrderItems;
use App\Models\ProductStoreQty;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        $saleLast = Sale::with('customer', 'user', 'saleItems')->orderBy('id', 'desc')->first();
        return view('pos.index', compact('categories', 'saleLast'));
    }

    public function getCustomers(Request $request)
    {
        $data = Customer::select('id', DB::raw('CONCAT(rut, " - ", name) AS text'))
        ->where('rut', 'like', '%' . $request->term . '%')
        ->orWhere('name', 'like', '%' . $request->term . '%')
        ->get();
        return response()->json($data);
    }

    public function getProducts(Request $request)
    {
        $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
                    ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                    ->select('products.id', 'products.name', 'products.code', 'products.price', 'product_store_qties.quantity')
                    ->where('product_store_qties.quantity', '>', '0')
                    ->where('products.name', 'like', '%' . $request->term . '%')
                    ->orWhere('products.code', 'like', '%' . $request->term . '%')
                    ->orderBy('categories.name', 'asc', 'products.code', 'asc')
                    ->get();

        return response()->json($data);
    }

    public function getWorkorders(Request $request)
    {
        $data = WorkOrder::join('customers', 'work_orders.customer_id', '=', 'customers.id')
                        ->select('work_orders.id', 'work_orders.correlativo', 'work_orders.total', 'customers.name', 'customers.rut')
                        ->where('status', 'Completado')
                        ->where('status_payments', 'Pendiente')
                        ->where(function ($query) use ($request) {
                            $query->where('customers.rut', 'like', '%' . $request->term . '%')
                                ->orWhere('customers.name', 'like', '%' . $request->term . '%')
                                ->orwhere('correlativo', 'like', '%' . $request->term . '%');
                        })
                        ->get();
        return response()->json($data);
    }

    public function storeCustomer(Request $request)
    {
        $data = $request->all();
        $data['store_id'] = 1;
        $customer = Customer::create($data);
        return response()->json($customer);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getProductPos(Request $request)
    {
        $query = Product::join('categories', 'products.category_id', '=', 'categories.id')
                        ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                        ->select('products.id', 'products.name', 'products.image', 'products.code', 'products.price', 'product_store_qties.quantity')
                        ->where('product_store_qties.quantity', '>', '0')
                        ->orderBy('categories.name', 'asc', 'products.code', 'asc');
        // Si se proporciona un ID de categoría, se filtra por esa categoría
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        // Obtener los productos
        $productos = $query->get();
        return response()->json($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customer = Customer::find($request->customer);
        $discount = $request->discount;
        $status= '';
        if ($request->methodpay == 'Total') {
            $status = 'paid';
        } else {
            $status = 'partial';
        }
        $sale = Sale::create([
            'store_id'              => 1,
            'customer_id'           => $request->customer,
            'user_id'               => auth()->user()->id,
            'customer_name'         => $customer->name,
            'total'                 => $request->subtotal,
            'order_discount_id'     => 0,
            'total_discount'        => $discount,
            'order_tax_id'          => 0,
            'total_tax'             => 0,
            'perquisite'            => $request->perquisite,
            'grand_total'           => $request->grandtotal,
            'total_items'           => $request->total_items,
            'note'                  => $request->note_ref,
            'note_pay'              => $request->note,
            'paid'                  => $request->amount,
            'payment_status'        => $status
        ]);

        $products = json_decode($request->productos);

        foreach ($products as $key) {
            if ($key->type == 'product') {
                $product = Product::where('id', $key->id)->first();
                SaleItems::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'quantity' => $key->quantity,
                    'unit_price' => $key->price,
                    'net_unit_price' => $key->price,
                    'subtotal' => $key->subtotal,
                    'cost' => $product->cost
                ]);

                # disminuimos el stock del producto

                $productqty = ProductStoreQty::where('product_id', $key->id)->first();
                $habian = $productqty->quantity;
                $salieron = $key->quantity;
                $quedan = $habian - $salieron;

                $productqty->quantity = $productqty->quantity - $key->quantity;
                $productqty->save();


                # ingresamos informacion en kardex del producto
                Kardex::create([
                    'product_id'    => $key->id,
                    'ingreso'       => 0,
                    'habian'        => $habian,
                    'salieron'      => $salieron,
                    'quedan'        => $quedan,
                    'quantity'      => $key->quantity,
                    'price'         => $key->price,
                    'total'         => $key->subtotal,
                    'type'          => 2,
                    'description'   => 'Venta de ' . $product->name.' facturado en el POS, perteneciente a la venta #00000' . $sale->id . '',
                    'user_id'       => auth()->user()->id,
                    'sale_id'       => $sale->id
                ]);

                # Buscamos el id del producto en  wordpress para actualizar su stock
                $productoWPId = $this->SearchProductByCode($product->code);
                if ($productoWPId != 'Producto no encontrado') {
                     # actualizamos el stock en wordpress
                    $this->updateStockWP($productoWPId, $quedan);
                }
               

            }

            if ($key->type == 'workorder') {

                $Work_Orders = WorkOrder::find($key->id);
                $Work_Orders->update(['status_payments' => 'Pagado']);

                $workorder = WorkOrderItems::where('work_order_id', $key->id)->get();

                foreach ($workorder as $item) {

                    $producto = Product::where('id', $item->product_id)->first();
                    $productqty = ProductStoreQty::where('product_id', $producto->id)->first();
                    $habian = $productqty->quantity;
                    $salieron = $key->quantity;
                    $quedan = $habian - $salieron;
                    SaleItems::create([
                        'sale_id'           => $sale->id,
                        'work_order_id'     => $key->id,
                        'product_id'        => $producto->id,
                        'product_name'      => $producto->name,
                        'product_code'      => $producto->code,
                        'quantity'          => $item->quantity,
                        'unit_price'        => $item->price,
                        'net_unit_price'    => $item->price,
                        'subtotal'          => $item->total,
                        'cost'              => $item->price
                    ]);

                    # ingresamos informacion en kardex del producto
                    Kardex::create([
                        'product_id'    => $item->product_id,
                        'ingreso'       => 0,
                        'habian'        => $habian,
                        'salieron'      => $salieron,
                        'quedan'        => $quedan,
                        'quantity'      => $item->quantity,
                        'price'         => $item->price,
                        'total'         => $item->total,
                        'type'          => 2,
                        'description'   => 'Producto ' . $producto->name.' facturado en el POS, perteneciente de la orden de trabajo ' . $key->name,
                        'user_id'       => auth()->user()->id,
                        'sale_id'       => $sale->id,
                        'work_order_id' => $key->id
                    ]);

                    # Buscamos el id del producto en  wordpress para actualizar su stock
                    $productoWPId = $this->SearchProductByCode($producto->code);
                    if ($productoWPId != 'Producto no encontrado') {
                        # actualizamos el stock en wordpress
                        $this->updateStockWP($productoWPId, $quedan);
                    }
                }

            }

        }

        if ($request->methodpay == 'Total') {
            SalePayment::create([
                'store_id' => 1,
                'sale_id' => $sale->id,
                'user_id' => auth()->user()->id,
                'customer_id' => $request->customer,
                'amount' => $request->amount,
                'payment_method' => $request->paymentby,
                'note' => $request->notePay,
                'pos_paid' => $request->amount,
                'pos_balance' => $request->amount,
                'note' => $request->notepayments
            ]);
        } elseif ($request->methodpay == 'Parcial') {
            $paypartial = json_decode($request->paypartial);
            foreach ($paypartial as $key) {
                SalePayment::create([
                    'store_id' => 1,
                    'sale_id' => $sale->id,
                    'user_id' => auth()->user()->id,
                    'customer_id' => $request->customer,
                    'amount' => $key->amount,
                    'payment_method' => ($key->payment == 'Tarjeta') ? 'Tarjeta de credito' : $key->payment,
                    'note' => $request->details,
                    'pos_paid' => $request->amount,
                    'pos_balance' => $request->amount,
                ]);
            }

        }

        return redirect()->route('pos.index')->with('success', 'Venta registrada con exito');
    }
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $data = Sale::last()->get();
        return response()->json($data);
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
