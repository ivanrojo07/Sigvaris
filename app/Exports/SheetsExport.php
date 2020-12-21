<?php

namespace App\Exports;

use App\Exports\CorteCajaExport;
use App\Exports\TotalVentasExport;
use App\Exports\ClienteVentasExport;
use App\Exports\DevolucionPExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class SheetsExport implements  WithMultipleSheets
{

    

    public function sheets(): array
    {
        $sheets = [
            'Corte de Caja' => new CorteCajaExport(),
            'Ventas Totales' => new TotalVentasExport(),
            'Cientes ' => new ClienteVentasExport(),
            'Devoluciones ' => new DevolucionPExport(),
        ];

        return $sheets;
    }
    
}