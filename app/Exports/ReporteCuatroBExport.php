<?php

namespace App\Exports;
use App\Venta;
use App\Oficina;
use App\Paciente;
use App\Producto;
use App\Empleado;
use App\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReporteCuatroBExport implements FromView
    {

        public function __construct($request,$prendas){ 
         $this->request = $request;
        $this->prendas = $prendas;
            }
	    public function view(): View
            {
                
            // $ventas = $this->request  ;
        return view('reportes.tableCuatrob', [
            // 'ventas' =>$ventas
            'skusConVentas'=>$this->request,
            'totalPrendasVendidas'=>$this->prendas
        ]);
    }
}


