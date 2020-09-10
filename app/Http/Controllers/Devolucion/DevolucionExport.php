<?php

namespace App\Exports;
use DB;
use App\Devolucion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DevolucionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $QUERY = DB::table('registros')->select('id', 'venta_id','monto','cuenta','beneficiario','referencia','clave','banco')->get();
        return  $QUERY;
    }
    public function headings(): array
    {
        return [
            'Id',
            'venta_id',
            'monto',
            'cuenta',
            'beneficiario',
            'referencia',
            'clave',
            'banco',
            
        ];
    }
    public function title(): string
    {
        return 'Devoluciones';
    }
}


