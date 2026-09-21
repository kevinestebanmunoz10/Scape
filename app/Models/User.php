<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['Documento', 'Nom_usua', 'email', 'Telefono', 'QR', 'Contrasena', 'id_rol', 'id_Estado', 'cod_postal'])]
#[Hidden(['Contrasena'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuario';

    protected $primaryKey = 'Documento';

    public $incrementing = false;

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Contrasena' => 'hashed',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->Contrasena;
    }

    /**
     * Get the dashboard route name that corresponds to the user's role.
     */
    public function panelRoute(): string
    {
        return match ((int) $this->id_rol) {
            2 => 'profesor.dashboard',
            3 => 'rector.dashboard',
            4 => 'vigilante.dashboard',
            default => 'dashboard',
        };
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_Estado', 'id_estado');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
