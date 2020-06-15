<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoDamage extends Model
{
    protected $table = "productos_damage";
    protected $fillable = ["producto_id", "tipo_damage", "user_id", "descripcion"];

    public function producto(){
        return $this->belongsTo('App\Producto');
    }
}
