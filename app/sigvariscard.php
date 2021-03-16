<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sigvariscard extends Model
{
    //
    protected $table='sigvariscards';

    protected $fillable = [
        'id',
        'paciente_id',
        'folio',
        'tipo',
        'venta_id'
    ];
    public $timestamps = false;

    public function paciente(){
        return $this->belongsTo('App\Paciente');
    }
}
