<?php

namespace App\Exports;
use App\Venta;
use App\Oficina;
use App\Paciente;
use App\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReporteDosExport implements FromCollection,  WithHeadings
{
	public function __construct($fechaInicial,$fechaFinal,$ventas)
    {
        
         $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
         $this->ventas = $ventas;
        
    }


    public function ventas($fechaInicial,$fechaFinal){
    		$ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
                ->where('fecha', '<=', $fechaFinal)
                ->withCount('productos');
        $ventas = $ventas->get();

        return $ventas;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $ventas = $this->ventas($this->fechaInicial,$this->fechaFinal);
        // 
       
        function ($Venta,$index) {
        return collect(
        		return [
        		'fecha'=>$this->ventas->fecha,
        		'nombre'=>$this->ventas->paciente->nombre,
        		'paterno'=>$this->ventas->paciente->paterno,
        		'materno'=>$this->ventas->paciente->materno,
        		'cantidad_productos'=>$this->ventas->cantidad_productos
        			 ]
        		
        );
    });
        
    }

    public function headings(): array
    {
        return [
            'fecha',
            'nombre',
            'paterno',
            'materno',
            'NÚM. prendas'
        ];
    }
}
