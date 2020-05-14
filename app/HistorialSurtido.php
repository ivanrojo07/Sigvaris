<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialSurtido extends Model
{
    //
    protected $table = "historial_surtido";
    protected $fillable = ['producto_id','numero'];
    protected $hidden=['created_at','updated_at'];

    public function producto()
    {
        return $this->belongsTo('App\Producto', 'producto_id');
    }
}
