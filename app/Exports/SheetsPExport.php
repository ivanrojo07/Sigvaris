<?php

namespace App\Exports;

use App\Exports\CorteCajaPExport;
use App\Exports\TotalVentasPExport;
use App\Exports\ClienteVentasPExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class SheetsPExport implements  WithMultipleSheets
{

    

    public function sheets(): array
    {
        $sheets = [
            'Corte de Caja ' => new CorteCajaPExport(),
            'Ventas Totales' => new TotalVentasPExport(),
            'Cientes ' => new ClienteVentasPExport(),
             'Devoluciones ' => new DevolucionPExport(),
            'Devoluciones Sigpesos ' => new DevolucionSExport(),
            'Garext ' => new garextPExport(),
        ];

        return $sheets;
    }
    
}