<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa el registro de accesos de los visitantes
class AccesoVisi extends Model
{
    protected $table = 'acceso_visi'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_acces_visi'; // Clave primaria de la tabla acceso_visi

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = ['f_ingreso', 'f_salida', 'Documento_visi', 'id_Estado_visi', 'Lugar']; // Campos permitidos para asignación masiva

    // Relación que indica a qué visitante pertenece este acceso
    public function visitante()
    {
        // El acceso pertenece a un visitante mediante la clave foránea Documento_visi
        return $this->belongsTo(Visitante::class, 'Documento_visi', 'Docu_visi');
    }
}
