<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos el trait que permite usar fábricas en los modelos
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * @use HasFactory<AcudienteFactory>
 */
class Acudiente extends Model
{
    use HasFactory; // Habilita el uso de fábricas

    protected $table = 'acudiente'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'Documento_acud'; // Clave primaria: documento del acudiente

    public $incrementing = false; // Indica que la clave primaria no es autoincremental

    protected $keyType = 'string'; // Tipo de la clave primaria: cadena de texto

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'Documento_acud', // Documento de identidad del acudiente
        'nombre', // Nombre completo del acudiente
        'tel_acu', // Teléfono principal del acudiente
        'tel_acu2', // Teléfono alterno del acudiente
        'Direcccion_acu', // Dirección de residencia del acudiente
        'Correo_acud', // Correo electrónico del acudiente
    ];

    // Relación con los estudiantes que tienen a este acudiente
    public function estudiantes()
    {
        // El acudiente se vincula con varios usuarios a través de la tabla estudiante_acudiente
        return $this->belongsToMany(
            User::class, // Modelo del otro lado del vínculo
            'estudiante_acudiente', // Tabla intermedia
            'Documento_acud', // Columna de esta tabla en el vínculo
            'Documento_estu', // Columna del usuario en el vínculo
        )->withPivot('id_parentesco'); // Expone el parentesco guardado en el vínculo
    }

    // Relación con los vínculos crudos que lo relacionan con estudiantes
    public function vinculos()
    {
        // El acudiente tiene muchos vínculos en la tabla estudiante_acudiente
        return $this->hasMany(EstudianteAcudiente::class, 'Documento_acud', 'Documento_acud');
    }
}
