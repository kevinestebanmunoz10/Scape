<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa los departamentos del país
class Departamento extends Model
{
    protected $table = 'departamento'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'Codi_Departa'; // Clave primaria: código del departamento

    public $incrementing = false; // Indica que la clave primaria no es autoincremental

    protected $keyType = 'int'; // Tipo de la clave primaria: número entero

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'Codi_Departa', // Código del departamento
        'Departamento', // Nombre del departamento
    ];

    // Relación con las ciudades del departamento
    public function ciudades()
    {
        // El departamento tiene muchas ciudades que lo referencian por su código
        return $this->hasMany(Ciudad::class, 'Codi_Departa', 'Codi_Departa');
    }
}
