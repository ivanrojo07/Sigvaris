<?php

namespace App\Exports;

use App\Exports\CorteCajaExport;
use App\Exports\TotalVentasExport;
use App\Exports\ClienteVentasExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SheetsExport implements FromArray, WithMultipleSheets
{

    public function __construct()
    {

    }

    public function array(): array
    {
    }

    public function sheets(): array
    {
        $sheets = [
            'Corte de Caja' => new CorteCajaExport(),
            'Ventas Totales' => new TotalVentasExport(),
            'Cientes ' => new ClienteVentasExport()
        ];

        return $sheets;
    }
}