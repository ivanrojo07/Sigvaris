<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones';
    public $timestamps = true;
    
    protected $fillable = [
        'id',
        'venta_id',
        'monto',
        'cuenta',
        'beneficiario',
        'referencia',
        'clave',
        'banco'
    ];

    public function venta(){
        return $this->belongsTo('App\Venta', 'venta_id');
    }

}
