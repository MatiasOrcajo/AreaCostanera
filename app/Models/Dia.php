<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dia extends Model
{
    use HasFactory;
    protected $guarded;

    public function descuentos()
    {
        return $this->hasOne(DiasDescuentos::class, 'dia_id');
    }
}
