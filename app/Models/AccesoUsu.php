<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesoUsu extends Model
{
    protected $table = 'acceso_usu';

    protected $primaryKey = 'id_acces_usu';

    public $timestamps = false;

    protected $fillable = ['f_ingreso', 'f_salida', 'Documento_acces', 'id_Estado_usu'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'Documento_acces', 'Documento');
    }
}
