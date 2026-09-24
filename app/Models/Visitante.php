<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa a las personas visitantes de la institución
class Visitante extends Model
{
    protected $table = 'visitantes'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'Docu_visi'; // Clave primaria: documento del visitante

    public $incrementing = false; // Indica que la clave primaria no es autoincremental

    protected $keyType = 'string'; // Tipo de la clave primaria: cadena de texto

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = ['Docu_visi', 'Nom_visi', 'tel_visi', 'correo_visi']; // Campos permitidos para asignación masiva

    // Relación con los accesos registrados del visitante
    public function accesos()
    {
        // El visitante tiene muchos accesos asociados por su documento
        return $this->hasMany(AccesoVisi::class, 'Documento_visi', 'Docu_visi');
    }
}
