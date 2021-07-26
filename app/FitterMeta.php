<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FitterMeta extends Model
{
    protected $table = "fitter_metas";
    protected $fillable = [
        "monto_venta", 
        "num_pacientes_recompra",
        "numero_recompras",
        "fecha_inicio",
        "empleado_id",
        'ventas_obsoletos',
        'calcetin',
        'leggings',
        'muslo',
        'media',
        'panti',
        'tobimedias',
        'pz_mayor',
        'pz_menor',
    ];

    public function empleado(){
        return $this->hasOne('App\Empleado');
    }
}
