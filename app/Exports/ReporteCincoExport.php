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

class ReporteCincoExport implements FromView
    {

        public function __construct($pacientes_,$anios,$meses_,$numPacientesPorAnio,$opcion){ 
        $this->pacientes_=$pacientes_;
        $this->anios=$anios;
        $this->meses_=$meses_;
        $this->numPacientesPorAnio=$numPacientesPorAnio;
        $this->opcion=$opcion;
        // $this->total=$total;
            }
	    public function view(): View
            {
                    // dd($this->anios,$this->meses_,$this->numPacientesPorAnio);
        return view('reportes.tableCinco', [
            
            'pacientes'=> $this->pacientes_,
            'anios'=>json_decode($this->anios),
             'meses'=>json_decode($this->meses_),
            'numPacientesPorAnio'=>json_decode($this->numPacientesPorAnio),
            'opcion'=> $this->opcion
        ]);
    }
}


