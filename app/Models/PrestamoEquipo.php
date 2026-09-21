<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrestamoEquipo extends Model
{
    protected $table = 'prestamo_equipo';

    protected $primaryKey = 'id_Prestamo_equi';

    public $timestamps = false;

    protected $fillable = ['f_Prestamo', 'f_devolucion', 'motivo', 'Documento_pres', 'serial_equi', 'id_estado'];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'serial_equi', 'serial_equi');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'Documento_pres', 'Documento');
    }
}
