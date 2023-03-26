<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteCuota extends Model
{
    use HasFactory;
    protected $guarded;
    protected $table = 'estudiantes_cuotas';
}
