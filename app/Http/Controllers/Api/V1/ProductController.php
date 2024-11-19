<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Kardex;
use GuzzleHttp\Client;
use App\Models\Product;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Request;
use App\Models\ProductStoreQty;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use App\Exports\NeumaticosInternacionalesExport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getStock($product)
    {
        $count = Product::where('code', $product)->count();
        if ($count > 0) {
            $data = Product::join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                    ->select('products.id', 'products.name', 'products.code', 'products.price', 'product_store_qties.quantity')
                    ->where('products.code', $product)
                    ->first();

            return response()->json($data, 200);
        } else {
            return response()->json(null, 404);
        }

    }

    /**
     * Display a listing of the resource.
     */
    public function updateStock(Request $request)
    {
        # buscamos el producto por el codigo
        $product = Product::with('storeqty')->where('code', $request->code)->first();
        $productqty = ProductStoreQty::where('product_id', $product->id)->first();

        $habian = $productqty->quantity;
        $salieron = $request->quantity;
        $quedan = $habian - $salieron;
        $precio = $request->price;
        $total = $request->total;
        # verificamos que su stock sea mayor a 0
        if ($productqty->quantity > 0)
        {
            # buscamos el id para actualizar el stock
            $pqty = ProductStoreQty::where('product_id', $product->id)->first();
            $pqty->quantity = $pqty->quantity - $request->quantity;
            $pqty->save();

            # ingresamos informacion en kardex del producto
            Kardex::create([
                'product_id'    => $product->id,
                'ingreso'       => 0,
                'habian'        => $habian,
                'salieron'      => $salieron,
                'quedan'        => $quedan,
                'quantity'      => $habian,
                'price'         => $precio,
                'total'         => $total,
                'type'          => 2,
                'description'   => 'Venta de ' . $product->name.' en la tienda en linea',
                'user_id'       => auth()->user()->id,
                'sale_id'       => null,
                'work_order_id' => null
            ]);
            # Buscamos el id del producto en  wordpress para actualizar su stock
            $productoWPId = $this->SearchProductByCode($request->code);
            # actualizamos el stock en wordpress
            $this->updateStockWP($productoWPId, $quedan);
            # regresa el stock actualizado
            $p = Product::with('storeqty')->where('code', $request->code)->first();
            return response()->json($p, 200);
        } else {

            return response()->json('No hay stock para este producto', 404);
        }


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
                return response()->json(['message' => 'Producto no encontrado.'], 404);
            }
        }

        return response()->json(['error' => 'Error al consultar el producto.'], $response->getStatusCode());
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
