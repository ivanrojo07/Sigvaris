<?php

namespace App\Exports;

use App\Exports\CorteCajaExport;
use App\Exports\TotalVentasExport;
use App\Exports\ClienteVentasExport;
use App\Exports\DevolucionPExport;
use App\Exports\DevolucionSExport;
use App\Exports\GarextPExport;

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
            'Devoluciones Sigpesos ' => new DevolucionSExport(),
            'Garext ' => new GarextPExport(),
        ];

        return $sheets;
    }
    
}