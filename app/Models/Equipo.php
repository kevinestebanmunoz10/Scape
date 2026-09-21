<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipo';

    protected $primaryKey = 'serial_equi';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['serial_equi', 'id_t_equip', 'id_Marca', 'Color', 'imagen'];

    public function tipo()
    {
        return $this->belongsTo(TipoEquipo::class, 'id_t_equip', 'id_t_equip');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_Marca', 'id_marca');
    }

    public function prestamos()
    {
        return $this->hasMany(PrestamoEquipo::class, 'serial_equi', 'serial_equi');
    }
}
