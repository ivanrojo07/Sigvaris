<?php

namespace App\Services\Productos;

use App\Imports\ProductImport;
use App\Producto;
use ErrorException;
use Excel;
use UxWeb\SweetAlert\SweetAlert as Alert;

class StoreExcelProductosService
{

    public function make($file)
    {
        // OBTENEMOS LOS DATOS DEL EXCEL
        $data = Excel::toArray(new ProductImport, $file);
        $Precios = $data[1];
        $data = $data[0];
        unset($Precios[0]);
        unset($data[0]);
        
        if (!count($data)) {
            return redirect()->back()->withErrors(['error', 'Error al subir el archivo.']);
        }

        try {
            // OBTENEMOS LOS PRODUCTOS DEL EXCEL
            foreach ($data as $row) {
               // $indice = $this->buscarPreciosenExcel($Precios, count($Precios), $row[0]);

                //if ($indice != -1) {
                    //dd($indice);
                if (isset($row[7])) {
                    $arr[] = [
                        'sku' => $row[0],
                        'descripcion' => $row[1],
                        'line' => $row[2],
                        'upc' => $row[3],
                        'swiss_id' => $row[4],
                        //'precio_distribuidor' => number_format($row->distribuidor, 2, '.', ''),
                        'precio_publico' => number_format($row[5], 2, '.', ''),
                        'precio_publico_iva' => number_format($row[6], 2, '.', ''),
                        'stock' => $row[7],
                        'oficina_id' => session('oficina')
                    ];
                }else{
                    $arr[] = [
                        'sku' => $row[0],
                        'descripcion' => $row[1],
                        'line' => $row[2],
                        'upc' => $row[3],
                        'swiss_id' => $row[4],
                        //'precio_distribuidor' => number_format($row->distribuidor, 2, '.', ''),
                        'precio_publico' => number_format($row[5], 2, '.', ''),
                        'precio_publico_iva' => number_format($row[6], 2, '.', ''),
                        'oficina_id' => session('oficina')
                    ];
                }
                    
                //}
            }
        } catch (\ErrorException $ee) {
            return redirect()->back()->withErrors(['status', 'error_create']);
        }

        if (empty($arr)) {
            return redirect()->back()->withErrors(['error', 'Error al subir el archivo.']);
        }

        foreach ($arr as $producto) {
            Producto::updateOrCreate(['sku'=>$producto['sku'],'oficina_id' => session('oficina')], $producto);
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
