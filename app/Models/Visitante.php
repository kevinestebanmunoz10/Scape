<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    protected $table = 'visitantes';

    protected $primaryKey = 'Docu_visi';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['Docu_visi', 'Nom_visi', 'tel_visi', 'correo_visi'];

    public function accesos()
    {
        return $this->hasMany(AccesoVisi::class, 'Documento_visi', 'Docu_visi');
    }
}
