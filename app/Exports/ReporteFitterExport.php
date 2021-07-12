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

class ReporteFitterExport implements FromView
    {

        public function __construct($ventasMes,$datosVentasMes){ 
        $this->ventasMes=$ventasMes;
        $this->datosVentasMes=$datosVentasMes;
        // $this->doctores=$doctores;
        // $this->mesesSolicitados=$mesesSolicitados;
        // $this->numPacientesPorAnio=$numPacientesPorAnio;
        // $this->opcion=$opcion;
        // $this->total=$total;
            }
	    public function view(): View
            {       
                // dd($this->datosVentasMes,'dentro de ',$this->ventasMes);
                $aux = json_decode($this->ventasMes);
               // dd( var_dump($aux),$aux->{'0'},$aux->{'0'}->{'0'}->{'montoVenta'});
                    // dd($aux);
                  // $doctores = Doctor::get();
                    // dd($this->doctores);
        return view('reportes.tableFitter', [
            
            'datosVentasMes'=> (array)($aux),
            // 'doctores'=>$doctores,
            //  'mesesSolicitados'=>json_decode($this->mesesSolicitados),

        ]);
    }
}


