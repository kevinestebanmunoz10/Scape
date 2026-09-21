<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesoVisi extends Model
{
    protected $table = 'acceso_visi';

    protected $primaryKey = 'id_acces_visi';

    public $timestamps = false;

    protected $fillable = ['f_ingreso', 'f_salida', 'Documento_visi', 'id_Estado_visi', 'Lugar'];

    public function visitante()
    {
        return $this->belongsTo(Visitante::class, 'Documento_visi', 'Docu_visi');
    }
}
