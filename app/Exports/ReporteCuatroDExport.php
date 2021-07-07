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

class ReporteCuatroDExport implements FromView
    {

        public function __construct($anio_ini,$anio_fin,$meses,$request){ 
        $this->request=$request;
        $this->anio_ini=$anio_ini;
        $this->anio_fin=$anio_fin;
        $this->meses=$meses;
        // $this->total=$total;
            }
	    public function view(): View
            {
                $final= $this->anio_fin;
        return view('reportes.tableCuatrod', [
           
            'anioInicial'=>$this->anio_ini,
            'anioFinal '=>$final,
            'meses'=>$this->meses,
            'request'=> $this->request
        ]);
    }
}


