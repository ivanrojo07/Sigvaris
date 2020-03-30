<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PacientesExpedientes extends Model
{
    //
    protected $table='pacientes_expediente';
    protected $fillable=['id','paciente_id','receta','aviso_privacidad','identificacion','identificacion2','inapam'];
    protected $hidden=['created_at','updated_at'];

    public function paciente()
    {
    	return $this->belongsTo('App\Paciente','paciente_id');
    }
}
