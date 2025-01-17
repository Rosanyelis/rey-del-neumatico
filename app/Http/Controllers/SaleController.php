<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class SaleController extends Controller
{

    /**
     * Handles the index request for sales.
     *
     * This function is responsible for handling the index request for sales.
     * It retrieves a list of users with a role ID not equal to 1 and returns the sales index view with the list of users.
     *
     * @return \Illuminate\View\View The sales index view with the list of users.
     */
    public function index()
    {
        $users = User::where('rol_id', '!=' ,'1')->get();
        return view('sales.index', compact('users'));
    }
    /**
     * Handles the data table request for sales.
     *
     * This function is responsible for handling the data table request for sales.
     * It joins the sales table with the users and sale payments tables, and applies filters based on the request parameters.
     * It returns the data table response with the filtered data.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Http\JsonResponse The data table response.
     */
    public function datatable(Request $request)
{
    if ($request->ajax()) {
        // Seleccionar solo las columnas necesarias
        $query = Sale::query()
            ->select([
                'sales.id',
                'sales.created_at',
                'sales.customer_name',
                'sales.grand_total',
                'sales.payment_status',
                'users.name AS user_name',
                DB::raw("GROUP_CONCAT(sale_payments.payment_method SEPARATOR ', ') AS payment_method"),
            ])
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->leftJoin('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
            ->groupBy('sales.id', 'sales.created_at', 'sales.customer_name',
                'sales.grand_total', 'sales.payment_status', 'users.name');

        // Filtros dinámicos
        if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
            $query->where('sales.user_id', $request->get('user_id'));
        }

        if ($request->has('day') && $request->get('day') != '') {
            $query->whereDate('sales.created_at', $request->get('day'));
        }

        if ($request->has('search') && $request->get('search')['value'] != '') {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($subQuery) use ($searchValue) {
                $subQuery->where('users.name', 'like', "%{$searchValue}%") // Cambiado 'user_name' por 'users.name'
                         ->orWhere('sales.customer_name', 'like', "%{$searchValue}%")
                         ->orWhere('sales.payment_status', 'like', "%{$searchValue}%")
                         ->orWhere('sales.grand_total', 'like', "%{$searchValue}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('actions', function ($data) {
                // Renderizar botones de acción
                return view('sales.partials.actions', ['id' => $data->id])->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}



    public function totalSales(Request $request)
    {
        if ($request->ajax()) {
            // Función para agregar filtros dinámicos
            $applyFilters = function ($query) use ($request) {
                if ($request->has('day') && $request->get('day') != '') {
                    $query->whereDate('sales.created_at', '=', $request->get('day'));
                }
                if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                    $query->where('sales.user_id', $request->get('user_id'));
                }
            };

            // Consulta para totales de ventas y propinas
            $totals = Sale::selectRaw('SUM(grand_total) as total, SUM(perquisite) as totalpropina')
                ->where($applyFilters)
                ->first();

            $total = $totals->total ?? 0;
            $totalpropina = $totals->totalpropina ?? 0;

            // Consulta para totales por método de pago
            $payments = DB::table('sales')
                ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                ->selectRaw('SUM(sale_payments.amount) AS total, sale_payments.payment_method')
                ->where($applyFilters)
                ->groupBy('sale_payments.payment_method')
                ->get();

            // Inicialización de totales por método de pago
            $totalefectivo = 0;
            $totalcredito = 0;
            $totalcheque = 0;
            $totaltransferencia = 0;

            foreach ($payments as $key) {
                switch ($key->payment_method) {
                    case 'Efectivo':
                        $totalefectivo = floatval($key->total);
                        break;
                    case 'Tarjeta de credito':
                        $totalcredito = floatval($key->total);
                        break;
                    case 'Cheque':
                        $totalcheque = floatval($key->total);
                        break;
                    case 'Transferencia':
                        $totaltransferencia = floatval($key->total);
                        break;
                }
            }

            // Preparar respuesta
            $data = [
                'total' => $total,
                'totalefectivo' => $totalefectivo,
                'totalcredito' => $totalcredito,
                'totalcheque' => $totalcheque,
                'totaltransferencia' => $totaltransferencia,
                'totalpropina' => $totalpropina,
            ];

            return response()->json($data);
        }
    }

    /**
     * Generates an informe based on the given request parameters.
     *
     * It filters sales by day and user id, and then generates an informe with the total sales,
     * total propina, and total payments by payment method.
     *
     * @param Request $request The request object containing the day and user id parameters.
     * @return \Illuminate\Http\Response A PDF response with the generated informe.
     */
    public function generateInforme(Request $request)
    {
        // Filtros dinámicos
        $filters = function ($query) use ($request) {
            if ($request->has('day') && $request->get('day') != '') {
                $query->whereDate('sales.created_at', '=', $request->get('day'));
            }
            if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                $query->where('sales.user_id', $request->get('user_id'));
            }
        };

        // Consulta optimizada con relaciones necesarias
        $sales = Sale::with(['user', 'payments'])
            ->select('sales.*')
            ->where($filters)
            ->get();

        // Datos generales
        $vendedor = $request->get('user_id') == 'Todos' ? '' : User::find($request->get('user_id'))->name;
        $dia = $request->get('day');

        // Inicialización de totales
        $total = 0;
        $propina = 0;
        $efectivo = 0;
        $credito = 0;
        $cheque = 0;
        $transferencia = 0;

        // Informe de ventas
        $informe = $sales->map(function ($sale) use (&$total, &$propina) {
            $total += $sale->grand_total;
            $propina += $sale->perquisite;

            return [
                'id' => $sale->id,
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'cliente' => $sale->customer_name,
                'vendedor' => $sale->user->name ?? '',
                'propina' => $sale->perquisite,
                'total' => $sale->grand_total,
            ];
        });

        // Totales por método de pago
        $payments = DB::table('sales')
            ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
            ->select(DB::raw('SUM(sale_payments.pos_paid) AS total'), 'sale_payments.payment_method')
            ->where($filters)
            ->groupBy('sale_payments.payment_method')
            ->get();

        foreach ($payments as $key) {
            switch ($key->payment_method) {
                case 'Efectivo':
                    $efectivo = floatval($key->total);
                    break;
                case 'Tarjeta de credito':
                    $credito = floatval($key->total);
                    break;
                case 'Cheque':
                    $cheque = floatval($key->total);
                    break;
                case 'Transferencia':
                    $transferencia = floatval($key->total);
                    break;
            }
        }

        // Generar PDF
        return Pdf::loadView(
            'pdfs.informesales',
            compact('informe', 'total', 'propina', 'efectivo', 'credito', 'cheque', 'transferencia', 'dia', 'vendedor')
        )->stream(
            config('app.name', 'Laravel') . '- Informe de ventas por vendedor y dia - ' . Carbon::now('America/Santiago')->format('d/m/Y') . '.pdf'
        );
    }

    /**
     * Handles the index2 request for sales.
     *
     * This function is responsible for handling the index2 request for sales.
     * It retrieves a list of users with a role ID not equal to 1 and returns the sales index2 view.
     *
     * @return \Illuminate\View\View The sales index2 view.
     */
    public function indexmonth()
    {
        $users = User::where('rol_id', '!=' ,'1')->get();
        return view('sales.index2', compact('users'));
    }
    /**
     * Handles the AJAX request for the datatable by month.
     *
     * It filters sales by user id, month, and search query, and then returns the datatable response.
     *
     * @param Request $request The request object containing the user id, month, and search query parameters.
     * @return \Illuminate\Http\JsonResponse A JSON response with the datatable data.
     */
    public function datatablexmonth(Request $request)
    {
        if ($request->ajax()) {
            // Construcción inicial de la consulta
            $data = DB::table('sales')
                ->join('users', 'users.id', '=', 'sales.user_id')
                ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                ->select(
                    'sales.id',
                    'sales.created_at',
                    'sales.customer_name',
                    'sales.grand_total',
                    'sales.payment_status',
                    'users.name AS user_name',
                    DB::raw("GROUP_CONCAT(DISTINCT sale_payments.payment_method SEPARATOR ', ') AS payment_method")
                )
                ->groupBy('sales.id', 'sales.created_at', 'sales.customer_name', 'sales.grand_total', 'sales.payment_status', 'users.name')
                ->orderBy('sales.id', 'desc');

            // Aplicar filtros según los parámetros recibidos
            if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                $data->where('sales.user_id', $request->get('user_id'));
            }

            if ($request->has('month') && $request->get('month') != '') {
                $data->whereMonth('sales.created_at', '=', $request->get('month'));
            }

            if ($request->has('search') && $request->get('search')['value'] != '') {
                $searchValue = $request->get('search')['value'];
                $data->where(function ($query) use ($searchValue) {
                    $query->where('users.name', 'like', "%{$searchValue}%")
                          ->orWhere('sales.customer_name', 'like', "%{$searchValue}%")
                          ->orWhere('sales.payment_status', 'like', "%{$searchValue}%");
                });
            }

            // Configuración de DataTables
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    // Renderizado de las acciones usando un componente parcial
                    return view('sales.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions']) // Permitir HTML en la columna 'actions'
                ->make(true);
        }
    }

    /**
     * Handles an AJAX request to retrieve total sales data for a given month.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the total sales data.
     */
    public function totalSalesxmonth(Request $request)
    {
        if ($request->ajax()) {
            // Consulta principal para obtener totales
            $salesQuery = DB::table('sales')
                ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                ->select(
                    DB::raw('SUM(sales.grand_total) AS total'),
                    DB::raw('SUM(sales.perquisite) AS totalpropina'),
                    DB::raw('SUM(CASE WHEN sale_payments.payment_method = "Efectivo" THEN sale_payments.amount ELSE 0 END) AS totalefectivo'),
                    DB::raw('SUM(CASE WHEN sale_payments.payment_method = "Tarjeta de credito" THEN sale_payments.amount ELSE 0 END) AS totalcredito'),
                    DB::raw('SUM(CASE WHEN sale_payments.payment_method = "Cheque" THEN sale_payments.amount ELSE 0 END) AS totalcheque'),
                    DB::raw('SUM(CASE WHEN sale_payments.payment_method = "Transferencia" THEN sale_payments.amount ELSE 0 END) AS totaltransferencia')
                );

            // Aplicar filtros según parámetros
            if ($request->has('month') && $request->get('month') != '') {
                $salesQuery->whereMonth('sales.created_at', '=', $request->get('month'));
            }
            if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                $salesQuery->where('sales.user_id', $request->get('user_id'));
            }

            // Ejecutar la consulta
            $salesData = $salesQuery->first();

            // Preparar respuesta con valores calculados
            $data = [
                'total' => $salesData->total ?? 0,
                'totalefectivo' => $salesData->totalefectivo ?? 0,
                'totalcredito' => $salesData->totalcredito ?? 0,
                'totalcheque' => $salesData->totalcheque ?? 0,
                'totaltransferencia' => $salesData->totaltransferencia ?? 0,
                'totalpropina' => $salesData->totalpropina ?? 0,
            ];

            return response()->json($data);
        }
    }

    public function generateInformexmes(Request $request)
    {
        $total = 0;
        $totalefectivo = 0;
        $totalcredito = 0;
        $totalcheque = 0;
        $totaltransferencia = 0;
        $totalpropina = 0;
        $mes = $request->get('month')
            ? Carbon::createFromFormat('!m', $request->get('month'))->translatedFormat('F')
            : '';
        $vendedor = ($request->get('user_id') != 'Todos')
            ? User::find($request->get('user_id'))->name
            : 'Todos';

        // Consulta consolidada para obtener ventas y totales por método de pago
        $salesData = DB::table('sales')
            ->leftJoin('users', 'sales.user_id', '=', 'users.id')
            ->leftJoin('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
            ->select(
                'sales.id',
                'sales.created_at',
                'sales.customer_name',
                'users.name AS user_name',
                'sales.perquisite',
                'sales.grand_total',
                DB::raw('SUM(CASE WHEN sale_payments.payment_method = "Efectivo" THEN sale_payments.pos_paid ELSE 0 END) AS totalefectivo'),
                DB::raw('SUM(CASE WHEN sale_payments.payment_method = "Tarjeta de credito" THEN sale_payments.pos_paid ELSE 0 END) AS totalcredito'),
                DB::raw('SUM(CASE WHEN sale_payments.payment_method = "Cheque" THEN sale_payments.pos_paid ELSE 0 END) AS totalcheque'),
                DB::raw('SUM(CASE WHEN sale_payments.payment_method = "Transferencia" THEN sale_payments.pos_paid ELSE 0 END) AS totaltransferencia')
            )
            ->when($request->get('month'), function ($query) use ($request) {
                $query->whereMonth('sales.created_at', '=', $request->get('month'));
            })
            ->when($request->get('user_id') != 'Todos', function ($query) use ($request) {
                $query->where('sales.user_id', '=', $request->get('user_id'));
            })
            ->groupBy('sales.id', 'sales.created_at', 'sales.customer_name', 'users.name', 'sales.perquisite', 'sales.grand_total')
            ->get();

        // Preparar datos para el informe
        $informe = $salesData->map(function ($sale) use (&$total, &$totalpropina, &$totalefectivo, &$totalcredito, &$totalcheque, &$totaltransferencia) {
            $total += $sale->grand_total;
            $totalpropina += $sale->perquisite;
            $totalefectivo += $sale->totalefectivo;
            $totalcredito += $sale->totalcredito;
            $totalcheque += $sale->totalcheque;
            $totaltransferencia += $sale->totaltransferencia;

            return [
                'id' => $sale->id,
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'cliente' => $sale->customer_name,
                'vendedor' => $sale->user_name,
                'propina' => $sale->perquisite,
                'total' => $sale->grand_total,
            ];
        })->toArray();

        // Generar el PDF
        return Pdf::loadView('pdfs.informesalesmonth', compact(
            'informe',
            'total',
            'totalefectivo',
            'totalcredito',
            'totalcheque',
            'totaltransferencia',
            'totalpropina',
            'mes',
            'vendedor'
        ))->stream('' . config('app.name', 'Laravel') . '- Informe de ventas por vendedor y mes - ' . Carbon::now('America/Santiago')->format('d/m/Y') . '.pdf');
    }

    /**
     * Handles the indexrange request for sales.
     *
     * This function is responsible for handling the indexrange request for sales.
     * It retrieves a list of users with a role ID not equal to 1 and returns the sales index3 view.
     *
     * @return \Illuminate\View\View The sales index3 view.
     */
    public function indexrange()
    {
        $users = User::where('rol_id', '!=' ,'1')->get();
        return view('sales.index3', compact('users'));
    }
    /**
     * Handles an AJAX request to retrieve sales data for a given date range.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the sales data.
     */
    public function datatablexrange(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('sales')
                ->join('users', 'users.id', '=', 'sales.user_id')
                ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                ->select('sales.*', 'users.name AS user_name', DB::raw("GROUP_CONCAT(sale_payments.payment_method SEPARATOR ', ') AS payment_method"))
                ->groupBy('sales.id', 'sales.created_at', 'sales.customer_name', 'sales.grand_total', 'users.name')
                ->orderBy('id', 'desc');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                    }

                    if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                        $query->where('sales.user_id', $request->get('user_id'));
                    }


                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('users.name', 'like', "%{$searchValue}%")
                                     ->orWhere('sales.customer_name', 'like', "%{$searchValue}%")
                                     ->orWhere('sales.payment_status', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->addColumn('actions', function ($data) {
                    return view('sales.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }
    /**
     * Retrieves the total sales within a specified range.
     *
     * @param Request $request The HTTP request object containing the start and end dates,
     *                         as well as the user ID (optional).
     * @return \Illuminate\Http\JsonResponse The JSON response containing the total sales,
     *                                       as well as the total sales by payment method.
     */
    public function totalSalesxrange(Request $request)
    {
        if ($request->ajax()) {
            $object = Sale::where(function ($query) use ($request) {
                if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                    $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                }
                if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                    $query->where('sales.user_id', $request->get('user_id'));
                }
            })->get();

            $total = 0; $totalefectivo = 0; $totalcredito = 0; $totalcheque = 0;
            $totaltransferencia = 0; $totalpropina = 0;

            foreach ($object as $sale) {
                $total += $sale->grand_total;
                $totalpropina += $sale->perquisite;
            }

            $payments = DB::table('sales')
                    ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                    ->select(DB::raw('SUM(sale_payments.amount) AS total'), 'sale_payments.payment_method')
                    ->groupBy('sale_payments.payment_method')
                    ->where(function ($query) use ($request) {
                        if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                            $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                        }
                        if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                            $query->where('sales.user_id', $request->get('user_id'));
                        }
                    })
                    ->get();

            foreach ($payments as $key) {
                if ($key->payment_method == 'Efectivo') {
                    $totalefectivo = floatval($key->total);
                } else if ($key->payment_method == 'Tarjeta de credito') {
                    $totalcredito = floatval($key->total);
                } else if ($key->payment_method == 'Cheque') {
                    $totalcheque = floatval($key->total);
                } else if ($key->payment_method == 'Transferencia') {
                    $totaltransferencia = floatval($key->total);
                }
            }

            $data = [
                'total' => $total,
                'totalefectivo' => $totalefectivo,
                'totalcredito' => $totalcredito,
                'totalcheque' => $totalcheque,
                'totaltransferencia' => $totaltransferencia,
                'totalpropina' => $totalpropina
            ];
            return response()->json($data);
        }
    }
    /**
     * Generates an Informe de ventas por vendedor y mes PDF document based on the provided request data.
     *
     * @param Request $request The request object containing the start and end dates, and the user ID.
     * @return \Illuminate\Http\Response The generated PDF document.
     */
    public function generateInformexrange( Request $request )
    {
        $informe            = [];
        $total              = 0;
        $totalefectivo      = 0;
        $totalcredito       = 0;
        $totalcheque        = 0;
        $totaltransferencia = 0;
        $totalpropina       = 0;
        $fechai             = ($request->get('start') != '') ? Carbon::parse($request->get('start'))->format('d/m/Y') : '';
        $fechaf             = ($request->get('end') != '') ? Carbon::parse($request->get('end'))->format('d/m/Y') : '';
        $vendedor           = ($request->get('user_id') != 'Todos') ? User::find($request->get('user_id'))->name : 'Todos';

        $data = Sale::where(function ($query) use ($request) {
            if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
            }
            if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                $query->where('sales.user_id', $request->get('user_id'));
            }
        })->get();

        foreach ($data as $sale) {
            $informe[] = [
                'id'        => $sale->id,
                'fecha'     => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'cliente'   => $sale->customer_name,
                'vendedor'  => $sale->user_name,
                'propina'   => $sale->perquisite,
                'total'     => $sale->grand_total,
            ];
            $total += $sale->grand_total;
            $totalpropina += $sale->perquisite;

            $payments = DB::table('sales')
                ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                ->select(DB::raw('SUM(sale_payments.pos_paid) AS total'), 'sale_payments.payment_method')
                ->where(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                    }
                    if ($request->has('user_id') && $request->get('user_id') != 'Todos') {
                        $query->where('sales.user_id', $request->get('user_id'));
                    }
                })
                ->groupBy('sale_payments.payment_method')
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
        return Pdf::loadView('pdfs.informesalesrange',
                    compact('informe', 'total', 'totalefectivo', 'totalcredito',
                        'totalcheque', 'totaltransferencia', 'totalpropina', 'fechai', 'fechaf', 'vendedor'))
                ->stream(''.config('app.name', 'Laravel').'- Informe de ventas por vendedor y mes - '. Carbon::now('America/Santiago')->format('d/m/Y'). '.pdf');
    }
    /**
     * Display the specified resource.
     */
    public function show($sale)
    {
        $data = Sale::with('user', 'saleitems', 'customer', 'payments')->find($sale);
        return response()->json($data);
    }

    public function generateInvoice($sale)
    {
        $sale = Sale::with('user', 'saleitems', 'customer', 'payments')->find($sale);
        return Pdf::loadView('pdfs.invoice', compact('sale'))
                ->stream(''.config('app.name', 'Laravel').' - Factura - ' . $sale->customer_name. '.pdf');
    }

    public function generateTicket($sale)
    {
        $sale = Sale::with('user', 'saleitems', 'customer', 'payments')->find($sale);
        return Pdf::loadView('pdfs.factura', compact('sale'))
                ->setPaper([0,0,220,1000])
                ->stream(''.config('app.name', 'Laravel').' - Factura - ticket -' . $sale->customer_name. '.pdf');
    }

    public function printPos($sale)
    {
        $sale = Sale::with('user', 'saleitems', 'customer', 'payments')->find($sale);

        $nombreImpresora = "POS-58";
        $connector = new WindowsPrintConnector($nombreImpresora);


        $impresora = new Printer($connector);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        /** Encabezado */
        $logo = EscposImage::load(public_path('assets/images/logo-official.png'), false);
        $impresora->bitImage($logo);

        $impresora->setTextSize(2, 2);
        $impresora->text("Rey del Neumatico" . "\n");
        $impresora->setTextSize(1, 1);
        $impresora->text("Jose Joaquin Prieto 5780 - San Miguel - Santiago" . "\n");
        $impresora->text("vulca_david@hotmail.com" . "\n");
        $impresora->text("56652759029 - 56413243313 - 56232075270" . "\n");
        $impresora->text("Documento #00000".$sale->id. "\n");
        /** Encabezado */

        /** Datos de Cliente */
        $impresora->text("Cliente: " . $sale->customer->name . "\n");
        $impresora->text("Rut: " . $sale->customer->rut . "\n");
        $impresora->text("Correo: " . $sale->customer->email . "\n");
        $impresora->text("Tlf: " . $sale->customer->phone . "\n");
        $impresora->text("Dirección: " . $sale->customer->address . "\n");
        $impresora->text("Fecha: " . \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i A') . "\n");
        $impresora->text("Vendedor: " . $sale->user->name . "\n");


        /** Cuerpo */
        $impresora->text("CANT" . "\t" . "DESCRIPCION" . "\t" . "PRECIO" . "\t" . "TOTAL" . "\n");
        $impresora->text("------------------------------------------------" . "\n");

        foreach ($sale->saleitems as $key) {
            $impresora->text(number_format($key->quantity, 0, ',', '.') . "\t" . $key->product_name . "\t" . number_format($key->unit_price, 0, ',', '.') . "\t" . number_format($key->subtotal, 0, ',', '.') . "\n");
        }
        $impresora->setJustification(Printer::JUSTIFY_RIGHT);
        $impresora->text("------------------------------------------------" . "\n");
        $impresora->text("Subtotal: " . number_format($sale->total, 0, ',', '.') . "\n");
        $impresora->text("Descuento (%".$sale->order_discount_id. "): " . number_format($sale->total * ($sale->order_discount_id / 100), 0, ',', '.') . "\n");
        $impresora->text("Impuesto (%" . number_format($sale->order_tax_id, 0, ',', '.')."): ". number_format($sale->total * ($sale->order_tax_id / 100), 0, ',', '.') . "\n");
        $impresora->text("Total: " . number_format($sale->grand_total, 0, ',', '.') . "\n");

        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("------------------------------------------------" . "\n");
        $impresora->setTextSize(2, 2);
        $impresora->text("GRACIAS POR SU COMPRA" . "\n");
        $impresora->text("¡HASTA PRONTO!" . "\n");
        $impresora->feed(2);
        $impresora->cut();
        $impresora->close();
    }
}
