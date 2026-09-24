<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa los tipos de equipos (portátil, video-beam, etc.)
class TipoEquipo extends Model
{
    protected $table = 'tipo_equipo'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_t_equip'; // Clave primaria del tipo de equipo

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    // Relación con los equipos de este tipo
    public function equipos()
    {
        // El tipo de equipo tiene muchos equipos asociados por id_t_equip
        return $this->hasMany(Equipo::class, 'id_t_equip', 'id_t_equip');
    }
}
