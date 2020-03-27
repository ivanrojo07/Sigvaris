<?php

namespace App\Exports;

use App\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatosFiscalesExport implements FromCollection, WithHeadings
{
    public function __construct($fecha, $oficina_id)
    {
        $this->fecha = $fecha;
        $this->oficina_id = $oficina_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $ventas = Venta::where('requiere_factura',1);

        if($this->fecha){
            $ventas = $ventas->where('fecha', $this->fecha);
        }

        if($this->oficina_id){
            $ventas = $ventas->where('oficina_id', $this->oficina_id);
        }

        return $ventas->get()->pluck('paciente')
            ->flatten()
            ->pluck('datoFiscal')
            ->flatten()
            ->unique()
            ->map(function ($datos_fiscales) {
                return [
                    'paciente' => $datos_fiscales->paciente ? $datos_fiscales->paciente->fullname : '',
                    'tipo_persona' => $datos_fiscales->tipo_persona,
                    'nombre_o_razon_social' => $datos_fiscales->nombre_o_razon_social,
                    'regimen_fiscal' => $datos_fiscales->regimen_fiscal,
                    'homoclave' => $datos_fiscales->homoclave,
                    'correo' => $datos_fiscales->correo,
                    'rfc' => $datos_fiscales->rfc,
                    'calle' => $datos_fiscales->calle,
                    'num_ext' => $datos_fiscales->num_ext,
                    'num_int' => $datos_fiscales->num_int,
                    'colonia' => $datos_fiscales->colonia,
                    'ciudad' => $datos_fiscales->ciudad,
                    'alcaldia_o_municipio' => $datos_fiscales->alcaldia_o_municipio,
                    'estado' => $datos_fiscales->estado,
                    'codigo_postal' => $datos_fiscales->codigo_postal,
                    'porcentaje_descuento' => '',
                    'nombre_descuento' => '',
                    'uso_cfdi' => $datos_fiscales->uso_cfdi,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'PACIENTE',
            'TIPO PERSONA',
            'NOMBRE O RAZÓN SOCIAL',
            'REGIMEN FISCAL',
            'HOMOCLAVE',
            'CORREO',
            'RFC',
            'CALLE',
            'NUM. EXT',
            'NUM. INT',
            'COLONIA',
            'CIUDAD',
            'ALCALDIA O MUNICIPIO',
            'ESTADO',
            'CODIGO POSTAL',
            'PORCENTAJE DESCUENTO',
            'NOMBRE DESCUENTO',
            'USO CFDI'
        ];
    }
}
