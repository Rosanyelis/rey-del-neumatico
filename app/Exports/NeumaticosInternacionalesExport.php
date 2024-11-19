<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NeumaticosInternacionalesExport implements FromView
{
    public $informe;
    public $fechaInicio;
    public $fechaFin;
    public $totales;

    public function __construct($informe, $fechaInicio, $fechaFin, $totales)
    {
        $this->informe = $informe;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->totales = $totales;
    }

    public function view(): View
    {
        return view('exports.neumaticosinternacionales', [
            'informe' => $this->informe,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'totales' => $this->totales,
        ]);
    }
}
