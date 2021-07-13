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
            {       $mesesString_ = array(
            "01" => "Enero",
            "02" => "Febrero",
            "03" => "Marzo",
            "04" => "Abril",
            "05" => "Mayo",
            "06" => "Junio",
            "07" => "Julio",
            "08" => "Agosto",
            "09" => "Septiembre",
            "10" => "Octubre",
            "11" => "Noviembre",
            "12" => "Diciembre"
        );
                  $doctores = Doctor::get();
                    // dd(json_decode($this->mesesSolicitados));
        return view('reportes.tableDiez', [
            
            'mesesString'=> $mesesString_,
            'doctores'=>$doctores,
             'mesesSolicitados'=>json_decode($this->mesesSolicitados),

        ]);
    }
}


