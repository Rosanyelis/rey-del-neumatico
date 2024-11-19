<?php

namespace App\Http\Controllers;

use App\Models\Kardex;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\TypeProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\ProductStoreQty;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Exports\ProductsInternationalExport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorys = Category::all();
        return view('products.index', compact('categorys'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->leftjoin('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
            ->select('products.*', 'product_store_qties.quantity as stock', 'categories.name as category_name')
            ->orderBy('categories.name', 'asc', 'products.code', 'asc');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('category_id') && $request->get('category_id') != '') {
                        $query->where('category_id', $request->get('category_id'));
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('products.code', 'like', "%{$searchValue}%")
                                     ->orWhere('products.name', 'like', "%{$searchValue}%")
                                     ->orWhere('products.type', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->addColumn('actions', function ($data) {
                    return view('products.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function totalProductos(Request $request)
    {
        if ($request->ajax()) {
            $total = DB::table('products')
            ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
            ->where('type', '!=', 'SERVICIOS')
            ->select(DB::raw('SUM(product_store_qties.quantity) as total_products'),
                    DB::raw('SUM(products.cost * product_store_qties.quantity) as total'))
            ->where(function ($query) use ($request) {
                if ($request->has('category_id') && $request->get('category_id') != '') {
                    $query->where('products.category_id', $request->get('category_id'));
                }
            })
            ->first();

            $totalp = Product::where(function ($query) use ($request) {
                if ($request->has('category_id') && $request->get('category_id') != '') {
                    $query->where('products.category_id', $request->get('category_id'));
                }
            })
            ->count();

            $data = [
                'total' => $totalp,
                'stock' => $total->total_products,
                'totalclp' => $total->total
            ];

            return response()->json($data);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category= Category::all();
        $typeproduct = TypeProduct::all();
        return view('products.create', compact('category', 'typeproduct'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->all();
        $data['image'] = null;
        if ($request->hasFile('image')) {
            $uploadPath = public_path('/storage/productos/');
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/productos/'.$fileName;
            $foto = $url;
            $data['image'] = $url;
        }

        $code = trim($data['code']);
        $codeclean = str_replace(' ', '', $code);

        $producto = Product::create([
            'code'              => $codeclean,
            'name'              => $data['name'],
            'category_id'       => $data['category_id'],
            'type'              => $data['type'],
            'cost'              => $data['cost'],
            'price'             => $data['price'],
            'image'             => $data['image'],
            'description'       => $data['description'],
            'barcode_symbology' => 'code128',
            'alert_quantity'    => $data['alert_quantity'],
            'max_quantity'      => $data['max_quantity'],
            'weight'            => $data['weight'],
            'nacionality'       => $data['nacionality'],
            'cellar'            => $data['cellar'],
            'hail'              => $data['hail'],
            'rack'              => $data['rack'],
            'position'          => $data['position'],
        ]);

        ProductStoreQty::create([
            'store_id'   => 1,
            'product_id' => $producto->id,
            'quantity'   => $data['quantity'],
            'price'      => $data['price'],
        ]);

        Kardex::create([
            'product_id'    => $producto->id,
            'ingreso'       => $data['quantity'],
            'habian'        => 0,
            'salieron'      => 0,
            'quedan'        => $data['quantity'],
            'quantity'      => $data['quantity'],
            'price'         => $data['cost'],
            'total'         => $data['cost'],
            'type'          => 1,
            'description'   => 'Registro del producto ' . $data['name'],
            'user_id'       => auth()->user()->id
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado con exito');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product)
    {
        $category= Category::all();
        $typeproduct = TypeProduct::all();
        $product = Product::find($product);
        return view('products.edit', compact('product', 'category', 'typeproduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $product)
    {
        $data = $request->all();

        $product = Product::find($product);
        $data['image'] = $product->image;
        if ($request->hasFile('image')) {
            $uploadPath = public_path('/storage/productos/');
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/productos/'.$fileName;
            $foto = $url;
            $data['image'] = $url;
        }
        $code = trim($data['code']);
        $codeclean = str_replace(' ', '', $code);
        $product->update([
            'code'              => $codeclean,
            'name'              => $data['name'],
            'category_id'       => $data['category_id'],
            'type'              => $data['type'],
            'cost'              => $data['cost'],
            'price'             => $data['price'],
            'image'             => $data['image'],
            'description'       => $data['description'],
            'barcode_symbology' => 'code128',
            'alert_quantity'    => $data['alert_quantity'],
            'max_quantity'      => $data['max_quantity'],
            'weight'            => $data['weight'],
            'nacionality'       => $data['nacionality'],
            'cellar'            => $data['cellar'],
            'hail'              => $data['hail'],
            'rack'              => $data['rack'],
            'position'          => $data['position'],
        ]);

        $productQty = ProductStoreQty::where('product_id', $product->id)->first();
        $habian = $productQty->quantity;
        $productQty->update([
            'quantity'   => $data['quantity'],
            'price'      => $data['price'],
        ]);
        
        Kardex::create([
            'product_id'    => $product->id,
            'ingreso'       => 0,
            'habian'        => $habian,
            'salieron'      => 0,
            'quedan'        => $data['quantity'],
            'quantity'      => $data['quantity'],
            'price'         => $data['price'],
            'total'         => $data['price'],
            'type'          => 3,
            'description'   => 'Edicion del producto ' . $data['name'],
            'user_id'       => auth()->user()->id
        ]);
        
        $quedan = $data['quantity'];
        # Buscamos el id del producto en  wordpress para actualizar su stock
        $productoWPId = $this->SearchProductByCode($product->code);
        if ($productoWPId != 'Producto no encontrado') {
            # actualizamos el stock en wordpress
            $this->updateStockWP($productoWPId, $quedan);
        }
        
        return redirect()->route('productos.index')->with('success', 'Producto actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product)
    {
        $dato = ProductStoreQty::where('product_id', $product)->first();
        $data = Product::find($product);
        Kardex::create([
            'product_id'    => $product,
            'ingreso'       => 0,
            'habian'        => $dato->quantity,
            'salieron'      => $dato->quantity,
            'quedan'        => 0,
            'quantity'      => $dato->quantity,
            'price'         => $dato->price,
            'total'         => $dato->price * $dato->quantity,
            'type'          => 4,
            'description'   => 'Eliminacion del producto ' . $data->name,
            'user_id'       => auth()->user()->id
        ]);


        ProductStoreQty::where('product_id', $product)->delete();

        $data = Product::find($product);
        $data->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado con exito');
    }

    /**
     * Renders the view for importing categories.
     *
     */
    public function view_import()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        Excel::import(new ProductsImport, $request->file('file'));
        return redirect()->route('productos.index')->with('success', 'Productos importados con exito');
    }

    public function allproductpdf()
    {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                ->select('products.*', 'product_store_qties.quantity as stock', 'categories.name as category_name')
                ->orderBy('categories.name', 'asc', 'products.code', 'asc')
                ->get();
        return Pdf::loadView('pdfs.allproducts', compact('products'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos.pdf');
    }

    public function generateInformefilter(Request $request)
    {
        $products = Product::where('category_id', $request->category_id)->get();
        return Pdf::loadView('pdfs.porcategoria', compact('products'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos.pdf');
    }

    public function kardex(Request $request, $product)
    {
        $producto = Product::find($product);
        if ($request->ajax()) {
            $data = DB::table('kardexes')
            ->join('products', 'products.id', '=', 'kardexes.product_id')
            ->leftjoin('users', 'users.id', '=', 'kardexes.user_id')
            ->where('kardexes.product_id', $product)
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
        return view('products.kardex', compact('producto'));
    }

    public function kardexpdf($product)
    {
        $producto = Product::find($product);
        $kardexes = DB::table('kardexes')
            ->join('products', 'kardexes.product_id', '=', 'products.id')
            ->where('kardexes.product_id', $producto->id)
            ->select('kardexes.*', 'products.name as product_name')
            ->get();
        return Pdf::loadView('pdfs.kardex', compact('kardexes', 'producto'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Kardex.pdf');
    }

    public function allproductbarspdf()
    {
        $products = Product::all();
        return Pdf::loadView('pdfs.productsbars', compact('products'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos con codigo de barrras.pdf');
    }

    public function kardexpdffilter(Request $request, $product)
    {
        $producto = Product::find($product);
        $kardexes = DB::table('kardexes')
            ->join('products', 'kardexes.product_id', '=', 'products.id')
            ->where('kardexes.product_id', $producto->id)
            ->whereBetween('kardexes.created_at', [$request->start, $request->end])
            ->select('kardexes.*', 'products.name as product_name')
            ->get();
        return Pdf::loadView('pdfs.kardex', compact('kardexes', 'producto'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Kardex.pdf');
    }

    public function export()
    {
        return Excel::download(new ProductsInternationalExport, 'neumaticos-internacionales.xlsx');
    }

    public function exportproduct()
    {
        return Excel::download(new ProductsExport, 'productos.xlsx');
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
