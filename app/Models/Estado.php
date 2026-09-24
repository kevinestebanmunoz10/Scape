<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa los estados posibles de un usuario (activo, inactivo)
class Estado extends Model
{
    protected $table = 'estado'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_estado'; // Clave primaria del estado

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    // Relación con los usuarios que tienen este estado
    public function usuarios()
    {
        // El estado tiene muchos usuarios asociados por id_Estado
        return $this->hasMany(User::class, 'id_Estado', 'id_estado');
    }
}
