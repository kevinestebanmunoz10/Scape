<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa el registro de accesos de los estudiantes
class AccesoEstu extends Model
{
    protected $table = 'acceso_estu'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_acces_estu'; // Clave primaria de la tabla acceso_estu

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = ['f_ingreso', 'f_salida', 'Documento_estu', 'id_autorizacion']; // Campos permitidos para asignación masiva

    // Relación que indica a qué estudiante pertenece este acceso
    public function estudiante()
    {
        // El acceso pertenece a un estudiante mediante la clave foránea Documento_estu
        return $this->belongsTo(User::class, 'Documento_estu', 'Documento');
    }
}
