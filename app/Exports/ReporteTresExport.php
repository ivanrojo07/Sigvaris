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

class ReporteTresExport implements FromView
    {

        public function __construct($request){ 
         $this->request = $request;
        
            }
	    public function view(): View
            {
                //1arregloFechasConVentas
                //2arregloTotalPacientesConUnProducto
                //3arregloTotalPacientesConMasDeUnProducto
                $arregloFechasConVentas=[];
                $arregloTotalPacientesConUnProducto=[];
                $arregloTotalPacientesConMasDeUnProducto=[];
                $contador=0;
                $auxiliar= $this->request;
                $auxiliar = json_decode($auxiliar);
                // dd();
                foreach ($auxiliar  as &$arreglo) {

                    if ($contador==0) {
                       $arregloFechasConVentas = $arreglo;
                        $contador++;
                    }else if($contador==1){
                        $arregloTotalPacientesConUnProducto = $arreglo;
                        $contador++;
                    }else if($contador==2){
                        $arregloTotalPacientesConMasDeUnProducto = $arreglo;
                        $contador++;
                    }

                    // $arregloFechasConVentas = $arreglo;
                }

                // dd($arregloFechasConVentas,"fehca",$arregloTotalPacientesConUnProducto,'Paciente',$arregloTotalPacientesConMasDeUnProducto,'Mas de uno');
            $ventas = $this->request  ;
        return view('exports.reporteTres', [
            // 'ventas' =>$ventas
            'arregloFechasConVentas'=>$arregloFechasConVentas,
            'arregloTotalPacientesConUnProducto'=>$arregloTotalPacientesConUnProducto,
            'arregloTotalPacientesConMasDeUnProducto'=>$arregloTotalPacientesConMasDeUnProducto,
        ]);
    }
}

