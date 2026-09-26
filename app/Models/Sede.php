<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos el trait que permite usar fábricas en los modelos
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * @use HasFactory<SedeFactory>
 */
class Sede extends Model
{
    use HasFactory; // Habilita el uso de fábricas

    protected $table = 'sede'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'Nit'; // Clave primaria: NIT de la sede

    public $incrementing = false; // Indica que la clave primaria no es autoincremental

    protected $keyType = 'int'; // Tipo de la clave primaria: número entero

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'Nit', // NIT que identifica a la sede
        'nombre', // Nombre de la sede
        'cod_postal', // Código postal de la ciudad donde está la sede
        'id_Estado', // Identificador del estado de la sede
    ];

    // Atributo calculado que indica si la sede está activa
    public function getEstaActivaAttribute(): bool
    {
        // Compara el estado de la sede contra el estado activo definido en la tabla 'estado'
        return (int) $this->id_Estado === 1;
    }

    // Filtra el alcance para devolver únicamente las sedes activas
    public function scopeActivas($query)
    {
        // Restringe el alcance a las sedes con estado activo
        return $query->where('id_Estado', 1);
    }

    // Relación con el estado de la sede
    public function estado()
    {
        // La sede pertenece a un estado mediante id_Estado
        return $this->belongsTo(Estado::class, 'id_Estado', 'id_estado');
    }

    // Relación con la ciudad donde está ubicada la sede
    public function ciudad()
    {
        // La sede pertenece a una ciudad mediante cod_postal
        return $this->belongsTo(Ciudad::class, 'cod_postal', 'cod_Postal');
    }

    // Relación con las matrículas realizadas en esta sede
    public function matriculas()
    {
        // La sede tiene muchas matrículas que la referencian por su NIT
        return $this->hasMany(Matricula::class, 'Nit', 'Nit');
    }
}
