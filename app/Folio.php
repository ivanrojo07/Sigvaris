<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    //

    protected $table = 'folios';
    public $fillable = ['id','rango_superior', 'rango_inferior', 'descripcion','monto'];
    public $timestamps = true;

    public function sigpesosventa(){
        return $this->hasMany('App\Sigpesosventa');
    }
}
