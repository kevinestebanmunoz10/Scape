<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends Model
{
    protected $table = 'tipo_equipo';

    protected $primaryKey = 'id_t_equip';

    public $timestamps = false;

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'id_t_equip', 'id_t_equip');
    }
}
