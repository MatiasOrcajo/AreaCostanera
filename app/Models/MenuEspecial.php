<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuEspecial extends Model
{
    use HasFactory;
    protected $table = 'menus_especiales';
    protected $guarded;
}
