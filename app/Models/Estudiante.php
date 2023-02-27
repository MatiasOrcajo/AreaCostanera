<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    protected $guarded;
    use HasFactory;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(FormasPago::class, 'forma_pago_id');
    }

    public function event()
    {
        return $this->belongsTo(Egresados::class, 'egresado_id');
    }

    public function specialMenu()
    {
        return $this->belongsTo(MenuEspecial::class, 'menu_especial');
    }

    public function people()
    {
        return $this->hasMany(EstudianteFamiliares::class, 'estudiante_id');
    }
}
