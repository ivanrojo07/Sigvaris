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
        $Ventas=Venta::where('fecha', '>=', date('Y-m-d'))
            ->where('oficina_id',1)
            ->get();
        $TotalVentas=$Ventas->count();
        $VentasIVA= $Ventas->sum('total');
        $VentasSIVA=$Ventas->sum('subtotal');
        $auxNu=[];
        $auxRe=[];
        $NumDoc=[];
        foreach ($Ventas as $Venta) {
            if ( $Venta->paciente->ventas()->count()==1) {
                array_push ($auxNu,$Venta->paciente->id);
            }else{
                array_push ($auxRe,$Venta->paciente->id);
            }
            if ( $Venta->paciente->doctor != null) {
                array_push ($NumDoc, $Venta->paciente->doctor->id);
            }

        }
        array_unique($auxNu);
        array_unique($auxRe);
        array_unique($NumDoc);
        return Venta::where('fecha', '>=', date('Y-m-d'))
            ->where('id',"<=",2)
            ->get()
            //->first()
            //->pluck('productos')
            ->flatten()
            ->map(
                
                function ($Venta,$TotalVentas,$VentasIVA,$VentasSIVA,$auxNu,$auxRe,$NumDoc) {
                return collect([
                    $TotalVentas,
                    $VentasIVA,
                    $VentasSIVA,
                    count($auxNu)+count($auxRe),
                    count($auxNu),
                    count($auxRe),
                    count($NumDoc)

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
