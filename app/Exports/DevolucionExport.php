<?php

namespace App\Exports;
use DB;
use App\Devolucion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;



class DevolucionExport implements FromView

{
    
    public function view(): View
    {
         $consulta = Devolucion::all();
        return view('exports.devolucion', [
            'devolucion' =>$consulta
        ]);
    }
}
