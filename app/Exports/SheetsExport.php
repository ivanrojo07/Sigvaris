<?php

namespace App\Exports;

use App\Exports\CorteCajaExport;
use App\Exports\TotalVentasExport;
use App\Exports\ClienteVentasExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetsExport implements  WithMultipleSheets, WithTitle
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
    public function title(): array
    {
        return  [
            'Corte de Caja' ,
            'Ventas Totales',
            'Cientes '
        ];
    }
}