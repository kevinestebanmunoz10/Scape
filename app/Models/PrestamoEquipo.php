<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa los préstamos de equipos realizados a los usuarios
class PrestamoEquipo extends Model
{
    protected $table = 'prestamo_equipo'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_Prestamo_equi'; // Clave primaria del préstamo

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = ['f_Prestamo', 'f_devolucion', 'motivo', 'Documento_pres', 'serial_equi', 'id_estado']; // Campos permitidos para asignación masiva

    // Relación con el equipo prestado
    public function equipo()
    {
        // El préstamo pertenece a un equipo mediante su número de serie
        return $this->belongsTo(Equipo::class, 'serial_equi', 'serial_equi');
    }

    // Relación con el usuario que realiza el préstamo
    public function usuario()
    {
        // El préstamo pertenece al usuario mediante la clave foránea Documento_pres
        return $this->belongsTo(User::class, 'Documento_pres', 'Documento');
    }
}
