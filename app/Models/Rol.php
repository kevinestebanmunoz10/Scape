<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa los roles disponibles en el sistema
class Rol extends Model
{
    protected $table = 'rol'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_rol'; // Clave primaria del rol

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    // Relación con los usuarios que tienen este rol
    public function usuarios()
    {
        // El rol tiene muchos usuarios asociados por id_rol
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }
}
