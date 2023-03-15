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

    public function medioDePago()
    {
        return $this->belongsTo(MediosPago::class, 'medio_pago_id');
    }

    public function getPriceOfAdults()
    {
        $adultsCount = $this->familiares - $this->menores_5 - $this->menores_12;
        $priceWithoutDiscounts = $adultsCount * Egresados::find($this->egresado_id)->menu->precio;
        $discounts = Egresados::find($this->egresado_id)->getEventDiscountByAmountOfStudents();

        return ceil($priceWithoutDiscounts - ($priceWithoutDiscounts * $discounts / 100));
    }

    public function getPriceOfMinorsOfTwelve()
    {
        $minorsCount = $this->menores_12;
        $priceWithoutDiscounts = ($minorsCount * Egresados::find($this->egresado_id)->menu->precio) / 2;
        $discounts = Egresados::find($this->egresado_id)->getEventDiscountByAmountOfStudents();

        return ceil($priceWithoutDiscounts - ($priceWithoutDiscounts * $discounts / 100));
    }

    public function payments()
    {
        return $this->hasMany(Pago::class, 'estudiante_id');
    }

    public function getTotalPrice()
    {
        $iva = ($this->getPriceOfMinorsOfTwelve() + $this->getPriceOfAdults())* $this->medioDePago->iva / 100;

        return $this->getPriceOfMinorsOfTwelve() + $this->getPriceOfAdults() + $iva - array_sum($this->payments->pluck('amount')->toArray());
    }
}
