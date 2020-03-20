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
        $index=0;
        return Venta::where('fecha', '>=', date('Y-m-d'))
            ->where('oficina_id',2)
            ->get()
            //->pluck('productos')
            ->flatten()
            ->map(
                
                function ($Venta,$index) {

                //dd($Venta->productos()->pluck('cantidad')->sum());
                $index++;
                return collect([
                    date('Y-m-d'),
                    Carbon::parse($Venta->fecha)->format('h:i:s'),
                    $Venta->id,
                    $index,
                    $Venta->paciente->nombre." ".$Venta->paciente->paterno." ".$Venta->paciente->materno,
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->nombre : "",
                    $Venta->paciente->ventas()->count() == 1?  "1":"2"

                ]);
            });

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
