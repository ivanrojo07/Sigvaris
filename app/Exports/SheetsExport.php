<?php

namespace App\Exports;

use App\Exports\CorteCajaExport;
use App\Exports\TotalVentasExport;
use App\Exports\ClienteVentasExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class SheetsExport implements  WithMultipleSheets, SkipsUnknownSheets
{

    

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