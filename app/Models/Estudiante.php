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

    public function payments()
    {
        return $this->hasMany(Pago::class, 'estudiante_id');
    }

    public function getRemainingDuesCount()
    {
        return count($this->cuotas) - count($this->cuotas->where('status', 1));
    }

    public function getPriceOfStudent()
    {
        $menuPrice = $this->resumen ? $this->resumen->precio_unitario : Egresados::find($this->egresado_id)->menu->precio;

        return $menuPrice - $menuPrice * $this->getTotalDiscounts() / 100;
    }

    public function getPriceOfAdults()
    {
        $adultsCount = count($this->people->where('tipo', 'adulto')->where('fuera_termino', 0)) + 1;
        $menuPrice = $this->resumen ? $this->resumen->precio_unitario : Egresados::find($this->egresado_id)->menu->precio;

        return $adultsCount * $menuPrice - ($adultsCount * $menuPrice) * $this->getTotalDiscounts() / 100;
    }

    public function getPriceOfMinorsOfTwelve()
    {
        $minorsCount = count($this->people->where('tipo', 'menor_12')->where('fuera_termino', 0));
        $menuPrice = $this->resumen ? $this->resumen->precio_unitario : Egresados::find($this->egresado_id)->menu->precio;

        return $minorsCount * $menuPrice / 2 - ($minorsCount * $menuPrice / 2) * $this->getTotalDiscounts() / 100;
    }

    public function getTotalDiscounts()
    {
        $special_discount = $this->event->discount->descuento ?? 0;

        return Egresados::find($this->egresado_id)->getEventDiscountByAmountOfStudents() + $this->event->day->descuento + $special_discount;
    }

    public function getTotalPrice()
    {
        if ($this->resumen == null) {
            $descuentoParaMenuDelEgresado = $this->event->menu->precio * $this->descuento_especial / 100;

            $total_adultos_egresado = $this->getPriceOfMinorsOfTwelve() + $this->getPriceOfAdults() - $descuentoParaMenuDelEgresado;

            $firstTotal = $total_adultos_egresado - array_sum($this->payments->where('tipo', 'adelanto')->pluck('amount')->toArray());

            $total =
                $firstTotal + ($firstTotal * $this->paymentType->interes / 100);

            $total += $total * $this->medioDePago->iva / 100;

            if($total < 100) $total = 0;

        } else {
            $total = $this->resumen->total;

            if($total < 100) $total = 0;
        }

        return round($total);

    }

    public function getDuesPayedAmount()
    {
        $payedDues = $this->cuotas->where('status', 1)->pluck('id')->map(function ($query) {
            return Pago::where('estudiantes_cuotas_id', $query)->pluck('amount')->first();
        });

        return array_sum($payedDues->toArray());
    }

    public function getTotalPriceWithAdvancePayments()
    {
        return round($this->getTotalPrice());
    }

    public function calculateDuesAmount()
    {
        $total = $this->getTotalPriceWithAdvancePayments();
        return $total / count($this->cuotas);
    }

    public function cuotas()
    {
        return $this->hasMany(EstudianteCuota::class, 'estudiante_id');
    }

    public function resumen()
    {
        return $this->hasOne(EstudiantesResumen::class, 'estudiante_id');
    }
}
