<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos el trait que permite usar fábricas en los modelos
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * @use HasFactory<MatriculaFactory>
 */
class Matricula extends Model
{
    use HasFactory; // Habilita el uso de fábricas

    protected $table = 'matricula'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'numero_matri'; // Clave primaria: número de matrícula

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'grado', // Grado en el que está matriculado el estudiante
        'fecha_matri', // Fecha en la que se realizó la matrícula
        'Documento_matri', // Documento del estudiante matriculado
        'Jornada', // Jornada escolar del estudiante
        'Salon', // Código del salón asignado
        'Nit', // NIT de la sede donde se matricula
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // Define las conversiones de tipos de los atributos
    protected function casts(): array
    {
        return [
            'fecha_matri' => 'date', // La fecha de matrícula se maneja como una fecha
            'Nit' => 'integer', // El NIT de la sede se maneja como un entero
        ];
    }

    // Relación con el estudiante matriculado
    public function estudiante()
    {
        // La matrícula pertenece a un usuario mediante Documento_matri
        return $this->belongsTo(User::class, 'Documento_matri', 'Documento');
    }

    // Relación con el salón asignado
    public function salon()
    {
        // La matrícula pertenece a un salón mediante el código guardado en Salon
        return $this->belongsTo(Salon::class, 'Salon', 'codigo');
    }

    // Relación con la jornada escolar
    public function jornada()
    {
        // La matrícula pertenece a una jornada mediante el nombre guardado en Jornada
        return $this->belongsTo(Jornada::class, 'Jornada', 'jornada');
    }

    // Relación con la sede donde se realizó la matrícula
    public function sede()
    {
        // La matrícula pertenece a una sede mediante el NIT guardado en Nit
        return $this->belongsTo(Sede::class, 'Nit', 'Nit');
    }
}
