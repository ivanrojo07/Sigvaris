<?php

namespace App\Exports;

use App\Venta;
use App\Factura;
use App\HistorialCambioVenta;
use Carbon\Carbon;
use App\Descuento;
use App\Promocion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TotalVentasExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect(['',
        '',
        '',
        '',
        '',
        '',
        ''
        ]);

    }

    public function headings(): array
    {
        return [
            'Total de ventas',
            'Ventas Sin iva',
            'Ventas Con iva',
            'Numero total de pacientes',
            'Numero total de pacientes nuevos',
            'Numero total de pacientes recurrentes',
            'Numero total de doctores recomendaron'


        ];
    }
}
