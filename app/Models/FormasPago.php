<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormasPago extends Model
{
    protected $table = 'formas_pago';
    use HasFactory;
    protected $guarded;
}
