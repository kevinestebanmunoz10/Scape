<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos el trait que permite usar fábricas en los modelos
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * @use HasFactory<JornadaFactory>
 */
class Jornada extends Model
{
    use HasFactory; // Habilita el uso de fábricas

    protected $table = 'jornada'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_jornada'; // Clave primaria de la jornada

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'jornada', // Nombre de la jornada escolar
    ];

    // Relación con las matrículas que tienen asignada esta jornada
    public function matriculas()
    {
        // La jornada tiene muchas matrículas que la referencian por su nombre
        return $this->hasMany(Matricula::class, 'Jornada', 'jornada');
    }
}
