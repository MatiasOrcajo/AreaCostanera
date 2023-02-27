<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteFamiliares extends Model
{
    use HasFactory;
    protected $table = 'estudiante_familiares';
    protected $guarded;
}
