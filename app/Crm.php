<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crm extends Model
{
    protected $table = 'crms';
    public $timestamps = true;
    
    protected $fillable = [
    	'id',
    	'paciente_id',
    	'observaciones',
        'acuerdos',
        'comentarios',
        'fecha_aviso',
        'fecha_contacto',
        'forma_contacto',
        'hora',
        'estado_id',
        'oficina_id',
        'contesto_id',
        'comentarios_uso'
    ];

    public function paciente(){
        return $this->belongsTo(Paciente::class,'paciente_id');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
