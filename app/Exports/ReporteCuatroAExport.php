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

class ReporteCuatroAExport implements FromView
    {

        public function __construct($request){ 
         $this->request = $request;
        
            }
	    public function view(): View
            {
                //1arregloFechasConVentas
                //2arregloTotalPacientesConUnProducto
                //3arregloTotalPacientesConMasDeUnProducto
                $pacientesConCompra=[];
                $sumadePrendas =[];
                $Porcentaje =[];
                // $arregloTotalPacientesConMasDeUnProducto=[];
                $contador=0;
                $auxiliar = $this->request;
                $auxiliar = json_decode($auxiliar);
                $suma= null;
                // dd(count($auxiliar),$auxiliar);
                // dd($auxiliar[0]->ventas[0]->productos[0]->pivot->precio ,$this->request);
                // for ($i=0; $i < (count($auxiliar)) ; $i++) { 
                //     // dd(->productos[$i]);
                //     $array = array($auxiliar);
                //     $array2 = json_decode( json_encode($array), true );
                //     dd(array($auxiliar[0]->ventas[0]->productos[0]->pivot) );
                //         //     $contador_vntas = count($auxiliar[$i]->ventas[$i]);
                //         // // if (($auxiliar[$i]->ventas[$i]->productos[$i]) !== null) {
                //         //      for ($i=0; $i < $contador_vntas ; $i++) { 

                //         //        array_push($sumadePrendas, $auxiliar[$i]->ventas[$i]->productos[$i]->pivot->cantidad);
                           
                //         // }
                //         // }else{
                //         //      array_push($sumadePrendas, $auxiliar[$i]->ventas[$i]->productos[$i]->pivot->cantidad);
                //         // }

                                         
                // }
               
                // dd($sumadePrendas);

                // dd($arregloFechasConVentas,"fehca",$arregloTotalPacientesConUnProducto,'Paciente',$arregloTotalPacientesConMasDeUnProducto,'Mas de uno');
            $ventas = $this->request  ;
        return view('exports.reporteCuatroA', [
            // 'ventas' =>$ventas
            'pacientesConCompra'=>$auxiliar,
        ]);
    }
}


