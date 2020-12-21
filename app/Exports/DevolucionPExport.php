<?php

namespace App\Exports;

use App\Venta;
use App\Factura;
use App\Devolucion;
use App\HistorialCambioVenta;
use Carbon\Carbon;
use App\Descuento;
use App\Promocion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class DevolucionPExport implements FromCollection, WithHeadings,WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $now = Carbon::now('America/Mexico_City');

        return Devolucion::where('created_at', '>=',$now->format('Y-m-d'))
            ->get()
            //->pluck('productos')
            ->flatten()
            ->map(
                
                // function ($Venta) {
                //     $SkuRe="";
                //     $contador = $Venta->productos()->pluck('cantidad');
                //     $aux = 0;
                //     foreach ($Venta->productos as $producto ) {
                       
                //         $SkuRe.=$producto->sku." - ".$contador[$aux]."| ";
                //         $aux++;
                        
                //     }

                 $Devoluciones = DB::table('devoluciones')->where('created_at','>=',$now->format('Y-m-d'))->get();

                return collect([
                    $Devoluciones->id,
                    date('Y-m-d'),                    
                                

                ]);
            });
    }

    public function headings(): array
    {
        return [
            'Nota de remisión',
            'Fecha de compra'

        ];
    }
    public function title(): string
    {
        return 'Devoluciones';
    }
}
