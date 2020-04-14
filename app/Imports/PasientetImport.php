<?php

namespace App\Imports;

use App\Paciente;
use App\Doctor;
use App\Hospital;
use App\Venta;
use App\Factura;
use App\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class PacienteImport implements ToModel, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (count($row[5])>0) {
            $Doctor= new Doctor([
                'nombre' => $row[5],
                'apellidomaterno' => $row[6],
                'apellidopaterno' => $row[7],
                //'celular' => $row[1],
                //'mail' => $row[2],
                //'nacimiento' => $row[2],
                //'activo' => $row[2],
                //'deleted_at' => $row[2,]
            ]);
        }
        Doctor::updateOrCreate($Doctor, $Doctor);
        
        if (count($row[9])>0) {
            $Hospital= new Hospital([
                'nombre' => $row[9],
                'etiqueta' => $row[9][0],
            ]);
        }
        Hospital::updateOrCreate($Hospital,$Hospital);

        $Pasiente= new Paciente([
            'nombre' => $row[0],
            'materno' => $row[1],
            'paterno' => $row[2],
            'nacimiento' => $row[3],
            'rfc' => number_format((float)$row[5], 2, '.', ''),
            'celular' => number_format((float)$row[6], 2, '.', ''),
            'telefono' => $row[6],
            'mail' => $row[7],
            'otro_doctor' => $row[8],
            'nivel_id' => $row[9],
            'oficina_id' => $row[10],
            'homoclave' => $row[11],
            'created_at' => date('Y-m-d h:m:s'),
            'updated_at' => date('Y-m-d h:m:s'),
        ]);
        Paciente::updateOrCreate((['nombre' => $row[0],'materno' => $row[1],'paterno' => $row[2]]),$Paciente);
        for ($i=0; $i < 16 ; $i++) { 
            if (isset($row[13+$i]) ) {
                $Venta= new Venta([
                    'id_pasiente'=>$Paciente['id'],
                    'fecha'=> date('Y-m-d h:m:s'),
                    'subtotal' => Producto::where('sku',$row[13+$i])->value('precio_publico'),
                    'total' => Producto::where('sku',$row[13+$i])->value('precio_publico_iva'),
                    'oficina_id' => 1,
                    'empleado_id' => 1,

                ]);
                Venta::updateOrCreate($Venta,$Venta);


                $Venta->producto()->attach(Producto::where('sku',$row[13+$i])->value('id'),['cantidad'=>1,'precio'=>Producto::where('sku',$row[13+$i])->value('precio_publico'), 'created_at' => date('Y-m-d h:m:s'), 'updated_at' => date('Y-m-d h:m:s')]);
                $Factura= new Factura([
                    'venta_id'=>$Venta['id']
                ]);
            }
        }

    }
}
