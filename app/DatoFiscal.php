<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatoFiscal extends Model
{
    protected $table = "datos_fiscales";
    protected $fillable = [
        'tipo_persona',
        'paciente_id',
        'nombre_o_razon_social',
        'regimen_fiscal',
        'homoclave',
        'correo',
        'rfc',
        'calle',
        'num_ext',
        'num_int',
        'colonia',
        'ciudad',
        'alcaldia_o_municipio',
        'estado',
        'codigo_postal',
        'uso_cfdi'
    ];

    public function paciente(){
        return $this->belongsTo('App\Paciente');
    }
}
