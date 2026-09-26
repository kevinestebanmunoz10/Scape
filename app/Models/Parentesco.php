<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos el trait que permite usar fábricas en los modelos
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * @use HasFactory<ParentescoFactory>
 */
class Parentesco extends Model
{
    use HasFactory; // Habilita el uso de fábricas

    protected $table = 'parentesco'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_parentesco'; // Clave primaria del parentesco

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'parentesco', // Nombre del parentesco
        'Estado_paren', // Estado del parentesco (1 = activo)
    ];

    // Filtra el alcance para devolver únicamente los parentescos activos
    public function scopeActivos($query)
    {
        // Restringe el alcance a los parentescos con estado activo
        return $query->where('Estado_paren', 1);
    }

    // Relación con los vínculos de estudiantes que usan este parentesco
    public function vinculos()
    {
        // El parentesco tiene muchos vínculos estudiante-acudiente asociados
        return $this->hasMany(EstudianteAcudiente::class, 'id_parentesco', 'id_parentesco');
    }
}
