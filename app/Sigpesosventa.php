<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sigpesosventa extends Model
{
    //

    protected $table = 'sigpesosventa';
    public $fillable = ['id','venta_id', 'monto', 'folio' ,'folio_id','paciente_id','tipo','usado'];
    public $timestamps = true;


    public function venta()
    {
        return $this->belongsTo('App\Venta', 'venta_id');
    }
    public function folio(){
        return $this->belongsTo('App\Folio', 'folio_id');
    }
}
