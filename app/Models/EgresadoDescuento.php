<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EgresadoDescuento extends Model
{
    use HasFactory;
    protected $table = 'egresado_descuentos';
    protected $guarded;
}
