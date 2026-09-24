<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa las marcas de los equipos
class Marca extends Model
{
    protected $table = 'marca'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_marca'; // Clave primaria de la marca

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = ['marca']; // Campo permitido para asignación masiva

    // Relación con los equipos de esta marca
    public function equipos()
    {
        // La marca tiene muchos equipos asociados por id_Marca
        return $this->hasMany(Equipo::class, 'id_Marca', 'id_marca');
    }
}
