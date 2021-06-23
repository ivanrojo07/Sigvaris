<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retex extends Model
{
    //
    protected $table = 'retexes';
    protected $fillable = ['id','descuento'];
    protected $timestamps = true;



    public function ventas(){
        return $this->belongsToMany('App\Venta', 'retex_ventas','venta_id');
    }
}
