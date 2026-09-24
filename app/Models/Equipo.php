<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa los equipos tecnológicos de la institución
class Equipo extends Model
{
    protected $table = 'equipo'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'serial_equi'; // Clave primaria: número de serie del equipo

    public $incrementing = false; // Indica que la clave primaria no es autoincremental

    protected $keyType = 'string'; // Tipo de la clave primaria: cadena de texto

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = ['serial_equi', 'id_t_equip', 'id_Marca', 'Color', 'imagen', 'Documento']; // Campos permitidos para asignación masiva

    // Relación que indica el tipo de equipo
    public function tipo()
    {
        // El equipo pertenece a un tipo de equipo mediante id_t_equip
        return $this->belongsTo(TipoEquipo::class, 'id_t_equip', 'id_t_equip');
    }

    // Relación que indica la marca del equipo
    public function marca()
    {
        // El equipo pertenece a una marca mediante id_Marca
        return $this->belongsTo(Marca::class, 'id_Marca', 'id_marca');
    }

    // Relación que indica el usuario al que está asignado el equipo
    public function usuario()
    {
        // El equipo pertenece a un usuario mediante el documento
        return $this->belongsTo(User::class, 'Documento', 'Documento');
    }

    // Relación con los préstamos realizados de este equipo
    public function prestamos()
    {
        // El equipo tiene muchos préstamos asociados por su número de serie
        return $this->hasMany(PrestamoEquipo::class, 'serial_equi', 'serial_equi');
    }
}
