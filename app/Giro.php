<?php

namespace App;

use App\DatosGeneralesProveedor;
use Illuminate\Database\Eloquent\Model;

class Giro extends Model
{
    //
    protected $table = 'giro';
    protected $fillable=['id','nombre','etiqueta'];
    protected $hidden=[ 'created_at', 'updated_at','deleted_at'];

    public function datosGen(){
    	return $this->hasOne(DatosGeneralesProveedor::class);
    }
}
