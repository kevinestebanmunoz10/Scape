<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesoEstu extends Model
{
    protected $table = 'acceso_estu';

    protected $primaryKey = 'id_acces_estu';

    public $timestamps = false;

    protected $fillable = ['f_ingreso', 'f_salida', 'Documento_estu', 'id_autorizacion'];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'Documento_estu', 'Documento');
    }
}
