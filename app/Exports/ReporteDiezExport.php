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

class ReporteDiezExport implements FromView
    {

        public function __construct($mesesString,$doctores,$mesesSolicitados){ 
        $this->mesesString=$mesesString;
        $this->doctores=$doctores;
        $this->mesesSolicitados=$mesesSolicitados;
        // $this->numPacientesPorAnio=$numPacientesPorAnio;
        // $this->opcion=$opcion;
        // $this->total=$total;
            }
	    public function view(): View
            {
                    dd($this->doctores);
        return view('reportes.tableDiez', [
            
            'mesesString'=> $this->mesesString,
            'doctores'=>$this->doctores,
             'mesesSolicitados'=>json_decode($this->mesesSolicitados),

        ]);
    }
}


