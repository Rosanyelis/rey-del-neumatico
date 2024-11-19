<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $empresa = DB::table('stores')->first();
        View::share('empresa', $empresa);

       $data = DB::select('SELECT
                            products.id,
                            products.name,
                            products.price,
                            product_store_qties.quantity
                        FROM
                            products
                        INNER JOIN product_store_qties ON products.id = product_store_qties.product_id
                        WHERE
                            product_store_qties.quantity = 0');

        View::share('productsQty', $data);

    }
}
