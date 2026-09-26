<?php

// Declaración del espacio de nombres donde vive este modelo

namespace App\Models;

// Importamos la fábrica de usuarios para las pruebas y seeders
use Database\Factories\UserFactory;
// Importamos el trait que permite usar fábricas en los modelos
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Importamos la clase base de autenticación de Laravel
use Illuminate\Foundation\Auth\User as Authenticatable;
// Importamos el trait para enviar notificaciones
use Illuminate\Notifications\Notifiable;

// Modelo que representa a los usuarios del sistema (administradores, profesores, rectores, vigilantes y estudiantes)
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable; // Habilita el uso de fábricas y notificaciones
    // para definir a que tabla de la base de datos apunta

    protected $table = 'usuario'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'Documento'; // Clave primaria: documento de identidad

    public $incrementing = false; // Indica que la clave primaria no es autoincremental

    protected $keyType = 'string'; // Tipo de la clave primaria: cadena de texto

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    protected $fillable = [
        'Documento', // Documento de identidad del usuario
        'Nom_usua', // Nombre del usuario
        'email', // Correo electrónico del usuario
        'Telefono', // Teléfono del usuario
        'QR', // Código QR asociado al usuario
        'Contrasena', // Contraseña del usuario
        'id_rol', // Identificador del rol asignado
        'id_Estado', // Identificador del estado del usuario
        'cod_postal', // Código postal de la ciudad
        'NIT', // Número de identificación tributaria
    ];

    protected $hidden = [
        'Contrasena', // Oculta la contraseña al serializar el modelo
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
            'Contrasena' => 'hashed', // La contraseña se guardará cifrada
        ];
    }

    // Devuelve la contraseña que Laravel debe usar para la autenticación
    public function getAuthPassword(): string
    {
        return $this->Contrasena; // Retorna el valor del atributo Contrasena
    }

    /**
     * Get the dashboard route name that corresponds to the user's role.
     */
    // Devuelve la ruta del panel según el rol del usuario
    public function panelRoute(): string
    {
        return match ((int) $this->id_rol) { // Compara el id_rol del usuario como entero
            2 => 'profesor.dashboard', // Si es 2, panel de profesor
            3 => 'rector.dashboard', // Si es 3, panel de rector
            4 => 'vigilante.dashboard', // Si es 4, panel de vigilante
            default => 'dashboard', // Cualquier otro rol, panel general
        };
    }

    // Relación con el estado del usuario
    public function estado()
    {
        // El usuario pertenece a un estado mediante id_Estado
        return $this->belongsTo(Estado::class, 'id_Estado', 'id_estado');
    }

    // Relación con el rol del usuario
    public function rol()
    {
        // El usuario pertenece a un rol mediante id_rol
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Relación con la matrícula del estudiante
    public function matricula()
    {
        // El usuario tiene a lo sumo una matrícula, referenciada por su documento
        return $this->hasOne(Matricula::class, 'Documento_matri', 'Documento');
    }

    // Relación con los acudientes registrados para el estudiante
    public function acudientes()
    {
        // El usuario se vincula con varios acudientes a través de la tabla estudiante_acudiente
        return $this->belongsToMany(
            Acudiente::class, // Modelo del otro lado del vínculo
            'estudiante_acudiente', // Tabla intermedia
            'Documento_estu', // Columna de esta tabla en el vínculo
            'Documento_acud', // Columna del acudiente en el vínculo
        )->withPivot('id_parentesco'); // Expone el parentesco guardado en el vínculo
    }
}
