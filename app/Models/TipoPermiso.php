<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa los tipos de permiso de salida (médico, calamidad, etc.)
class TipoPermiso extends Model
{
    protected $table = 'tipo_permiso'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_tipo_permiso'; // Clave primaria del tipo de permiso

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = ['tipo']; // Campo permitido para asignación masiva
}
