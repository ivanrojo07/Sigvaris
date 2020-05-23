<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    //

    protected $table = 'folios';
    protected $fillable = ['id','rango_superior', 'rango_inferior', 'descripcion'];
    public $timestamps = true;

    public function sigpesosventa(){
        return $this->hasMany('App\Sigpesosventa');
    }
}
