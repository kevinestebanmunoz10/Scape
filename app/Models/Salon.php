<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos el trait que permite usar fábricas en los modelos
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * @use HasFactory<SalonFactory>
 */
class Salon extends Model
{
    use HasFactory; // Habilita el uso de fábricas

    protected $table = 'salones'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_salon'; // Clave primaria del salón

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'codigo', // Código del salón
        'capacidad', // Número de estudiantes que admite el salón
        'id_Estado', // Identificador del estado del salón
    ];

    // Relación con las matrículas que tienen asignado este salón
    public function matriculas()
    {
        // El salón tiene muchas matrículas que lo referencian por su código
        return $this->hasMany(Matricula::class, 'Salon', 'codigo');
    }

    // Relación con el estado del salón
    public function estado()
    {
        // El salón pertenece a un estado mediante id_Estado
        return $this->belongsTo(Estado::class, 'id_Estado', 'id_estado');
    }
}
