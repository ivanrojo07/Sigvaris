<?php

namespace App\Services\Pacientes;

use App\Imports\ProductImport;
use App\Paciente;
use ErrorException;
use Excel;
use UxWeb\SweetAlert\SweetAlert as Alert;
use Carbon\Carbon;
use App\Doctor;
use App\Hospital;
use App\Venta;
use App\Factura;
use App\Producto;

class StoreExcelPacientesService
{

    public function make($file)
    {
        // OBTENEMOS LOS DATOS DEL EXCEL
        $data = Excel::toArray(new ProductImport, $file);
        $data=$data[0];
        //dd($data);

        foreach ($data as $row) {
            if (isset($row[2])) {
            if ($row[5]!="-") {
                $Doctor= array(
                    'nombre' => $row[5],
                    'apellidopaterno' => $row[6],
                    'apellidomaterno' => $row[7],
                    //'celular' => $row[1],
                    //'mail' => $row[2],
                    //'nacimiento' => $row[2],
                    'activo' => 1,
                    //'deleted_at' => $row[2,]
                );
                $D=Doctor::updateOrCreate($Doctor, $Doctor);
                $ID_Doctor=$D['id'];
            }
            
            
            

                # code...
            
            if (isset($ID_Doctor)) {
                $Paciente= array(
                'nombre' => $row[2],
                'paterno' => $row[3],
                'materno' => $row[4],
                
                //'nacimiento' => $row[3],
                'rfc' => $row[19],
                'celular' => $row[29],
                'telefono' => $row[30],
                'mail' => $row[28],
                'doctor_id' => $ID_Doctor,
                'nivel_id' => 1,
                'oficina_id' => 2,
                //'homoclave' => $row[11],
                //'created_at' => date('Y-m-d h:m:s'),
                //'updated_at' => date('Y-m-d h:m:s'),
            );
            }else{
                $Paciente= array(
                'nombre' => $row[2],
                'paterno' => $row[3],
                'materno' => $row[4],
                //'nacimiento' => $row[3],
                'rfc' => $row[19],
                'celular' => $row[29],
                'telefono' => $row[30],
                'mail' => $row[28],
                //'id_doctor' => $ID_Doctor,
                'nivel_id' => 1,
                'oficina_id' => 2,
                //'homoclave' => $row[11],
                //'created_at' => date('Y-m-d h:m:s'),
                //'updated_at' => date('Y-m-d h:m:s'),
            );
            }
            
            $P=Paciente::updateOrCreate((['nombre' => $row[2],'materno' => $row[4],'paterno' => $row[3]]),$Paciente);
            $Precio_public=0;
            $Precio_public_iva=0;
            for ($i=0; $i <8 ; $i++) { 
                if ( Producto::where('sku',$row[11+$i])->exists()) {
                    $Precio_public+=Producto::where('sku',$row[11+$i])->value('precio_publico');
                    $Precio_public_iva +=Producto::where('sku',$row[11+$i])->value('precio_publico_iva');
                }
            }
            if ($Precio_public>0) {
                    $Venta= array(
                        'paciente_id'=>$P['id'],
                        'fecha'=> Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1])),
                        'subtotal' => $Precio_public,
                        'total' => $Precio_public_iva,
                        'oficina_id' => 2,
                        'empleado_id' => 1,
                        'tipoPago' =>0
                    );
                    $V=Venta::updateOrCreate(['paciente_id'=>$P['id'],'subtotal' => $Precio_public,'total' => $Precio_public_iva,'empleado_id' => 1],$Venta);
                  
                     for ($i=0; $i < 8 ; $i++) {
                     if ( Producto::where('sku',$row[11+$i])->exists()) { 
                        $V->productos()->attach(
                            Producto::where('sku',$row[11+$i])->value('id'),array('cantidad'=>1,
                            'precio'=>Producto::where('sku',$row[11+$i])->value('precio_publico'), 
                            'created_at' => date('Y-m-d h:m:s'), 
                            'updated_at' => date('Y-m-d h:m:s')
                        ));
                        }
                    }

                    if ($row[19]!="-") {
                        $Factura= array(
                        'venta_id'=>$V['id'],
                        'nombre'=>$row[20],
                        //'fisica'=>$row[],
                        'rfc'=>$row[30],
                        //'regimen_fiscal'=>$row[],
                        //'homoclave'=>$row[],
                        'correo'=>$row[28],
                        'calle'=>$row[21],
                        'num_ext'=>$row[22],
                        'num_int'=>$row[23],
                        'colonia'=>$row[24],
                        'cp' => $row[25],
                        //'ciudad'=>$row[],
                        'municipio'=>$row[26],
                        'estado'=>$row[27],
                        'created_at' => date('Y-m-d h:m:s'), 
                        'updated_at' => date('Y-m-d h:m:s')
                        );
                        Factura::updateOrCreate($Factura,$Factura);
                    }
                    
                
                }
            

            
        }
        /*$data = $data[0];
        
        if (!count($data)) {
            return redirect()->back()->withErrors(['error', 'Error al subir el archivo.']);
        }

        try {
            // OBTENEMOS LOS PRODUCTOS DEL EXCEL
            foreach ($data as $row) {
                $indice = $this->buscarPreciosenExcel($Precios, count($Precios), $row[0]);
                if ($indice != -1) {
                    $arr[] = [
                        'nombre' => $row[2],
                        'materno' => $row[4],
                        'paterno' => $row[3],
                        //'nacimiento' => $row[3], 
                        //'rfc' => number_format((float)$row[30], 2, '.', ''),
                        'rfc' => $row[30],
                        'celular' => $row[41],
                        'telefono' => $row[40],
                        'mail' => $row[39],
                        //'otro_doctor' => $row[8],
                        'nivel_id' => "1",
                        'oficina_id' => $row[10],
                        'homoclave' => $row[11],
                        'created_at' => date('Y-m-d h:m:s'),
                        'updated_at' => date('Y-m-d h:m:s'),
                    ];
                }
            }
        } catch (\ErrorException $ee) {
            return redirect()->back()->withErrors(['status', 'error_create']);
        }

        if (empty($arr)) {
            return redirect()->back()->withErrors(['error', 'Error al subir el archivo.']);
        }

        foreach ($arr as $producto) {
            Producto::updateOrCreate($producto, $producto);
        }
        */
    }
    }

    public function buscarPreciosenExcel($arreglo, $tamaño, $dato)
    {
        $centro = 0;
        $inf = 0;
        $sup = $tamaño - 1;
        while ($inf <= $sup) {
            $centro = (int) (($sup - $inf) / 2) + $inf;
            if ($arreglo[$centro][0] == $dato)       return $centro;
            else if ($dato < $arreglo[$centro][0]) $sup = $centro - 1;
            else $inf = $centro + 1;
        }
        return -1;
    }
}
