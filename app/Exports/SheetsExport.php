<?php

namespace App\Exports;

use App\Exports\CorteCajaExport;
use App\Exports\TotalVentasExport;
use App\Exports\ClienteVentasExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SheetsExport implements FromArray, WithMultipleSheets
{
    protected $sheets;

    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    public function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        $sheets = [
            new CorteCajaExport(),
            new TotalVentasExport(),
            new ClienteVentasExport()
        ];

        return $sheets;
    }
}