<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estado';

    protected $primaryKey = 'id_estado';

    public $timestamps = false;

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_Estado', 'id_estado');
    }
}
