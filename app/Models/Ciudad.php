<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos el trait que permite usar fábricas en los modelos
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * @use HasFactory<CiudadFactory>
 */
class Ciudad extends Model
{
    use HasFactory; // Habilita el uso de fábricas

    protected $table = 'ciudad'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'cod_Postal'; // Clave primaria: código postal de la ciudad

    public $incrementing = false; // Indica que la clave primaria no es autoincremental

    protected $keyType = 'int'; // Tipo de la clave primaria: número entero

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'cod_Postal', // Código postal de la ciudad
        'Ciudad', // Nombre de la ciudad
        'Codi_Departa', // Código del departamento al que pertenece la ciudad
    ];

    // Relación con el departamento de la ciudad
    public function departamento()
    {
        // La ciudad pertenece a un departamento mediante Codi_Departa
        return $this->belongsTo(Departamento::class, 'Codi_Departa', 'Codi_Departa');
    }

    // Relación con las sedes ubicadas en esta ciudad
    public function sedes()
    {
        // La ciudad tiene muchas sedes que la referencian por su código postal
        return $this->hasMany(Sede::class, 'cod_postal', 'cod_Postal');
    }
}
