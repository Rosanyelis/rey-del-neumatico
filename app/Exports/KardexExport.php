<?php

namespace App\Exports;

use App\Models\Kardex;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KardexExport implements FromView
{
    public $start;
    public $end;
    public $producto;

    public function __construct($start, $end, $producto)
    {
        $this->start = $start;
        $this->end = $end;
        $this->producto = $producto;
    }
    public function view(): View
    {
        $start = $this->start;
        $end = $this->end;
        $producto = $this->producto;
        # consulta
        $data = Kardex::select([
            'kardexes.created_at AS fecha',
            'p.name AS producto',
            'kardexes.ingreso AS ingresaron',
            'kardexes.habian AS habian',
            'kardexes.salieron AS salieron',
            'kardexes.quedan AS quedan',
            'kardexes.quantity AS cantidad',
            'kardexes.price AS precio', 'kardexes.total AS total',
            DB::raw('CASE kardexes.type
                WHEN 1 THEN \'Ingreso\'
                WHEN 2 THEN \'Salida\'
                WHEN 3 THEN \'Ajuste\'
                WHEN 4 THEN \'Eliminado\'
                ELSE \'Otro\'
            END AS tipo_movimiento'),
            'kardexes.description AS descripcion',
            'u.name AS usuario',
        ])
        ->leftJoin('products as p', 'p.id', '=', 'kardexes.product_id')
        ->leftJoin('users as u', 'u.id', '=', 'kardexes.user_id')
        ->orderBy('kardexes.created_at', 'desc')
        ->where(function ($query) use ($start, $end) {
            if ($start != '' && $end != '') {
                $query->whereBetween('kardexes.created_at', [$start, $end]);
            }
        })
        ->where('kardexes.product_id', $producto)
        ->get();


        return view('exports.kardex', [
            'kardex' => $data
        ]);
    }
}
