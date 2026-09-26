<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa el vínculo entre un estudiante y uno de sus acudientes
 *
 * La tabla estudiante_acudiente no tiene un modelo propio en el proyecto,
 * por eso se resuelve con la tabla intermedia belongsToMany de los dos lados.
 */
class EstudianteAcudiente extends Model
{
    protected $table = 'estudiante_acudiente'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_estu_acud'; // Clave primaria del vínculo

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'Documento_acud', // Documento del acudiente
        'Documento_estu', // Documento del estudiante
        'id_parentesco', // Parentesco entre el estudiante y el acudiente
    ];

    // Relación con el estudiante del vínculo
    public function estudiante()
    {
        // El vínculo pertenece a un usuario mediante Documento_estu
        return $this->belongsTo(User::class, 'Documento_estu', 'Documento');
    }

    // Relación con el acudiente del vínculo
    public function acudiente()
    {
        // El vínculo pertenece a un acudiente mediante Documento_acud
        return $this->belongsTo(Acudiente::class, 'Documento_acud', 'Documento_acud');
    }

    // Relación con el parentesco del vínculo
    public function parentesco()
    {
        // El vínculo pertenece a un parentesco mediante id_parentesco
        return $this->belongsTo(Parentesco::class, 'id_parentesco', 'id_parentesco');
    }
}
