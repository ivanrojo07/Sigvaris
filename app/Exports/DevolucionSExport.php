<?php

namespace App\Exports;

use App\Venta;
use App\Factura;
use App\Devolucion;
use Illuminate\Database\Eloquent\Model\Devolución;
use App\HistorialCambioVenta;
use Carbon\Carbon;
use App\Descuento;
use App\Promocion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class DevolucionSExport implements FromCollection, WithHeadings,WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
   
    public function collection()
    {
        $now = Carbon::today('America/Mexico_City');

        

     return HistorialCambioVenta::where('created_at', '>=',$now->format('Y-m-d'))->where('tipo_cambio','DEVOLUCION')
            ->get()
            //->pluck('productos')
             ->flatten()
            ->map(
                
                function ($HistorialCambioVenta) {

                  // $Devoluciones = Devolucion::where('created_at','>=',$now->format('Y-m-d'))->get();
                  // dd($Devoluciones->id);

                return collect([
                    $HistorialCambioVenta->venta_id,
                    date('Y-m-d h:i:s'),
                    $HistorialCambioVenta->observaciones,                 
                    

                ]);
            });
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Fecha de compra',
            'monto'

        ];
    }
    public function title(): string
    {
        return 'Devoluciones saldoafavor';
    }
}
