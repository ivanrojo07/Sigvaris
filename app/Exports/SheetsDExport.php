<?php

namespace App\Exports;

use App\Exports\DevolucionExport;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class SheetsExport implements  WithMultipleSheets
{

    

    public function sheets(): array
    {
        $sheets = [
            'Devoluciones' => new DevolucionExport()
        ];

        return $sheets;
    }
    
}