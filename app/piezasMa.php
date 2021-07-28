<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class piezasMa extends Model
{
    protected $table = 'piezas_mas';
    protected $fillable = ['id','producto_id','SKU','precio'];
    public $timestamps = true;
}
