<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa el registro de accesos de los usuarios (docentes/administrativos)
class AccesoUsu extends Model
{
    protected $table = 'acceso_usu'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_acces_usu'; // Clave primaria de la tabla acceso_usu

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = ['f_ingreso', 'f_salida', 'Documento_acces', 'id_Estado_usu']; // Campos permitidos para asignación masiva

    // Relación que indica a qué usuario pertenece este acceso
    public function usuario()
    {
        // El acceso pertenece a un usuario mediante la clave foránea Documento_acces
        return $this->belongsTo(User::class, 'Documento_acces', 'Documento');
    }
}
