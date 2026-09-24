<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la clase base del modelo de Eloquent
use Illuminate\Database\Eloquent\Model;

// Modelo que representa las autorizaciones de salida (permisos) registradas
class Autorizacion extends Model
{
    protected $table = 'autorizacion'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_autorizacion'; // Clave primaria de la autorización

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [ // Campos permitidos para asignación masiva
        'Permiso', // El tipo de permiso (médico, calamidad, etc.)
        'Descripcion', // La descripción del permiso
        'Fecha', // La fecha del permiso
        'id_rol', // El rol del usuario que autoriza
        'id_estu_acud', // La relación estudiante-acudiente
        'Documento_auto', // El documento del usuario que autoriza
    ];
}
