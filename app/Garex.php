<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Garex extends Model
{
    //
    protected $table = 'garexes';
    protected $fillable = ['id','precio_publico_iva'];
    public $timestamps = true;



    public function ventas(){
        return $this->belongsToMany('App\Venta', 'garex_ventas')->withPivot('venta_id');
    }
}
